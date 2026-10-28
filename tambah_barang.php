<?php
include 'cek_login.php';
include 'koneksi.php';
if (isset($_POST['simpan'])){
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $satuan = $_POST['satuan'];
    $stok = $_POST['stok'];

    $query = "INSERT INTO barang 
        (kode_barang, nama_barang, kategori, satuan, stok)
        VALUES
        ('$kode_barang' , '$nama_barang' , '$kategori' , '$satuan', '$stok')";
        mysqli_query($koneksi, $query);
        header("Location : barang.php");
        exit;

}

?>
<!DOCTYPE  html>
<html>
    <head>
        <title>Tambah Barang</title>
</head>
<body>

<h1>Tambah Barang</h1>
<form method="POST" action="barang.php">
    <label>Kode Barang</label><br>
    <input type="text" name="kode_barang" required>
    <br><br>

    <label>Nama Barang</label><br>
    <input type="text" name="nama_barang" required>
    <br><br>

    <label>Kategori</label><br>
    <input type="text" name="kategori">
    <br><br>

    <label>Satuan</label><br>
    <input type="text" name="satuan">
    <br><br>

    <label>Stok Awal</label><br>
    <input type="number" name="stok" value="0" required>
    <br><br>
    
    <button type="submit" name="tambah">Simpan</button>
</form>
<br>

<a href="barang.php">Kembali ke Data Barang</a>
<br>
<a href="barang.php">kembali ke Data Barang</a>
</body>
</html>