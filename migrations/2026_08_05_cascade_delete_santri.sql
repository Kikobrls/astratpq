-- ============================================================================
-- Hapus santri -> riwayat pembayaran & tagihannya ikut terhapus otomatis
--
-- PERINGATAN: pembayaran yang terhapus TIDAK bisa dikembalikan, dan Sisa Kas
-- serta laporan bulan-bulan sebelumnya akan berubah (lihat index.php:42-45,
-- di mana SUM(pembayaran.jumlah_bayar) dipakai menghitung saldo).
-- BACKUP DATABASE SEBELUM MENJALANKAN FILE INI.
--
-- File ini IDEMPOTEN: aman dijalankan berkali-kali. Kalau constraint sudah
-- ada, langkahnya dilewati (bukan error 1005 / errno 121).
--
-- Cara jalankan (phpMyAdmin: pilih db keuangan_tpq -> tab SQL -> paste):
--   mysql -u root keuangan_tpq < migrations/2026_08_05_cascade_delete_santri.sql
-- ============================================================================

-- 1. pembayaran: pastikan FK ke santri memakai ON DELETE CASCADE.
--    Drop dulu kalau ada (apa pun aturan lamanya), lalu buat ulang.
SET @sql := (
  SELECT IF(COUNT(*) > 0,
    'ALTER TABLE `pembayaran` DROP FOREIGN KEY `fk_santri`',
    'DO 0')
  FROM information_schema.TABLE_CONSTRAINTS
  WHERE CONSTRAINT_SCHEMA = DATABASE()
    AND TABLE_NAME = 'pembayaran'
    AND CONSTRAINT_NAME = 'fk_santri'
    AND CONSTRAINT_TYPE = 'FOREIGN KEY'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_santri`
  FOREIGN KEY (`id_santri`) REFERENCES `santri` (`id_santri`)
  ON DELETE CASCADE;

-- 2. santri_iuran: buat relasi ke santri kalau belum ada. Baris yatim
--    dibersihkan lebih dulu, kalau tidak penambahan constraint ditolak MySQL.
--    (MySQL otomatis membuat index untuk kolom FK, jadi tidak perlu ADD KEY.)
DELETE FROM `santri_iuran`
WHERE `id_santri` NOT IN (SELECT `id_santri` FROM `santri`);

SET @sql := (
  SELECT IF(COUNT(*) > 0,
    'DO 0',
    'ALTER TABLE `santri_iuran`
       ADD CONSTRAINT `fk_santri_iuran_santri`
       FOREIGN KEY (`id_santri`) REFERENCES `santri` (`id_santri`)
       ON DELETE CASCADE')
  FROM information_schema.TABLE_CONSTRAINTS
  WHERE CONSTRAINT_SCHEMA = DATABASE()
    AND TABLE_NAME = 'santri_iuran'
    AND CONSTRAINT_NAME = 'fk_santri_iuran_santri'
    AND CONSTRAINT_TYPE = 'FOREIGN KEY'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
