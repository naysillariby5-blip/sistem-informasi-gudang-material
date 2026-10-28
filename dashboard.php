<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

include "koneksi.php";

/* Statistik dashboard */
$query_barang = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM barang");
$data_barang = mysqli_fetch_assoc($query_barang);

$query_masuk = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM barang_masuk");
$data_masuk = mysqli_fetch_assoc($query_masuk);

$query_keluar = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM barang_keluar");
$data_keluar = mysqli_fetch_assoc($query_keluar);

/* 5 transaksi terbaru */
$query_transaksi = mysqli_query($koneksi, "
    SELECT *
    FROM (
        SELECT
            bm.tanggal,
            bm.id_barang,
            COALESCE(b.kode_barang, '-') AS kode_barang,
            COALESCE(b.nama_barang, 'Barang tidak ditemukan') AS nama_barang,
            'Masuk' AS jenis,
            bm.jumlah
        FROM barang_masuk bm
        LEFT JOIN barang b ON bm.id_barang = b.id_barang

        UNION ALL

        SELECT
            bk.tanggal,
            bk.id_barang,
            COALESCE(b.kode_barang, '-') AS kode_barang,
            COALESCE(b.nama_barang, 'Barang tidak ditemukan') AS nama_barang,
            'Keluar' AS jenis,
            bk.jumlah
        FROM barang_keluar bk
        LEFT JOIN barang b ON bk.id_barang = b.id_barang
    ) AS transaksi
    ORDER BY tanggal DESC
    LIMIT 5
");

if (!$query_transaksi) {
    die("Query transaksi error: " . mysqli_error($koneksi));
}

$transaksi_terbaru = $query_transaksi;
$username = htmlspecialchars($_SESSION['username'] ?? 'admin');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Gudang Material</title>

    <!-- Tetap memuat CSS utama project -->
    <link rel="stylesheet" href="css/style.css">

    <!-- CSS khusus dashboard agar tampilannya kembali seperti desain sebelumnya -->
    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        body.dashboard-page {
            background: #fff9fb;
            color: #5f5360;
            min-height: 100vh;
        }

        .layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* =========================
           SIDEBAR
           ========================= */
        .dashboard-page .sidebar {
            width: 170px;
            min-width: 170px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            padding: 16px 10px;
            background: #ffffff;
            border-right: 1px solid #f0d7e2;
            box-shadow: 2px 0 12px rgba(216, 91, 136, 0.05);
        }

        .dashboard-page .sidebar-header {
            padding: 2px 12px 15px;
            border-bottom: 1px solid #f0d7e2;
        }

        .dashboard-page .sidebar-header h2 {
            margin: 0;
            color: #d84f8d;
            font-size: 14px;
            line-height: 1.25;
        }

        .dashboard-page .sidebar-header p {
            margin: 5px 0 0;
            color: #a98b98;
            font-size: 9px;
        }

        .dashboard-page .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 3px;
            margin-top: 16px;
        }

        .dashboard-page .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 7px;
            min-height: 37px;
            padding: 7px 9px;
            margin: 0;
            border-radius: 9px;
            text-decoration: none;
            color: #62545c;
            font-size: 10px;
            font-weight: 500;
            line-height: 1.15;
            transition: 0.2s ease;
        }

        .dashboard-page .sidebar-menu a:hover {
            background: #fff0f5;
            color: #d84f8d;
        }

        .dashboard-page .sidebar-menu a.active {
            background: #d94b91;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(217, 75, 145, 0.18);
        }

        .dashboard-page .sidebar-menu a span {
            display: inline-block;
        }

        .dashboard-page .sidebar-bottom {
            margin-top: auto;
            padding-top: 10px;
            border-top: 1px solid #f0d7e2;
        }

        .dashboard-page .sidebar-bottom a {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 8px 9px;
            color: #c83f7d;
            font-size: 10px;
            text-decoration: none;
            border-radius: 9px;
        }

        .dashboard-page .sidebar-bottom a:hover {
            background: #fff0f7;
        }

        /* =========================
           MAIN
           ========================= */
        .dashboard-page .main-content {
            margin-left: 170px;
            width: calc(100% - 170px);
            min-width: 0;
            padding: 20px 24px;
            background: #fff9fb;
        }

        /* =========================
           TOPBAR
           ========================= */
        .dashboard-page .topbar {
            width: 100%;
            min-height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 12px 18px;
            margin-bottom: 15px;
            background: #ffffff;
            border: 1px solid #f1d5e2;
            border-radius: 12px;
        }

        .dashboard-page .topbar h1 {
            margin: 0;
            color: #422f38;
            font-size: 18px;
        }

        .dashboard-page .topbar p {
            margin: 4px 0 0;
            color: #a48c96;
            font-size: 9px;
        }

        .dashboard-page .user-info {
            flex-shrink: 0;
            padding: 7px 12px;
            border-radius: 8px;
            background: #fff0f5;
            color: #d84f8d;
            font-size: 10px;
        }

        /* =========================
           WELCOME
           ========================= */
        .dashboard-page .welcome-box {
            margin-bottom: 17px;
            padding: 18px 20px;
            border-radius: 13px;
            color: #ffffff;
            background: linear-gradient(135deg, #f39ab9, #f3b5cc);
            box-shadow: 0 5px 15px rgba(216, 91, 136, 0.10);
        }

        .dashboard-page .welcome-box h2 {
            margin: 0 0 5px;
            color: #ffffff;
            font-size: 15px;
        }

        .dashboard-page .welcome-box p {
            margin: 0;
            color: #ffffff;
            font-size: 9px;
        }

        /* =========================
           STATISTICS
           ========================= */
        .dashboard-page .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 13px;
            margin-bottom: 20px;
        }

        .dashboard-page .stat-card {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 67px;
            padding: 14px;
            background: #ffffff;
            border: 1px solid #f1d5e2;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(216, 91, 136, 0.06);
        }

        .dashboard-page .stat-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: #fff0f5;
            font-size: 17px;
        }

        .dashboard-page .stat-card p {
            margin: 0 0 4px;
            color: #a28b95;
            font-size: 9px;
        }

        .dashboard-page .stat-card h2 {
            margin: 0;
            color: #d84f8d;
            font-size: 18px;
        }

        /* =========================
           SECTION
           ========================= */
        .dashboard-page .section-title {
            margin: 0 0 10px;
        }

        .dashboard-page .section-title h2 {
            margin: 0 0 3px;
            color: #533c46;
            font-size: 15px;
        }

        .dashboard-page .section-title p {
            margin: 0;
            color: #a18b94;
            font-size: 9px;
        }

        /* =========================
           MENU PENGELOLAAN
           ========================= */
        .dashboard-page .menu-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
            margin-bottom: 20px;
        }

        .dashboard-page .menu-card {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 70px;
            padding: 12px 14px;
            background: #ffffff;
            border: 1px solid #f1d5e2;
            border-radius: 12px;
            text-decoration: none;
            box-shadow: 0 3px 10px rgba(216, 91, 136, 0.05);
            transition: 0.2s ease;
        }

        .dashboard-page .menu-card:hover {
            transform: translateY(-2px);
            border-color: #e9a7c5;
            box-shadow: 0 6px 14px rgba(216, 91, 136, 0.10);
        }

        .dashboard-page .menu-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: #fff0f5;
            font-size: 17px;
        }

        .dashboard-page .menu-card h3 {
            margin: 0 0 3px;
            color: #d84f8d;
            font-size: 11px;
        }

        .dashboard-page .menu-card p {
            margin: 0;
            color: #8f7b84;
            font-size: 8px;
            line-height: 1.35;
        }

        /* =========================
           TRANSAKSI TERBARU
           ========================= */
        .dashboard-page .recent-section {
            margin-top: 0;
        }

        .dashboard-page .recent-section h2 {
            margin: 0 0 3px;
            color: #533c46;
            font-size: 15px;
        }

        .dashboard-page .recent-section > p {
            margin: 0 0 10px;
            color: #a18b94;
            font-size: 9px;
        }

        .dashboard-page .table-container {
            width: 100%;
            overflow-x: auto;
            background: #ffffff;
            border: 1px solid #f1d5e2;
            border-radius: 0;
            box-shadow: none;
        }

        .dashboard-page .recent-table {
            width: 100%;
            min-width: 600px;
            border-collapse: collapse;
        }

        .dashboard-page .recent-table th {
            padding: 9px 10px;
            background: #f4d1e1;
            color: #7f4667;
            text-align: left;
            font-size: 8px;
            font-weight: 700;
            white-space: nowrap;
        }

        .dashboard-page .recent-table td {
            padding: 9px 10px;
            border-top: 1px solid #f4e3ea;
            color: #62555d;
            font-size: 8px;
            white-space: nowrap;
        }

        .dashboard-page .recent-table tbody tr:hover {
            background: #fff8fb;
        }

        .dashboard-page .badge {
            display: inline-block;
            padding: 4px 7px;
            border-radius: 6px;
            font-size: 7px;
            font-weight: 700;
        }

        .dashboard-page .badge.masuk {
            background: #ffe9f2;
            color: #d84f8d;
        }

        .dashboard-page .badge.keluar {
            background: #f9e2ec;
            color: #b84c76;
        }

        /* =========================
           RESPONSIVE
           ========================= */
        @media (max-width: 850px) {
            .dashboard-page .sidebar {
                width: 145px;
                min-width: 145px;
            }

            .dashboard-page .main-content {
                margin-left: 145px;
                width: calc(100% - 145px);
                padding: 16px;
            }

            .dashboard-page .stats {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 650px) {
            .dashboard-page .layout {
                display: block;
            }

            .dashboard-page .sidebar {
                position: relative;
                width: 100%;
                min-width: 0;
                height: auto;
                min-height: auto;
            }

            .dashboard-page .sidebar-menu {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .dashboard-page .sidebar-bottom {
                position: static;
                margin-top: 10px;
            }

            .dashboard-page .main-content {
                margin-left: 0;
                width: 100%;
            }

            .dashboard-page .menu-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="dashboard-page">

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="sidebar-header">
            <h2>Gudang Material</h2>
            <p>Sistem Informasi</p>
        </div>

        <nav class="sidebar-menu">
            <a href="dashboard.php" class="active">
                🏠 <span>Dashboard</span>
            </a>

            <a href="barang.php">
                📦 <span>Data Barang</span>
            </a>

            <a href="barang_masuk.php">
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

    <!-- MAIN CONTENT -->
    <main class="main-content">

        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <h1>Dashboard</h1>
                <p>Sistem Informasi Gudang Material</p>
            </div>

            <div class="user-info">
                👤 <strong><?php echo $username; ?></strong>
            </div>
        </div>

        <!-- WELCOME -->
        <div class="welcome-box">
            <h2>Selamat Datang 👋</h2>
            <p>
                Selamat datang, <strong><?php echo $username; ?></strong>.
                Silakan pilih menu untuk mengelola data gudang.
            </p>
        </div>

        <!-- STATISTICS -->
        <div class="stats">

            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div>
                    <p>Total Jenis Barang</p>
                    <h2><?php echo $data_barang['total']; ?></h2>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📥</div>
                <div>
                    <p>Total Transaksi Masuk</p>
                    <h2><?php echo $data_masuk['total']; ?></h2>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📤</div>
                <div>
                    <p>Total Transaksi Keluar</p>
                    <h2><?php echo $data_keluar['total']; ?></h2>
                </div>
            </div>

        </div>

        <!-- MENU PENGELOLAAN -->
        <div class="section-title">
            <h2>Menu Pengelolaan Gudang</h2>
            <p>Kelola data material dan transaksi gudang.</p>
        </div>

        <div class="menu-grid">

            <a href="barang.php" class="menu-card">
                <div class="menu-icon">📦</div>
                <div>
                    <h3>Data Barang</h3>
                    <p>Kelola data material gudang.</p>
                </div>
            </a>

            <a href="barang_masuk.php" class="menu-card">
                <div class="menu-icon">📥</div>
                <div>
                    <h3>Barang Masuk</h3>
                    <p>Kelola transaksi material masuk.</p>
                </div>
            </a>

            <a href="barang_keluar.php" class="menu-card">
                <div class="menu-icon">📤</div>
                <div>
                    <h3>Barang Keluar</h3>
                    <p>Kelola transaksi material keluar.</p>
                </div>
            </a>

            <a href="laporan.php" class="menu-card">
                <div class="menu-icon">📊</div>
                <div>
                    <h3>Laporan</h3>
                    <p>Lihat laporan barang masuk, barang keluar, dan stok.</p>
                </div>
            </a>

        </div>

        <!-- TRANSAKSI TERBARU -->
        <div class="recent-section">
            <h2>Transaksi Terbaru</h2>
            <p>Menampilkan 5 transaksi gudang terbaru.</p>

            <div class="table-container">
                <table class="recent-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($transaksi = mysqli_fetch_assoc($transaksi_terbaru)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($transaksi['tanggal']); ?></td>
                                <td><?php echo htmlspecialchars($transaksi['kode_barang']); ?></td>
                                <td><?php echo htmlspecialchars($transaksi['nama_barang']); ?></td>
                                <td>
                                    <?php if ($transaksi['jenis'] == 'Masuk') { ?>
                                        <span class="badge masuk">Masuk</span>
                                    <?php } else { ?>
                                        <span class="badge keluar">Keluar</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo htmlspecialchars($transaksi['jumlah']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

</body>
</html>
