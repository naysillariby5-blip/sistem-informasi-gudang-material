<?php
include 'cek_login.php';
include 'koneksi.php';

/* =========================
   PROSES TAMBAH BARANG
========================= */

if (isset($_POST['tambah'])) {

    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $satuan = $_POST['satuan'];
    $stok = $_POST['stok'];
    $lokasi = $_POST['lokasi'];

    $sql = "INSERT INTO barang
            (kode_barang, nama_barang, satuan, stok, lokasi)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($koneksi, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssis",
        $kode_barang,
        $nama_barang,
        $satuan,
        $stok,
        $lokasi
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: barang.php");
    exit;
}


/* =========================
   PENCARIAN BARANG
========================= */

$cari = "";

if (isset($_GET['cari'])) {

    $cari = $_GET['cari'];

    $query = mysqli_query($koneksi, "
        SELECT * FROM barang
        WHERE kode_barang LIKE '%$cari%'
        OR nama_barang LIKE '%$cari%'
        OR satuan LIKE '%$cari%'
        OR lokasi LIKE '%$cari%'
    ");

} else {

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM barang"
    );
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Barang - Gudang Material</title>

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


        /* =========================
           TAMBAH BARANG
        ========================= */

        .form-card {
            background: #ffffff;
            border: 1px solid #f0d7e2;
            border-radius: 14px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(217, 75, 145, 0.05);
        }

        .form-title {
            font-size: 18px;
            color: #d94b91;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
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

        input {
            width: 100%;
            padding: 11px 13px;
            border: 1px solid #e7cbd8;
            border-radius: 8px;
            background: #fffafb;
            color: #5f5360;
            font-size: 13px;
            outline: none;
        }

        input:focus {
            border-color: #d94b91;
            background: #ffffff;
        }

        .btn-tambah {
            width: 100%;
            border: none;
            background: #d94b91;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-tambah:hover {
            background: #c63f82;
        }


        /* =========================
           DATA BARANG
        ========================= */

        .data-card {
            background: #ffffff;
            border: 1px solid #f0d7e2;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(217, 75, 145, 0.05);
        }

        .data-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .data-title {
            font-size: 18px;
            font-weight: bold;
            color: #5f5360;
        }


        /* =========================
           SEARCH
        ========================= */

        .search-form {
            display: flex;
            gap: 8px;
            width: 400px;
        }

        .search-form input {
            flex: 1;
        }

        .btn-cari {
            border: none;
            background: #d94b91;
            color: white;
            padding: 0 18px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-cari:hover {
            background: #c63f82;
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
            min-width: 750px;
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

        .stok {
            font-weight: bold;
            color: #d94b91;
        }


        /* =========================
           TOMBOL AKSI
        ========================= */

        .btn {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
            margin: 2px;
        }

        .btn-edit {
            background: #f7c1d7;
            color: #7a294b;
        }

        .btn-edit:hover {
            background: #efa8c5;
        }

        .btn-hapus {
            background: #f8d7df;
            color: #a61e4d;
        }

        .btn-hapus:hover {
            background: #f3bdcc;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {

            .data-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .search-form {
                width: 100%;
            }
        }


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

            <a href="barang.php" class="active">
                📦 Data Barang
            </a>

            <a href="barang_masuk.php">
                📥 Barang Masuk
            </a>

            <a href="barang_keluar.php">
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
                Data Barang
            </div>

            <div class="user-info">
                📦 <strong>Data Barang</strong>
            </div>

        </div>


        <!-- PAGE HEADER -->

        <div class="page-header">

            <h1>📦 Data Barang</h1>

            <p>
                Mengelola data barang yang tersedia di gudang
            </p>

        </div>


        <!-- FORM TAMBAH BARANG -->

        <div class="form-card">

            <div class="form-title">
                ➕ Tambah Data Barang
            </div>


            <form method="POST" action="">


                <div class="form-row">


                    <div class="form-group">

                        <label>Kode Barang</label>

                        <input
                            type="text"
                            name="kode_barang"
                            placeholder="Masukkan kode barang"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Nama Barang</label>

                        <input
                            type="text"
                            name="nama_barang"
                            placeholder="Masukkan nama barang"
                            required
                        >

                    </div>


                </div>


                <div class="form-row">


                    <div class="form-group">

                        <label>Satuan</label>

                        <input
                            type="text"
                            name="satuan"
                            placeholder="Contoh: pcs, unit, kg"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Stok</label>

                        <input
                            type="number"
                            name="stok"
                            min="0"
                            placeholder="Masukkan jumlah stok"
                            required
                        >

                    </div>


                </div>


                <div class="form-group">

                    <label>Lokasi</label>

                    <input
                        type="text"
                        name="lokasi"
                        placeholder="Masukkan lokasi barang"
                    >

                </div>


                <button
                    type="submit"
                    name="tambah"
                    class="btn-tambah"
                >
                    + Tambah Barang
                </button>


            </form>

        </div>


        <!-- DATA BARANG -->

        <div class="data-card">


            <div class="data-header">


                <div class="data-title">
                    📋 Daftar Barang
                </div>


                <form
                    method="GET"
                    action=""
                    class="search-form"
                >

                    <input
                        type="text"
                        name="cari"
                        placeholder="🔍 Cari kode, nama, satuan, atau lokasi..."
                        value="<?= htmlspecialchars($cari); ?>"
                    >


                    <button
                        type="submit"
                        class="btn-cari"
                    >
                        Cari
                    </button>

                </form>


            </div>


            <!-- TABLE -->

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Kode Barang</th>

                            <th>Nama Barang</th>

                            <th>Satuan</th>

                            <th>Stok</th>

                            <th>Lokasi</th>

                            <th>Aksi</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($data = mysqli_fetch_assoc($query)) { ?>

                            <tr>

                                <td>
                                    <?= $data['id_barang']; ?>
                                </td>

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
                                    <?= $data['stok']; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['lokasi']); ?>
                                </td>

                                <td>

                                    <a
                                        href="edit_barang.php?id=<?= $data['id_barang']; ?>"
                                        class="btn btn-edit"
                                    >
                                        ✏️ Edit
                                    </a>


                                    <a
                                        href="hapus_barang.php?id=<?= $data['id_barang']; ?>"
                                        class="btn btn-hapus"
                                        onclick="return confirm('Yakin ingin menghapus barang ini?')"
                                    >
                                        🗑️ Hapus
                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>


    </div>


</body>

</html>