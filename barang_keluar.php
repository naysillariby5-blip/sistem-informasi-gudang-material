<?php
include 'cek_login.php';
include 'koneksi.php';

// Proses simpan barang keluar
if (isset($_POST['simpan'])) {

    $tanggal = $_POST['tanggal'];
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    $tujuan = $_POST['tujuan'];
    $keterangan = $_POST['keterangan'];

    // Cek stok barang
    $cek_stok = mysqli_query(
        $koneksi,
        "SELECT stok FROM barang WHERE id_barang = '$id_barang'"
    );

    $data_stok = mysqli_fetch_assoc($cek_stok);

    if (!$data_stok) {
        echo "Barang tidak ditemukan";
        exit;
    }

    $stok = $data_stok['stok'];

    // Pastikan jumlah keluar tidak melebihi stok
    if ($jumlah > $stok) {
        echo "Stok tidak cukup. Stok tersedia: " . $stok;
        exit;
    }

    // Simpan transaksi barang keluar
    $simpan = mysqli_query($koneksi, "INSERT INTO barang_keluar
        (tanggal, id_barang, jumlah, tujuan, keterangan)
        VALUES
        ('$tanggal', '$id_barang', '$jumlah', '$tujuan', '$keterangan')
    ");

    if ($simpan) {

        // Kurangi stok barang
        $update_stok = mysqli_query($koneksi, "UPDATE barang
            SET stok = stok - $jumlah
            WHERE id_barang = '$id_barang'
        ");

        if ($update_stok) {
            header("Location: barang_keluar.php");
            exit;
        } else {
            echo "Gagal memperbarui stok: " . mysqli_error($koneksi);
        }

    } else {
        echo "Gagal menyimpan barang keluar: " . mysqli_error($koneksi);
    }
}

// Ambil daftar barang
$barang = mysqli_query(
    $koneksi,
    "SELECT id_barang, kode_barang, nama_barang, stok FROM barang"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Keluar</title>

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

        /* SIDEBAR */
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

        /* MAIN */
        .main {
            margin-left: 170px;
            min-height: 100vh;
            padding: 22px 28px;
        }

        /* TOPBAR */
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

        /* HEADER */
        .page-header {
            margin-bottom: 20px;
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

        /* FORM CARD */
        .form-card {
            background: #ffffff;
            border: 1px solid #f0d7e2;
            border-radius: 14px;
            padding: 25px;
            max-width: 850px;
            box-shadow: 0 4px 15px rgba(217, 75, 145, 0.05);
        }

        .form-title {
            font-size: 18px;
            color: #d94b91;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            color: #665963;
            margin-bottom: 7px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 11px 13px;
            border: 1px solid #e7cbd8;
            border-radius: 8px;
            background: #fffafb;
            color: #5f5360;
            font-size: 13px;
            outline: none;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #d94b91;
            background: #ffffff;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* DUA KOLOM */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        /* BUTTON */
        .btn-simpan {
            width: 100%;
            border: none;
            background: #d94b91;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 5px;
            transition: 0.2s;
        }

        .btn-simpan:hover {
            background: #c63f82;
        }

        .kembali {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #d94b91;
            background: #fff0f6;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 13px;
        }

        .kembali:hover {
            background: #fce0ec;
        }

        /* RESPONSIVE */
        @media (max-width: 700px) {

            .sidebar {
                width: 150px;
            }

            .main {
                margin-left: 150px;
                padding: 15px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
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

            <a href="barang_keluar.php" class="active">
                📤 Barang Keluar
            </a>

            <a href="laporan.php">
                📊 Laporan
            </a>

            <a href="logout.php">
                🚪 Logout
            </a>

        </div>

    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">

            <div class="topbar-title">
                Barang Keluar
            </div>

            <div class="user-info">
                📤 <strong>Barang Keluar</strong>
            </div>

        </div>

        <!-- HEADER -->
        <div class="page-header">

            <h1>📤 Pencatatan Barang Keluar</h1>

            <p>
                Form pencatatan barang yang keluar dari gudang
            </p>

        </div>

        <!-- FORM -->
        <div class="form-card">

            <div class="form-title">
                Form Barang Keluar
            </div>

            <form method="POST">

                <div class="form-group">
                    <label>Tanggal</label>

                    <input
                        type="date"
                        name="tanggal"
                        required
                    >
                </div>


                <div class="form-group">
                    <label>Barang</label>

                    <select name="id_barang" required>

                        <option value="">
                            -- Pilih Barang --
                        </option>

                        <?php while ($b = mysqli_fetch_assoc($barang)) { ?>

                            <option value="<?= $b['id_barang']; ?>">

                                <?= $b['kode_barang']; ?>
                                -
                                <?= $b['nama_barang']; ?>
                                (Stok: <?= $b['stok']; ?>)

                            </option>

                        <?php } ?>

                    </select>
                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label>Jumlah</label>

                        <input
                            type="number"
                            name="jumlah"
                            min="1"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Tujuan</label>

                        <input
                            type="text"
                            name="tujuan"
                            placeholder="Masukkan tujuan barang"
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label>Keterangan</label>

                    <textarea
                        name="keterangan"
                        placeholder="Masukkan keterangan jika diperlukan"
                    ></textarea>

                </div>


                <button
                    type="submit"
                    name="simpan"
                    class="btn-simpan"
                >
                    Simpan Barang Keluar
                </button>

            </form>


            <a href="dashboard.php" class="kembali">
                ← Kembali ke Dashboard
            </a>

        </div>

    </div>

</body>
</html>