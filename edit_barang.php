<?php
include 'cek_login.php';
include 'koneksi.php';

$id = $_GET['id'] ?? '';

$query = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>
            alert('Data barang tidak ditemukan!');
            window.location='barang.php';
          </script>";
    exit;
}

if (isset($_POST['update'])) {

    $kode_barang = mysqli_real_escape_string($koneksi, $_POST['kode_barang']);
    $nama_barang = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $satuan      = mysqli_real_escape_string($koneksi, $_POST['satuan']);
    $stok        = mysqli_real_escape_string($koneksi, $_POST['stok']);
    $lokasi      = mysqli_real_escape_string($koneksi, $_POST['lokasi']);

    $query_update = mysqli_query($koneksi, "
        UPDATE barang SET
            kode_barang='$kode_barang',
            nama_barang='$nama_barang',
            satuan='$satuan',
            stok='$stok',
            lokasi='$lokasi'
        WHERE id_barang='$id'
    ");

    if ($query_update) {
        echo "<script>
                alert('Data barang berhasil diperbarui!');
                window.location='barang.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Data barang gagal diperbarui!');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Gudang Material</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #fff9fb;
            color: #444;
        }

        .sidebar {
            width: 170px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #ffffff;
            border-right: 1px solid #f0d7e2;
            padding: 22px 12px;
        }

        .sidebar h2 {
            color: #d94b91;
            font-size: 18px;
            text-align: center;
            margin-bottom: 4px;
        }

        .sidebar p {
            text-align: center;
            font-size: 11px;
            color: #999;
            margin-bottom: 25px;
        }

        .menu a {
            display: block;
            text-decoration: none;
            color: #666;
            padding: 11px 12px;
            margin-bottom: 6px;
            border-radius: 9px;
            font-size: 13px;
        }

        .menu a:hover {
            background: #fff0f6;
            color: #d94b91;
        }

        .menu a.active {
            background: #d94b91;
            color: white;
        }

        .main {
            margin-left: 170px;
            padding: 25px;
        }

        .topbar {
            background: white;
            padding: 17px 20px;
            border-radius: 12px;
            border: 1px solid #f0d7e2;
            margin-bottom: 20px;
        }

        .topbar h1 {
            font-size: 21px;
            color: #444;
            margin-bottom: 4px;
        }

        .topbar p {
            font-size: 13px;
            color: #999;
        }

        .card {
            max-width: 750px;
            background: white;
            border: 1px solid #f0d7e2;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(217, 75, 145, 0.06);
        }

        .card h2 {
            color: #d94b91;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 17px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            color: #555;
            margin-bottom: 7px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 11px 13px;
            border: 1px solid #e8bfd2;
            border-radius: 8px;
            outline: none;
            font-size: 13px;
            background: #fffafb;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #d94b91;
            box-shadow: 0 0 0 3px rgba(217, 75, 145, 0.08);
        }

        .button-area {
            display: flex;
            gap: 10px;
            margin-top: 22px;
        }

        .btn {
            border: none;
            padding: 11px 18px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 13px;
            display: inline-block;
        }

        .btn-simpan {
            background: #d94b91;
            color: white;
        }

        .btn-simpan:hover {
            background: #c63f82;
        }

        .btn-kembali {
            background: #f5e7ed;
            color: #777;
        }

        .btn-kembali:hover {
            background: #ead7e0;
        }

        @media (max-width: 700px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main {
                margin-left: 0;
            }

            .button-area {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<div class="sidebar">

    <h2>Gudang Material</h2>
    <p>Sistem Informasi</p>

    <div class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="barang.php" class="active">Data Barang</a>
        <a href="barang_masuk.php">Barang Masuk</a>
        <a href="barang_keluar.php">Barang Keluar</a>
        <a href="laporan.php">Laporan</a>
        <a href="logout.php">Logout</a>
    </div>

</div>

<div class="main">

    <div class="topbar">
        <h1>Edit Data Barang</h1>
        <p>Perbarui informasi barang yang tersimpan di gudang.</p>
    </div>

    <div class="card">

        <h2>Form Edit Barang</h2>

        <form method="POST">

            <div class="form-group">
                <label>Kode Barang</label>
                <input
                    type="text"
                    name="kode_barang"
                    value="<?= htmlspecialchars($data['kode_barang']); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Nama Barang</label>
                <input
                    type="text"
                    name="nama_barang"
                    value="<?= htmlspecialchars($data['nama_barang']); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Satuan</label>
                <input
                    type="text"
                    name="satuan"
                    value="<?= htmlspecialchars($data['satuan']); ?>"
                    placeholder="Contoh: pcs, unit, kg"
                    required
                >
            </div>

            <div class="form-group">
                <label>Stok</label>
                <input
                    type="number"
                    name="stok"
                    value="<?= htmlspecialchars($data['stok']); ?>"
                    min="0"
                    required
                >
            </div>

            <div class="form-group">
                <label>Lokasi</label>
                <input
                    type="text"
                    name="lokasi"
                    value="<?= htmlspecialchars($data['lokasi']); ?>"
                    placeholder="Contoh: Gudang A"
                    required
                >
            </div>

            <div class="button-area">
                <button type="submit" name="update" class="btn btn-simpan">
                    Simpan Perubahan
                </button>

                <a href="barang.php" class="btn btn-kembali">
                    Kembali
                </a>
            </div>

        </form>

    </div>

</div>

</body>
</html>