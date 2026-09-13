-- ============================================================================
-- Rename tabel `petugas` -> `users` beserta kolomnya.
--
--   petugas.id_petugas    -> users.id_user
--   petugas.nama_petugas  -> users.nama
--   pembayaran.id_petugas    -> pembayaran.id_user
--   log_aktivitas.id_petugas -> log_aktivitas.id_user
--
-- Nilai enum level ('admin','petugas','kepala_tpq') TIDAK diubah.
--
-- File ini IDEMPOTEN: aman dijalankan berkali-kali.
-- BACKUP DATABASE SEBELUM MENJALANKAN (lihat backups/pre_rename_users_*.sql).
--
-- Cara jalankan:
--   mysql -u root keuangan_tpq < migrations/2026_08_09_rename_petugas_to_users.sql
-- ============================================================================

-- Helper: jalankan SQL hanya bila kondisi terpenuhi.
-- (dipakai lewat pola PREPARE/EXECUTE di bawah)

-- ---------------------------------------------------------------------------
-- 1. Drop FK anak dulu supaya tabel induk bisa di-rename.
-- ---------------------------------------------------------------------------
SET @sql := (SELECT IF(COUNT(*) > 0,
  'ALTER TABLE `pembayaran` DROP FOREIGN KEY `fk_pembayaran_petugas`', 'DO 0')
  FROM information_schema.TABLE_CONSTRAINTS
  WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'pembayaran'
    AND CONSTRAINT_NAME = 'fk_pembayaran_petugas' AND CONSTRAINT_TYPE = 'FOREIGN KEY');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql := (SELECT IF(COUNT(*) > 0,
  'ALTER TABLE `log_aktivitas` DROP FOREIGN KEY `fk_log_petugas`', 'DO 0')
  FROM information_schema.TABLE_CONSTRAINTS
  WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'log_aktivitas'
    AND CONSTRAINT_NAME = 'fk_log_petugas' AND CONSTRAINT_TYPE = 'FOREIGN KEY');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- ---------------------------------------------------------------------------
-- 2. Rename tabel petugas -> users.
-- ---------------------------------------------------------------------------
SET @sql := (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.TABLES
      WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'petugas') > 0
    AND
    (SELECT COUNT(*) FROM information_schema.TABLES
      WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users') = 0,
  'RENAME TABLE `petugas` TO `users`', 'DO 0'));
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- ---------------------------------------------------------------------------
-- 3. Rename kolom di users: id_petugas -> id_user, nama_petugas -> nama.
-- ---------------------------------------------------------------------------
SET @sql := (SELECT IF(COUNT(*) > 0,
  'ALTER TABLE `users` CHANGE `id_petugas` `id_user` INT(11) NOT NULL AUTO_INCREMENT', 'DO 0')
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'id_petugas');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql := (SELECT IF(COUNT(*) > 0,
  'ALTER TABLE `users` CHANGE `nama_petugas` `nama` VARCHAR(100) NOT NULL', 'DO 0')
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'nama_petugas');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- ---------------------------------------------------------------------------
-- 4. Rename kolom FK di tabel anak: id_petugas -> id_user.
-- ---------------------------------------------------------------------------
SET @sql := (SELECT IF(COUNT(*) > 0,
  'ALTER TABLE `pembayaran` CHANGE `id_petugas` `id_user` INT(11) NOT NULL', 'DO 0')
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pembayaran' AND COLUMN_NAME = 'id_petugas');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql := (SELECT IF(COUNT(*) > 0,
  'ALTER TABLE `log_aktivitas` CHANGE `id_petugas` `id_user` INT(11) DEFAULT NULL', 'DO 0')
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'log_aktivitas' AND COLUMN_NAME = 'id_petugas');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- ---------------------------------------------------------------------------
-- 5. Pasang ulang FK dengan nama baru.
-- ---------------------------------------------------------------------------
SET @sql := (SELECT IF(COUNT(*) = 0,
  'ALTER TABLE `pembayaran`
     ADD CONSTRAINT `fk_pembayaran_user`
     FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`)', 'DO 0')
  FROM information_schema.TABLE_CONSTRAINTS
  WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'pembayaran'
    AND CONSTRAINT_NAME = 'fk_pembayaran_user' AND CONSTRAINT_TYPE = 'FOREIGN KEY');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql := (SELECT IF(COUNT(*) = 0,
  'ALTER TABLE `log_aktivitas`
     ADD CONSTRAINT `fk_log_user`
     FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL', 'DO 0')
  FROM information_schema.TABLE_CONSTRAINTS
  WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'log_aktivitas'
    AND CONSTRAINT_NAME = 'fk_log_user' AND CONSTRAINT_TYPE = 'FOREIGN KEY');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
