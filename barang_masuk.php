<?php
include 'cek_login.php';
include 'koneksi.php';

/* =========================
   PROSES SIMPAN BARANG MASUK
   ========================= */
if (isset($_POST['simpan'])) {

    $tanggal = $_POST['tanggal'];
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    $asal_barang = $_POST['asal_barang'];
    $keterangan = $_POST['keterangan'];

    /* Simpan transaksi barang masuk */
    $simpan = mysqli_query($koneksi, "INSERT INTO barang_masuk
        (tanggal, id_barang, jumlah, asal_barang, keterangan)
        VALUES
        ('$tanggal', '$id_barang', '$jumlah', '$asal_barang', '$keterangan')
    ");

    if ($simpan) {

        /* Tambahkan jumlah ke stok barang */
        $update_stok = mysqli_query($koneksi, "UPDATE barang
            SET stok = stok + $jumlah
            WHERE id_barang = '$id_barang'
        ");

        if ($update_stok) {
            header("Location: barang_masuk.php");
            exit;
        } else {
            echo "Gagal memperbarui stok: " . mysqli_error($koneksi);
        }

    } else {
        echo "Gagal menyimpan barang masuk: " . mysqli_error($koneksi);
    }
}

/* Ambil daftar barang untuk pilihan */
$barang = mysqli_query(
    $koneksi,
    "SELECT id_barang, kode_barang, nama_barang FROM barang"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Barang Masuk - Gudang Material</title>

    <style>

        /* =========================
           DASAR
           ========================= */

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #fff9fb;
            color: #5f5360;
        }

        /* =========================
           LAYOUT
           ========================= */

        .layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* =========================
           SIDEBAR
           ========================= */

        .sidebar {
            width: 170px;
            min-width: 170px;
            height: 100vh;

            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;

            display: flex;
            flex-direction: column;

            padding: 16px 10px;

            background: #ffffff;
            border-right: 1px solid #f0d7e2;

            box-shadow: 2px 0 12px rgba(216, 91, 136, 0.05);

            z-index: 100;
        }

        /* Judul sidebar */

        .sidebar-header {
            padding: 2px 12px 15px;
            border-bottom: 1px solid #f0d7e2;
        }

        .sidebar-header h2 {
            margin: 0;
            color: #d84f8d;
            font-size: 14px;
            line-height: 1.25;
        }

        .sidebar-header p {
            margin: 5px 0 0;
            color: #a98b98;
            font-size: 9px;
        }

        /* Menu */

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 3px;
            margin-top: 16px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;

            gap: 7px;

            min-height: 37px;

            padding: 7px 9px;

            border-radius: 9px;

            text-decoration: none;

            color: #62545c;

            font-size: 10px;
            font-weight: 500;

            transition: 0.2s ease;
        }

        .sidebar-menu a:hover {
            background: #fff0f5;
            color: #d84f8d;
        }

        /* Menu aktif Barang Masuk */

        .sidebar-menu a.active {
            background: #d94b91;
            color: #ffffff;

            font-weight: 700;

            box-shadow: 0 4px 10px rgba(217, 75, 145, 0.18);
        }

        /* Logout */

        .sidebar-bottom {
            margin-top: auto;

            padding-top: 10px;

            border-top: 1px solid #f0d7e2;
        }

        .sidebar-bottom a {
            display: flex;
            align-items: center;

            gap: 7px;

            padding: 8px 9px;

            color: #c83f7d;

            font-size: 10px;

            text-decoration: none;

            border-radius: 9px;
        }

        .sidebar-bottom a:hover {
            background: #fff0f7;
        }

        /* =========================
           MAIN CONTENT
           ========================= */

        .main-content {
            margin-left: 170px;

            width: calc(100% - 170px);

            min-width: 0;

            padding: 20px 24px;

            background: #fff9fb;
        }

        /* =========================
           TOPBAR
           ========================= */

        .topbar {
            width: 100%;

            min-height: 64px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;

            padding: 12px 18px;

            margin-bottom: 18px;

            background: #ffffff;

            border: 1px solid #f1d5e2;

            border-radius: 12px;
        }

        .topbar h1 {
            margin: 0;

            color: #422f38;

            font-size: 18px;
        }

        .topbar p {
            margin: 4px 0 0;

            color: #a48c96;

            font-size: 9px;
        }

        .user-info {
            padding: 7px 12px;

            border-radius: 8px;

            background: #fff0f5;

            color: #d84f8d;

            font-size: 10px;
        }

        /* =========================
           JUDUL HALAMAN
           ========================= */

        .page-header {
            margin-bottom: 15px;
        }

        .page-header h2 {
            margin: 0 0 5px;

            color: #533c46;

            font-size: 18px;
        }

        .page-header p {
            margin: 0;

            color: #a18b94;

            font-size: 9px;
        }

        /* =========================
           FORM CARD
           ========================= */

        .form-card {
            width: 100%;

            background: #ffffff;

            border: 1px solid #f1d5e2;

            border-radius: 14px;

            padding: 22px;

            box-shadow: 0 4px 15px rgba(216, 91, 136, 0.07);
        }

        /* =========================
           FORM
           ========================= */

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;

            margin-bottom: 6px;

            color: #7d4568;

            font-size: 10px;

            font-weight: 700;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {

            width: 100%;

            padding: 10px 12px;

            border: 1px solid #e8c5d8;

            border-radius: 9px;

            background: #fffafd;

            color: #5f5360;

            font-family: Arial, sans-serif;

            font-size: 10px;

            outline: none;

            transition: 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {

            border-color: #d94b91;

            background: #ffffff;

            box-shadow: 0 0 0 3px rgba(217, 75, 145, 0.08);
        }

        .form-group textarea {

            min-height: 85px;

            resize: vertical;
        }

        /* =========================
           FORM GRID
           ========================= */

        .form-row {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 15px;
        }

        /* =========================
           BUTTON
           ========================= */

        .btn-simpan {

            width: 100%;

            padding: 11px 15px;

            border: none;

            border-radius: 9px;

            background: #d94b91;

            color: #ffffff;

            font-size: 10px;

            font-weight: 700;

            cursor: pointer;

            transition: 0.2s;
        }

        .btn-simpan:hover {

            background: #c83f7d;

            transform: translateY(-1px);

            box-shadow: 0 5px 12px rgba(217, 75, 145, 0.18);
        }

        /* =========================
           TOMBOL KEMBALI
           ========================= */

        .kembali {

            display: inline-block;

            margin-top: 13px;

            padding: 8px 12px;

            border-radius: 8px;

            background: #fff0f5;

            color: #c83f7d;

            font-size: 9px;

            font-weight: 600;

            text-decoration: none;

            transition: 0.2s;
        }

        .kembali:hover {

            background: #fce1ed;

            color: #a83268;
        }

        /* =========================
           RESPONSIVE
           ========================= */

        @media (max-width: 850px) {

            .sidebar {

                width: 145px;

                min-width: 145px;
            }

            .main-content {

                margin-left: 145px;

                width: calc(100% - 145px);

                padding: 16px;
            }

        }

        @media (max-width: 650px) {

            .layout {

                display: block;
            }

            .sidebar {

                position: relative;

                width: 100%;

                min-width: 0;

                height: auto;
            }

            .sidebar-menu {

                flex-direction: row;

                flex-wrap: wrap;
            }

            .sidebar-bottom {

                margin-top: 10px;
            }

            .main-content {

                margin-left: 0;

                width: 100%;
            }

            .form-row {

                grid-template-columns: 1fr;
            }

            .topbar {

                display: block;
            }

            .user-info {

                display: inline-block;

                margin-top: 10px;
            }
        }

    </style>

</head>

<body>

<div class="layout">

    <!-- =========================
         SIDEBAR
         ========================= -->

    <aside class="sidebar">

        <div class="sidebar-header">

            <h2>Gudang Material</h2>

            <p>Sistem Informasi</p>

        </div>

        <nav class="sidebar-menu">

            <a href="dashboard.php">
                🏠 <span>Dashboard</span>
            </a>

            <a href="barang.php">
                📦 <span>Data Barang</span>
            </a>

            <a href="barang_masuk.php" class="active">
                📥 <span>Barang Masuk</span>
            </a>

            <a href="barang_keluar.php">
                📤 <span>Barang Keluar</span>
            </a>

            <a href="laporan.php">
                📊 <span>Laporan</span>
            </a>

        </nav>

        <div class="sidebar-bottom">

            <a href="logout.php">
                🚪 <span>Logout</span>
            </a>

        </div>

    </aside>


    <!-- =========================
         MAIN CONTENT
         ========================= -->

    <main class="main-content">

        <!-- TOPBAR -->

        <div class="topbar">

            <div>

                <h1>Barang Masuk</h1>

                <p>Sistem Informasi Gudang Material</p>

            </div>

            <div class="user-info">

                📥 <strong>Barang Masuk</strong>

            </div>

        </div>


        <!-- JUDUL -->

        <div class="page-header">

            <h2>📥 Pencatatan Barang Masuk</h2>

            <p>
                Masukkan data barang yang diterima dan masuk ke gudang.
            </p>

        </div>


        <!-- FORM -->

        <div class="form-card">

            <form method="POST">

                <!-- TANGGAL -->

                <div class="form-group">

                    <label>Tanggal</label>

                    <input
                        type="date"
                        name="tanggal"
                        required
                    >

                </div>


                <!-- BARANG -->

                <div class="form-group">

                    <label>Barang</label>

                    <select
                        name="id_barang"
                        required
                    >

                        <option value="">
                            -- Pilih Barang --
                        </option>

                        <?php while ($b = mysqli_fetch_assoc($barang)) { ?>

                            <option value="<?= $b['id_barang']; ?>">

                                <?= $b['kode_barang']; ?>
                                -
                                <?= $b['nama_barang']; ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <!-- JUMLAH + ASAL BARANG -->

                <div class="form-row">

                    <div class="form-group">

                        <label>Jumlah</label>

                        <input
                            type="number"
                            name="jumlah"
                            min="1"
                            placeholder="Masukkan jumlah"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Asal Barang</label>

                        <input
                            type="text"
                            name="asal_barang"
                            placeholder="Masukkan asal barang"
                        >

                    </div>

                </div>


                <!-- KETERANGAN -->

                <div class="form-group">

                    <label>Keterangan</label>

                    <textarea
                        name="keterangan"
                        placeholder="Masukkan keterangan jika diperlukan"
                    ></textarea>

                </div>


                <!-- SIMPAN -->

                <button
                    type="submit"
                    name="simpan"
                    class="btn-simpan"
                >

                    💾 Simpan Barang Masuk

                </button>

            </form>


            <!-- KEMBALI -->

            <a
                href="dashboard.php"
                class="kembali"
            >

                ← Kembali ke Dashboard

            </a>

        </div>

    </main>

</div>

</body>

</html>