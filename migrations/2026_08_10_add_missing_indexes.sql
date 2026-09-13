-- Migration: add missing indexes
-- Date: 2026-08-10
-- Reason: santri_iuran, pembayaran.tgl_bayar, jurnal_umum.tanggal, and
-- santri.status are filtered/joined constantly (halaman santri, form
-- pembayaran, laporan bulanan, api/get_*_iuran.php) but had no index,
-- forcing full table scans on every request. See optimization review
-- for details.
--
-- Safe to run on the existing database — only adds indexes, no data
-- is changed. Run once:
--   mysql -u root -p keuangan_tpq < migrations/2026_08_10_add_missing_indexes.sql

-- santri_iuran: joined/filtered by id_santri and id_iuran in
-- getSantriMonthlyProgressMap(), getSantriYearlyProgressMap(),
-- api/get_kelas_iuran.php, api/get_santri_iuran.php
ALTER TABLE `santri_iuran`
  ADD KEY `idx_santri_iuran_santri` (`id_santri`),
  ADD KEY `idx_santri_iuran_iuran` (`id_iuran`);

-- pembayaran: tgl_bayar is filtered on every load of the Pembayaran
-- page and every monthly report. Composite index covers
-- date-range + jenis filters together.
ALTER TABLE `pembayaran`
  ADD KEY `idx_pembayaran_tgl_bayar` (`tgl_bayar`);

-- jurnal_umum: tanggal is filtered 12x per year in laporan/index.php
-- and export.php (see 2026_08_10_optimize_laporan_query for the
-- accompanying query rewrite that lets this index actually be used).
ALTER TABLE `jurnal_umum`
  ADD KEY `idx_jurnal_tanggal_jenis` (`tanggal`, `jenis`);

-- santri: status is filtered on almost every santri listing/report
-- ("active" vs "nonaktif").
ALTER TABLE `santri`
  ADD KEY `idx_santri_status` (`status`);

-- log_aktivitas: activity log is typically browsed/filtered by date
-- (most recent first) and by user.
ALTER TABLE `log_aktivitas`
  ADD KEY `idx_log_created_at` (`created_at`);
