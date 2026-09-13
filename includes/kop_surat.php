<?php
/**
 * Kop Surat (Letterhead) — komponen bersama
 * Sistem Keuangan TPQ Al-Ikhlas Metro Parung
 *
 * Tujuan: menyeragamkan kop surat pada SEMUA output cetak / export PDF
 * agar tampil persis seperti kop pada surat resmi TPQ
 * (logo di kiri, nama lembaga + alamat sekretariat rata tengah,
 * garis ganda tebal sebagai pembatas, font serif / Times New Roman).
 *
 * Cara pakai:
 *
 *   require_once __DIR__ . '/../../includes/kop_surat.php';
 *
 *   // untuk Dompdf / cetak
 *   echo kopSuratCss();          // di dalam <style>
 *   echo kopSuratHtml();         // di awal <body>
 *
 * Semua data diambil dari tabel `settings` (menu Pengaturan > Umum),
 * jadi kop otomatis ikut berubah saat pengaturan lembaga diubah.
 */

if (!function_exists('getSetting')) {
    require_once dirname(__DIR__) . '/config/database.php';
}

/**
 * Ambil seluruh data lembaga untuk kop surat.
 *
 * Nilai di-cache per-request supaya tidak query settings berulang kali
 * ketika satu halaman memanggil kop + tanda tangan sekaligus.
 *
 * @return array{nama:string,alamat:string,telp:string,email:string,website:string,kota:string,kepala:string}
 */
function kopSuratData()
{
    static $data = null;
    if ($data !== null) {
        return $data;
    }

    $data = [
        'nama'     => trim((string) getSetting('nama_sekolah', 'TPQ AL-IKHLAS METRO PARUNG')),
        'alamat'   => trim((string) getSetting('alamat_sekolah', '')),
        'telp'     => trim((string) getSetting('telp_sekolah', '')),
        'email'    => trim((string) getSetting('email_sekolah', '')),
        'website'  => trim((string) getSetting('website_sekolah', '')),
        'kota'     => trim((string) getSetting('kota_sekolah', 'Parung')),
        'kepala'   => trim((string) getSetting('kepala_tpq', '')),
    ];

    if ($data['nama'] === '') {
        $data['nama'] = 'TPQ AL-IKHLAS METRO PARUNG';
    }
    if ($data['kota'] === '') {
        $data['kota'] = 'Parung';
    }

    return $data;
}

/**
 * Baris "Sekretariat: ..." pada kop.
 *
 * Pada surat resmi, alamat sekretariat ditulis sebagai satu kalimat panjang
 * yang otomatis membungkus ke baris kedua. Alamat pada pengaturan boleh
 * ditulis multi-baris; di sini baris-baris tersebut disatukan dengan koma
 * supaya hasilnya tetap rapi seperti pada surat.
 *
 * @return string teks polos (belum di-escape)
 */
function kopSuratAlamatText()
{
    $data = kopSuratData();
    $alamat = $data['alamat'];

    if ($alamat === '') {
        return '';
    }

    // Normalisasi: pecah per baris, buang baris kosong, gabung dengan koma.
    $parts = preg_split('/\r\n|\r|\n/', $alamat);
    $parts = array_filter(array_map('trim', $parts), function ($p) {
        return $p !== '';
    });
    $alamat = implode(', ', $parts);

    // Rapikan koma/spasi ganda akibat penggabungan.
    $alamat = preg_replace('/\s*,\s*,+/', ', ', $alamat);
    $alamat = preg_replace('/\s{2,}/', ' ', $alamat);
    $alamat = trim($alamat, " \t,");

    // Awali dengan "Sekretariat:" bila belum ada, mengikuti format surat resmi.
    if (stripos($alamat, 'sekretariat') !== 0) {
        $alamat = 'Sekretariat: ' . $alamat;
    }

    return $alamat;
}

/**
 * Baris kontak opsional (telp / email / website) di bawah alamat.
 *
 * @return string teks polos (belum di-escape), kosong bila tidak ada kontak
 */
function kopSuratKontakText()
{
    $data = kopSuratData();
    $kontak = [];

    if ($data['telp'] !== '') {
        $kontak[] = 'Telp: ' . $data['telp'];
    }
    if ($data['email'] !== '') {
        $kontak[] = 'Email: ' . $data['email'];
    }
    if ($data['website'] !== '') {
        $kontak[] = 'Web: ' . $data['website'];
    }

    return implode('  |  ', $kontak);
}

/**
 * Logo lembaga sebagai data URI base64.
 *
 * Dompdf dijalankan dengan isRemoteEnabled = false, sehingga <img src="http://...">
 * tidak akan ter-render. Menyisipkan gambar sebagai data URI adalah cara paling
 * aman: tidak butuh akses jaringan maupun izin baca path absolut.
 *
 * @return string data URI, atau string kosong bila file logo tidak ditemukan
 */
