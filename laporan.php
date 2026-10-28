<?php
include 'cek_login.php';
include 'koneksi.php';


/* =========================
   LAPORAN BARANG MASUK
========================= */

$masuk = mysqli_query($koneksi, "
    SELECT 
        barang.nama_barang,
        barang.kode_barang,
        barang_masuk.tanggal,
        barang_masuk.jumlah,
        barang_masuk.asal_barang,
        barang_masuk.keterangan
    FROM barang_masuk
    JOIN barang 
        ON barang_masuk.id_barang = barang.id_barang
    ORDER BY barang_masuk.tanggal DESC
");


/* =========================
   LAPORAN BARANG KELUAR
========================= */

$keluar = mysqli_query($koneksi, "
    SELECT 
        barang.nama_barang,
        barang.kode_barang,
        barang_keluar.tanggal,
        barang_keluar.jumlah,
        barang_keluar.tujuan,
        barang_keluar.keterangan
    FROM barang_keluar
    JOIN barang 
        ON barang_keluar.id_barang = barang.id_barang
    ORDER BY barang_keluar.tanggal DESC
");


/* =========================
   LAPORAN STOK
========================= */

$stok = mysqli_query($koneksi, "
    SELECT * 
    FROM barang
    ORDER BY nama_barang ASC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Gudang</title>


    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }


        body {
            font-family: Arial, Helvetica, sans-serif;

            background: #fff9fb;

            color: #5f5360;
        }


        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            position: fixed;

            left: 0;
            top: 0;

            width: 170px;

            height: 100vh;

            background: #ffffff;

            border-right: 1px solid #f0d7e2;

            padding: 22px 12px;
        }


        .sidebar-title {
            text-align: center;

            margin-bottom: 30px;
        }


        .sidebar-title h2 {
            color: #d94b91;

            font-size: 18px;

            margin-bottom: 5px;
        }


        .sidebar-title p {
            color: #9b8d96;

            font-size: 11px;
        }


        .menu {
            display: flex;

            flex-direction: column;

            gap: 7px;
        }


        .menu a {
            text-decoration: none;

            color: #756875;

            padding: 11px 12px;

            border-radius: 8px;

            font-size: 13px;

            transition: 0.2s;
        }


        .menu a:hover {
            background: #fff0f6;

            color: #d94b91;
        }


        .menu a.active {
            background: #d94b91;

            color: white;
        }


        /* =========================
           MAIN
        ========================= */

        .main {
            margin-left: 170px;

            min-height: 100vh;

            padding: 22px 28px;
        }


        /* =========================
           TOPBAR
        ========================= */

        .topbar {
            background: #ffffff;

            border: 1px solid #f0d7e2;

            border-radius: 12px;

            padding: 14px 18px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 25px;
        }


        .topbar-title {
            font-size: 16px;

            font-weight: bold;

            color: #5f5360;
        }


        .user-info {
            font-size: 13px;

            color: #8c7d86;
        }


        /* =========================
           PAGE HEADER
        ========================= */

        .page-header {
            margin-bottom: 22px;
        }


        .page-header h1 {
            font-size: 25px;

            color: #5f5360;

            margin-bottom: 7px;
        }


        .page-header p {
            color: #9b8d96;

            font-size: 13px;
        }


        /* =========================
           LAPORAN CARD
        ========================= */

        .report-card {
            background: #ffffff;

            border: 1px solid #f0d7e2;

            border-radius: 14px;

            padding: 20px;

            margin-bottom: 25px;

            box-shadow: 0 4px 15px rgba(217, 75, 145, 0.05);
        }


        .report-title {
            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 17px;
        }


        .report-title h2 {
            font-size: 17px;

            color: #d94b91;
        }


        .report-title span {
            font-size: 12px;

            color: #9b8d96;
        }


        /* =========================
           TABLE
        ========================= */

        .table-wrapper {
            overflow-x: auto;
        }


        table {
            width: 100%;

            border-collapse: collapse;

            min-width: 700px;
        }


        th {
            background: #d94b91;

            color: white;

            padding: 12px 10px;

            text-align: center;

            font-size: 13px;
        }


        td {
            padding: 11px 10px;

            border-bottom: 1px solid #f0d7e2;

            text-align: center;

            font-size: 13px;
        }


        tr:nth-child(even) {
            background: #fffafd;
        }


        tr:hover {
            background: #fff0f6;
        }


        .jumlah {
            font-weight: bold;

            color: #d94b91;
        }


        .stok {
            font-weight: bold;

            color: #d94b91;
        }


        /* =========================
           BUTTON
        ========================= */

        .button-area {
            display: flex;

            gap: 10px;

            margin-top: 5px;

            margin-bottom: 30px;
        }


        .btn {
            display: inline-block;

            padding: 10px 17px;

            border-radius: 8px;

            text-decoration: none;

            font-size: 13px;

            font-weight: bold;

            border: none;

            cursor: pointer;
        }


        .btn-kembali {
            background: #fff0f6;

            color: #d94b91;
        }


        .btn-kembali:hover {
            background: #fce0ec;
        }


        .btn-print {
            background: #d94b91;

            color: white;
        }


        .btn-print:hover {
            background: #c63f82;
        }


        /* =========================
           CETAK
        ========================= */

        @media print {

            body {
                background: white;
            }


            .sidebar,
            .topbar,
            .button-area {
                display: none;
            }


            .main {
                margin-left: 0;

                padding: 10px;
            }


            .report-card {
                box-shadow: none;

                border: 1px solid #ddd;

                page-break-inside: avoid;
            }


            .page-header {
                text-align: center;
            }


            .page-header h1 {
                color: #000;
            }


            .report-title h2 {
                color: #000;
            }


            th {
                background: #ddd !important;

                color: #000 !important;
            }

        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 700px) {

            .sidebar {
                width: 150px;
            }


            .main {
                margin-left: 150px;

                padding: 15px;
            }


            .topbar {
                flex-direction: column;

                align-items: flex-start;

                gap: 8px;
            }


            .report-title {
                flex-direction: column;

                align-items: flex-start;

                gap: 5px;
            }


            .button-area {
                flex-direction: column;
            }


            .btn {
                text-align: center;
            }

        }

    </style>

</head>


<body>


    <!-- =========================
         SIDEBAR
    ========================= -->

    <div class="sidebar">

        <div class="sidebar-title">

            <h2>Gudang Material</h2>

            <p>Sistem Informasi</p>

        </div>


        <div class="menu">

            <a href="dashboard.php">
                🏠 Dashboard
            </a>


            <a href="barang.php">
                📦 Data Barang
            </a>


            <a href="barang_masuk.php">
                📥 Barang Masuk
            </a>


            <a href="barang_keluar.php">
                📤 Barang Keluar
            </a>


            <a href="laporan.php" class="active">
                📊 Laporan
            </a>


            <a href="logout.php">
                🚪 Logout
            </a>

        </div>

    </div>



    <!-- =========================
         MAIN
    ========================= -->

    <div class="main">


        <!-- TOPBAR -->

        <div class="topbar">

            <div class="topbar-title">
                Laporan Gudang
            </div>


            <div class="user-info">
                📊 <strong>Laporan</strong>
            </div>

        </div>



        <!-- HEADER -->

        <div class="page-header">

            <h1>📊 Laporan Gudang Material</h1>

            <p>
                Menampilkan laporan transaksi dan stok barang gudang
            </p>

        </div>



        <!-- =========================
             BARANG MASUK
        ========================= -->

        <div class="report-card">


            <div class="report-title">

                <h2>
                    📥 Laporan Barang Masuk
                </h2>

                <span>
                    Data transaksi barang masuk
                </span>

            </div>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>Tanggal</th>

                            <th>Kode Barang</th>

                            <th>Nama Barang</th>

                            <th>Jumlah</th>

                            <th>Asal Barang</th>

                            <th>Keterangan</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($data = mysqli_fetch_assoc($masuk)) { ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($data['tanggal']); ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($data['kode_barang']); ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($data['nama_barang']); ?>
                                </td>


                                <td class="jumlah">
                                    <?= htmlspecialchars($data['jumlah']); ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($data['asal_barang']); ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($data['keterangan']); ?>
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>



        <!-- =========================
             BARANG KELUAR
        ========================= -->

        <div class="report-card">


            <div class="report-title">

                <h2>
                    📤 Laporan Barang Keluar
                </h2>

                <span>
                    Data transaksi barang keluar
                </span>

            </div>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>Tanggal</th>

                            <th>Kode Barang</th>

                            <th>Nama Barang</th>

                            <th>Jumlah</th>

                            <th>Tujuan</th>

                            <th>Keterangan</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($data = mysqli_fetch_assoc($keluar)) { ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($data['tanggal']); ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($data['kode_barang']); ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($data['nama_barang']); ?>
                                </td>


                                <td class="jumlah">
                                    <?= htmlspecialchars($data['jumlah']); ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($data['tujuan']); ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($data['keterangan']); ?>
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>



        <!-- =========================
             STOK BARANG
        ========================= -->

        <div class="report-card">


            <div class="report-title">

                <h2>
                    📦 Stok Barang
                </h2>

                <span>
                    Kondisi stok barang saat ini
                </span>

            </div>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>Kode Barang</th>

                            <th>Nama Barang</th>

                            <th>Satuan</th>

                            <th>Stok</th>

                            <th>Lokasi</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($data = mysqli_fetch_assoc($stok)) { ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($data['kode_barang']); ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($data['nama_barang']); ?>
                                </td>



                                <td>
                                    <?= htmlspecialchars($data['satuan']); ?>
                                </td>


                                <td class="stok">
                                    <?= htmlspecialchars($data['stok']); ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($data['lokasi']); ?>
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>



        <!-- =========================
             BUTTON
        ========================= -->

        <div class="button-area">

            <a
                href="dashboard.php"
                class="btn btn-kembali"
            >
                ← Kembali ke Dashboard
            </a>


            <button
                onclick="window.print()"
                class="btn btn-print"
            >
                🖨️ Cetak Laporan
            </button>

        </div>


    </div>


</body>

</html>