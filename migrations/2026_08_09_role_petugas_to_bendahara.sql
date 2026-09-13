-- ============================================================================
-- Ganti nilai role 'petugas' -> 'bendahara' pada users.level
--
-- Urutan penting:
--   1. perluas enum jadi ('admin','petugas','bendahara','kepala_tpq')
--   2. pindahkan data yang masih 'petugas' -> 'bendahara'
--   3. persempit enum jadi ('admin','bendahara','kepala_tpq'), DEFAULT 'bendahara'
--
-- Nilai historis di log_aktivitas.tabel TIDAK diubah (itu jejak audit).
--
-- File ini IDEMPOTEN: aman dijalankan berkali-kali.
-- BACKUP DULU (lihat backups/pre_role_bendahara_*.sql).
--
-- Cara jalankan:
--   mysql -u root keuangan_tpq < migrations/2026_08_09_role_petugas_to_bendahara.sql
-- ============================================================================

-- 1. enum sementara memuat kedua nilai supaya UPDATE tidak ditolak
SET @sql := (SELECT IF(
  COLUMN_TYPE <> "enum('admin','bendahara','kepala_tpq')",
  'ALTER TABLE `users` MODIFY COLUMN `level`
     ENUM(''admin'',''petugas'',''bendahara'',''kepala_tpq'')
     NOT NULL DEFAULT ''petugas''',
  'DO 0')
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'level');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- 2. pindahkan data
UPDATE `users` SET `level` = 'bendahara' WHERE `level` = 'petugas';

-- 3. buang nilai lama dari enum, set default baru
ALTER TABLE `users`
  MODIFY COLUMN `level` ENUM('admin','bendahara','kepala_tpq')
  NOT NULL DEFAULT 'bendahara';
