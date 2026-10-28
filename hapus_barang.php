<?php
include 'cek_login.php';
include 'koneksi.php';

$id = $_GET['id'];

$hapus = mysqli_query($koneksi, "
    DELETE FROM barang
    WHERE id_barang = '$id'
");

if ($hapus) {
    header("Location: barang.php");
    exit;
} else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}
?>