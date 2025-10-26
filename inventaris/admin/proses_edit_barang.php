<?php
include '../auth_guard.php';
include '../config.php';
if ($_SESSION['role'] != 'admin') die("Akses ditolak.");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_barang = $_POST['id_barang'];
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $deskripsi = $_POST['deskripsi'];
    $jumlah_total_baru = $_POST['jumlah_total'];
    $jumlah_dipinjam = $_POST['jumlah_dipinjam']; // Ambil dari form sebelumnya

    // Validasi server-side
    if ($jumlah_total_baru < $jumlah_dipinjam) {
        die("Error: Jumlah total tidak boleh lebih kecil dari jumlah yang sedang dipinjam ($jumlah_dipinjam).");
    }

    // Hitung ulang 'jumlah_tersedia'
    // jumlah_tersedia = jumlah_total (yang baru) - jumlah_yang_sedang_dipinjam
    $jumlah_tersedia_baru = $jumlah_total_baru - $jumlah_dipinjam;

    // Query untuk update
    $query = "UPDATE barang SET 
                kode_barang = '$kode_barang',
                nama_barang = '$nama_barang',
                deskripsi = '$deskripsi',
                jumlah_total = '$jumlah_total_baru',
                jumlah_tersedia = '$jumlah_tersedia_baru'
              WHERE id = '$id_barang'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?status=edit_barang_sukses");
    } else {
        header("Location: edit_barang.php?id=$id_barang&status=edit_gagal");
    }
}
