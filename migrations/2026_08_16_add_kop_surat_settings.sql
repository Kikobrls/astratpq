-- Migration: tambah setting untuk kop surat & tanda tangan laporan
-- Date: 2026-08-16
-- Alasan: kop surat pada export PDF sekarang mengikuti format surat resmi
-- TPQ Al-Ikhlas. Blok tanggal & tanda tangan sebelumnya di-hardcode
-- ("Jakarta, ..." dan nama user yang login), padahal pada surat resmi
-- tertulis kota lembaga dan nama Kepala TPQ.
--
-- Dua setting baru:
--   kota_sekolah -> kota pada baris tanggal, mis. "Parung, 16 Agustus 2026"
--   kepala_tpq   -> nama penanda tangan laporan
--
-- Keduanya bisa diubah lewat menu Pengaturan > Umum.
-- Aman dijalankan berulang: memakai INSERT ... ON DUPLICATE KEY UPDATE
-- sehingga nilai yang sudah diisi admin tidak akan tertimpa.
--
-- Cara menjalankan (sesuaikan nama database):
--   mysql -u root -p keuangan_tpq < migrations/2026_08_16_add_kop_surat_settings.sql

-- setting_key harus unik agar ON DUPLICATE KEY UPDATE bekerja.
-- Baris ini akan gagal (duplicate key name) bila indeks sudah ada —
-- abaikan errornya, artinya indeks memang sudah terpasang.
ALTER TABLE `settings` ADD UNIQUE KEY `uq_settings_key` (`setting_key`);

INSERT INTO `settings`
    (`setting_key`, `setting_value`, `setting_type`, `setting_group`, `description`)
VALUES
    ('kota_sekolah', 'Parung', 'text', 'general', 'Kota pada tanggal surat / laporan'),
    ('kepala_tpq', '', 'text', 'general', 'Nama Kepala TPQ (penanda tangan laporan)')
ON DUPLICATE KEY UPDATE
    `description` = VALUES(`description`);
