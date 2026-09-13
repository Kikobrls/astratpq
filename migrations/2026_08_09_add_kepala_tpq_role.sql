-- Migration: Add kepala_tpq role
-- Date: 2026-08-09
--
-- Catatan sejarah:
--   * tabel `petugas` sudah di-rename menjadi `users`
--     (lihat 2026_08_09_rename_petugas_to_users.sql)
--   * nilai role 'petugas' sudah diganti 'bendahara'
--     (lihat 2026_08_09_role_petugas_to_bendahara.sql)
--
-- Migrasi ini HARUS no-op bila role sudah bermigrasi ke 'bendahara'.
-- Kalau tetap dipaksa MODIFY ke enum lama, semua baris ber-level 'bendahara'
-- akan terpotong menjadi string kosong. Karena itu ada penjaga di bawah.

SET @tbl := IF(
  (SELECT COUNT(*) FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users') > 0,
  'users', 'petugas');

-- Penjaga: hanya jalan bila enum masih memuat 'petugas' (skema pra-bendahara).
SET @needs := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = @tbl
    AND COLUMN_NAME = 'level'
    AND COLUMN_TYPE LIKE '%''petugas''%'
    AND COLUMN_TYPE NOT LIKE '%''kepala_tpq''%');

SET @sql := IF(@needs > 0,
  CONCAT('ALTER TABLE `', @tbl, '` ',
         'MODIFY COLUMN `level` ENUM(''admin'', ''petugas'', ''kepala_tpq'') ',
         'NOT NULL DEFAULT ''petugas'''),
  'DO 0');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
