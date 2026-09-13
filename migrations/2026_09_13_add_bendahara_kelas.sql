-- Jalankan pada database aplikasi melalui phpMyAdmin sebelum memakai fitur ini.
-- Aman dijalankan ulang. Tidak mengubah santri, iuran, atau riwayat pembayaran.
-- Setelah migrasi, admin WAJIB memilih kelas di Data Pengguna > Edit Bendahara.
-- Tanpa penugasan, bendahara tidak dapat menginput pembayaran kelas mana pun.
CREATE TABLE IF NOT EXISTS bendahara_kelas (
    id_user INT(11) NOT NULL,
    id_kelas INT(11) NOT NULL,
    PRIMARY KEY (id_user, id_kelas),
    KEY idx_bendahara_kelas_kelas (id_kelas),
    CONSTRAINT fk_bendahara_kelas_user FOREIGN KEY (id_user)
        REFERENCES users (id_user) ON DELETE CASCADE,
    CONSTRAINT fk_bendahara_kelas_kelas FOREIGN KEY (id_kelas)
        REFERENCES kelas (id_kelas) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
