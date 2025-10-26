<?php
include '../auth_guard.php';
include '../config.php';

if ($_SESSION['role'] != 'admin') {
    die("Akses ditolak.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $deskripsi = $_POST['deskripsi'];
    $jumlah_total = $_POST['jumlah_total'];

    // Saat barang baru ditambahkan, jumlah tersedia = jumlah total
    $jumlah_tersedia = $jumlah_total;

    // Query untuk insert
    $query = "INSERT INTO barang (kode_barang, nama_barang, deskripsi, jumlah_total, jumlah_tersedia)
              VALUES ('$kode_barang', '$nama_barang', '$deskripsi', '$jumlah_total', '$jumlah_tersedia')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?status=tambah_barang_sukses");
    } else {
        // Cek jika error karena kode_barang duplikat
        if (mysqli_errno($koneksi) == 1062) { // 1062 = Error duplikat
            header("Location: tambah_barang.php?status=error_duplikat");
        } else {
            header("Location: tambah_barang.php?status=error_lainnya");
        }
    }
}
