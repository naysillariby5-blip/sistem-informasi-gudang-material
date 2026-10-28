<?php
$koneksi = mysqli_connect(
    "localhost",
    "root",
    "",
    "db_gudang"
);
if (!$koneksi) {
    die("koneksi database gagal: ". mysqli_connect_error());
}
?>