<?php
/**
 * Cetak Bukti Pembayaran
 */

session_start();
require_once '../../config/database.php';
require_once '../../config/app.php';

requirePaymentAccess();
$scope = paymentClassScope();

$id_pembayaran = isset($_GET['id']) ? (int) sanitize($_GET['id']) : 0;

if ($id_pembayaran <= 0) {
    die('Parameter pembayaran tidak valid!');
}

$payment = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT p.*, s.nama, k.nama_kelas, g.nama as nama_petugas, b.nama_iuran
    FROM pembayaran p
    JOIN santri s ON p.id_santri = s.id_santri
    JOIN kelas k ON s.id_kelas = k.id_kelas
    LEFT JOIN users g ON p.id_user = g.id_user
    JOIN iuran b ON p.id_iuran = b.id_iuran
    WHERE p.id_pembayaran = '$id_pembayaran' AND $scope
"));

if (!$payment) {
    die('Pembayaran tidak ditemukan!');
}

// Kop surat memakai komponen bersama agar bentuknya sama dengan
// kop pada surat resmi TPQ dan pada laporan keuangan.
require_once '../../includes/kop_surat.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Bukti Pembayaran - <?php echo htmlspecialchars($payment['id_pembayaran']); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .container {
            max-width: 680px;
            margin: 20px auto;
            padding: 20px;
            border: 2px solid #4e73df;
            border-radius: 10px;
        }

        /* Kop surat resmi TPQ (komponen bersama) */
        <?php echo kopSuratCss(false); ?>

        .title {
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            background: #4e73df;
            color: #fff;
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 12px;
        }

        .meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 24px;
            margin-bottom: 14px;
        }

        .meta .item {
            display: flex;
        }

        .meta .label {
            width: 115px;
            color: #666;
        }

        .meta .value {
            flex: 1;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background: #f8f9fc;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .total-wrap {
            margin-top: 12px;
            padding: 10px;
            border-radius: 6px;
            background: #f8f9fc;
        }

        .total-wrap .line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .total-wrap .line:last-child {
            margin-bottom: 0;
            font-size: 16px;
            font-weight: 700;
            color: #1cc88a;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #999;
            margin-top: 16px;
            border-top: 2px dashed #ccc;
            padding-top: 12px;
        }

        .print-btn {
            display: block;
            width: 100%;
            border: none;
            border-radius: 6px;
            margin-top: 14px;
            padding: 10px;
            font-size: 14px;
            cursor: pointer;
            color: #fff;
            background: #4e73df;
        }

        .print-btn:hover {
            background: #2e59d9;
        }

        @media print {
            .print-btn {
                display: none;
            }

            .container {
                border: 1px solid #000;
                margin: 0;
                max-width: 100%;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Kop surat resmi: bukti pembayaran menampilkan kontak juga -->
        <?php echo kopSuratHtml(true); ?>

        <div class="title">BUKTI PEMBAYARAN</div>

        <div class="meta">
            <div class="item"><span class="label">Tanggal</span><span
                    class="value"><?php echo tanggalIndo($payment['tgl_bayar']); ?></span></div>
            <div class="item"><span class="label">Nama Santri</span><span
                    class="value"><?php echo htmlspecialchars($payment['nama']); ?></span></div>
            <div class="item"><span class="label">Kelas</span><span
                    class="value"><?php echo htmlspecialchars($payment['nama_kelas']); ?></span></div>
            <div class="item"><span class="label">Petugas</span><span
                    class="value"><?php echo htmlspecialchars($payment['nama_petugas']); ?></span></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Tagihan</th>
                    <th>Periode</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><?php echo htmlspecialchars($payment['nama_iuran']); ?></td>
                    <td><?php echo htmlspecialchars(formatPeriodeTagihan($payment['periode_tipe'] ?: 'bulanan', $payment['bulan_dibayar'], $payment['tahun_dibayar'])); ?></td>
                    <td class="text-right"><?php echo formatRupiah($payment['jumlah_bayar']); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="total-wrap">
            <div class="line"><span>Sub Total
                    Item</span><span><?php echo formatRupiah($payment['jumlah_bayar']); ?></span></div>
            <div class="line"><span>TOTAL DIBAYAR</span><span><?php echo formatRupiah($payment['jumlah_bayar']); ?></span>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih atas pembayaran Anda.</p>
            <p>Simpan bukti ini sebagai arsip resmi.</p>
        </div>

        <button class="print-btn" onclick="window.print()">Cetak Bukti Pembayaran</button>
    </div>
</body>

</html>
