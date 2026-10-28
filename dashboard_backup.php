<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

include "koneksi.php";

// Mengambil jumlah data barang
$query_barang = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM barang");
$data_barang = mysqli_fetch_assoc($query_barang);

// Mengambil jumlah transaksi barang masuk
$query_masuk = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM barang_masuk");
$data_masuk = mysqli_fetch_assoc($query_masuk);

// Mengambil jumlah transaksi barang keluar
$query_keluar = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM barang_keluar");
$data_keluar = mysqli_fetch_assoc($query_keluar);

//Mengambil 5 transaksi terbaru
$query_transaksi = mysqli_query($koneksi,"
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
        LEFT JOIN barang b
        ON  bm.id_barang = b.id_barang 

        UNION ALL
         
        SELECT
            bk.tanggal,
            bk.id_barang,
            COALESCE(b.kode_barang, '-') AS kode_barang,
            COALESCE(b.nama_barang, 'Barang tidak ditemukan') AS nama_barang,
            'Keluar' AS jenis,
            bk.jumlah
            FROM barang_keluar bk
            LEFT JOIN barang b
            ON bk.id_barang = b.id_barang 

            ) AS transaksi

            ORDER BY tanggal DESC
            LIMIT 5

            ");

            $transaksi_terbaru = $query_transaksi;

            if (!$query_transaksi) {
                die("Query transaksi error: " . mysqli_error($koneksi));
            }
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Sistem Gudang Material</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f8;
        }

        .navbar {
            background: #1f4e79;
            color: white;
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            font-size: 20px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            background: #dc3545;
            padding: 9px 15px;
            border-radius: 6px;
        }

        .container {
            padding: 30px;
        }

        .welcome {
            margin-bottom: 25px;
        }

        .welcome h1 {
            color: #333;
            margin-bottom: 8px;
        }

        .welcome p {
            color: #777;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .card h3 {
            color: #777;
            margin-bottom: 10px;
        }

        .card .number {
            font-size: 32px;
            font-weight: bold;
            color: #1f4e79;
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .menu a {
            display: block;
            background: white;
            padding: 20px;
            text-decoration: none;
            color: #333;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: 0.2s;
        }

        .menu a:hover {
            background: #1f4e79;
            color: white;
        }

        .menu h3 {
            margin-bottom: 8px;
        }

        @media (max-width: 700px) {
            .cards,
            .menu {
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
            
        </div>

        <nav class="sidebar-menu">

            <a href="dashboard.php" class="active">
                🏠 <span>Dashboard</span>
            </a>

            <a href="barang.php" class="menu-card">
                📦 <span>Data Barang</span>
            </a>

            <a href="barang_masuk.php" class="menu-card">
                📥 <span>Barang Masuk</span>
            </a>

            <a href="barang_keluar.php" class="menu-card">
                📤 <span>Barang Keluar</span>
            </a>

            <a href="laporan.php" class="menu-card">
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
                👤
                <strong>
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </strong>
            </div>

        </div>


        <!-- WELCOME -->
        <div class="welcome-box">

            <h2>Selamat Datang Naysilla 👋</h2>

            <p>
                Selamat datang Naysilla,
                <strong>
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </strong>.
                Silakan pilih menu untuk mengelola data gudang.
            </p>

        </div>


        <!-- STATISTICS -->
        <div class="stats">

            <div class="stat-card">

                <div class="stat-icon">
                    📦
                </div>

                <div>
                    <p>Total Jenis Barang</p>

                    <h2>
                        <?php echo $data_barang['total']; ?>
                    </h2>
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon">
                    📥
                </div>

                <div>
                    <p>Total Transaksi Masuk</p>

                    <h2>
                        <?php echo $data_masuk['total']; ?>
                    </h2>
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon">
                    📤
                </div>

                <div>
                    <p>Total Transaksi Keluar</p>

                    <h2>
                        <?php echo $data_keluar['total']; ?>
                    </h2>
                </div>

            </div>

        </div>


        <!-- MENU -->
    <!-- AREA KONTEN HALAMAN -->

    <div id="pageContent" class="page-content">

    <div class="content-placeholder">
        <div class="content-icon"> </div>
        <h2>Menu Utama</h2>
        
</p>

</div>
</div>

        <div class="menu-grid">


        
            <a href="barang.php" class="menu-card">

                <div class="menu-icon"> </div>
                    📦
                </div>

                <div>
                    <h3>Data Barang</h3>
                    <p>
                        Kelola data material gudang.
                    </p>
                </div>

            </a>


            <a href="barang_masuk.php" class="menu-card">

                <div class="menu-icon"> </div>
                    📥
                </div>

                <div>
                    <h3>Barang Masuk</h3>
                    <p>
                        Kelola transaksi material masuk.
                    </p>
                </div>

            </a>


            <a href="barang_keluar.php" class="menu-card">

                <div class="menu-icon"> </div>
                    📤
                </div>

                <div>
                    <h3>Barang Keluar</h3>
                    <p>
                        Kelola transaksi material keluar.
                    </p>
                </div>

            </a>


           <a href="laporan.php" class="menu-card">

                <div class="menu-icon"> </div>
                    📊
                </div>

                <div>
                    <h3>Laporan</h3>
                    <p>
                        Lihat laporan barang masuk, barang keluar, dan stok.
                    </p>
                </div>

            </a>

        </div>

<!-- TAMBAHKAN KODE TRANSAKSI TERBARU DI SINI  -->
  
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

<td>
    <?php echo htmlspecialchars($transaksi['tanggal']); ?>
</td>
 
<td> 
    <?php echo htmlspecialchars($transaksi['kode_barang']); ?>
</td>

<td> 
    <?php echo htmlspecialchars($transaksi['nama_barang']); ?>
</td>

<td>
    <?php if ($transaksi['jenis'] == 'Masuk') { ?>
    <span class="badge masuk">
        Masuk
</span>

<?php } else { ?>
    <span class="badge keluar">
        Keluar 
</span>

<?php } ?>

</td>

<td> 
    <?php echo htmlspecialchars($transaksi['jumlah']); ?>
</td>
</tr>

<?php } ?>
</tbody>
</table>
</div>
</div>
    </main>

</div>

<script>
function bukaHalamanLaporan() {
    const mainContent = document.querySelector('.main-content');

    mainContent.innerHTML = `
    <div class="embedded-page">

    <button class="btn-kembali" onclick="window.location.href='dashboard.php'">

    Kembali ke Dashboard

    </button>

    <iframe
    src="${halaman}"
    class="content-frame">
    </iframe>

    </div>
    `;

}
</script>
</body>
</html>