function kopSuratLogoDataUri()
{
    static $uri = null;
    if ($uri !== null) {
        return $uri;
    }

    $uri = '';
    $dir = dirname(__DIR__) . '/assets/img/';

    // Nama file logo bisa diatur lewat settings; sediakan fallback bawaan.
    $candidates = [];
    $from_setting = trim((string) getSetting('logo_sekolah', ''));
    if ($from_setting !== '') {
        $candidates[] = basename($from_setting);
    }
    $candidates[] = 'logo.jpg';
    $candidates[] = 'logo.png';

    foreach ($candidates as $file) {
        $path = $dir . $file;
        if (!is_file($path) || !is_readable($path)) {
            continue;
        }

        $bytes = @file_get_contents($path);
        if ($bytes === false || $bytes === '') {
            continue;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = ($ext === 'png') ? 'image/png' : (($ext === 'gif') ? 'image/gif' : 'image/jpeg');

        $uri = 'data:' . $mime . ';base64,' . base64_encode($bytes);
        break;
    }

    return $uri;
}

/**
 * CSS kop surat — dipakai baik oleh Dompdf maupun halaman cetak browser.
 *
 * Catatan Dompdf: layout kop memakai <table> (bukan flexbox) karena
 * Dompdf tidak mendukung flexbox. Garis ganda dibuat dari dua <div>
 * bergaris, sebab border-style "double" milik Dompdf terlalu tipis
 * untuk menyerupai garis pada surat asli.
 *
 * @param bool $for_pdf true = ukuran untuk Dompdf, false = untuk layar/print browser
 * @return string blok CSS tanpa tag <style>
 */
function kopSuratCss($for_pdf = true)
{
    // Ukuran sedikit berbeda: Dompdf memakai px absolut, layar boleh lebih besar.
    $font_nama   = $for_pdf ? '15px' : '19px';
    $font_alamat = $for_pdf ? '11px' : '13px';
    $font_kontak = $for_pdf ? '9px'  : '11px';
    $logo_size   = $for_pdf ? '74px' : '92px';
    $logo_cell   = $for_pdf ? '92px' : '115px';

    return "
        /* ===== Kop Surat Resmi TPQ ===== */
        .kop-surat {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 4px 0;
        }
        .kop-surat td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .kop-surat .kop-logo-cell {
            width: {$logo_cell};
            text-align: center;
        }
        .kop-surat .kop-logo-cell img {
            width: {$logo_size};
            height: auto;
        }
        .kop-surat .kop-teks {
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            color: #000;
        }
        .kop-surat .kop-nama {
            font-size: {$font_nama};
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin: 0 0 3px 0;
            line-height: 1.25;
        }
        .kop-surat .kop-alamat {
            font-size: {$font_alamat};
            font-weight: bold;
            margin: 0;
            line-height: 1.4;
        }
        .kop-surat .kop-kontak {
            font-size: {$font_kontak};
            font-weight: normal;
            margin: 2px 0 0 0;
            line-height: 1.3;
        }
        /* Garis ganda pembatas kop (tebal di atas, tipis di bawah) */
        .kop-garis-tebal {
            border-bottom: 3px solid #000;
            margin-top: 6px;
        }
        .kop-garis-tipis {
            border-bottom: 1px solid #000;
            margin-top: 2px;
            margin-bottom: 14px;
        }
    ";
}

/**
 * HTML kop surat lengkap dengan garis pembatas ganda.
 *
 * Baris kontak sengaja dimatikan secara default: kop pada surat resmi TPQ
 * hanya memuat nama lembaga dan alamat sekretariat. Halaman yang memang
 * perlu menampilkan nomor telepon (mis. bukti pembayaran) bisa memanggil
 * kopSuratHtml(true).
 *
 * @param bool $with_kontak tampilkan baris telp/email/web (default: false)
 * @return string potongan HTML siap di-echo
 */
function kopSuratHtml($with_kontak = false)
{
    $data   = kopSuratData();
    $logo   = kopSuratLogoDataUri();
    $alamat = kopSuratAlamatText();
    $kontak = $with_kontak ? kopSuratKontakText() : '';

    $html  = '<table class="kop-surat">';
    $html .= '<tr>';

    // Sel logo hanya dirender bila file logo benar-benar ada, supaya
    // teks kop tetap berada di tengah halaman ketika logo tidak tersedia.
    if ($logo !== '') {
        $html .= '<td class="kop-logo-cell"><img src="' . $logo . '" alt="Logo"></td>';
    }

    $html .= '<td class="kop-teks">';
    $html .= '<div class="kop-nama">' . htmlspecialchars($data['nama'], ENT_QUOTES, 'UTF-8') . '</div>';

    if ($alamat !== '') {
        $html .= '<div class="kop-alamat">' . htmlspecialchars($alamat, ENT_QUOTES, 'UTF-8') . '</div>';
    }
    if ($kontak !== '') {
        $html .= '<div class="kop-kontak">' . htmlspecialchars($kontak, ENT_QUOTES, 'UTF-8') . '</div>';
    }

    $html .= '</td>';

    // Sel kosong penyeimbang di kanan agar blok teks benar-benar center
    // terhadap lebar kertas, bukan center terhadap sisa ruang di kanan logo.
    if ($logo !== '') {
        $html .= '<td class="kop-logo-cell"></td>';
    }

    $html .= '</tr></table>';
    $html .= '<div class="kop-garis-tebal"></div>';
    $html .= '<div class="kop-garis-tipis"></div>';

    return $html;
}

/**
 * Teks tempat & tanggal untuk blok tanda tangan, mis. "Parung, 16 Agustus 2026".
 *
 * @param string|null $date tanggal Y-m-d (default: hari ini)
 * @return string
 */
function kopSuratTempatTanggal($date = null)
{
    $data = kopSuratData();
    $date = $date ?: date('Y-m-d');
    return $data['kota'] . ', ' . tanggalIndo($date);
}

/**
 * Nama penanda tangan laporan.
 *
 * Prioritas: pengaturan "kepala_tpq" -> nama user yang sedang login -> garis kosong.
 *
 * @return string
 */
function kopSuratPenandaTangan()
{
    $data = kopSuratData();
    if ($data['kepala'] !== '') {
        return $data['kepala'];
    }
    if (!empty($_SESSION['nama_user'])) {
        return $_SESSION['nama_user'];
    }
    return '..............................';
}

/**
 * Jabatan penanda tangan.
 *
 * @return string
 */
function kopSuratJabatan()
{
    $data = kopSuratData();
    return $data['kepala'] !== '' ? 'Kepala TPQ Al-Ikhlas,' : 'Petugas,';
}
