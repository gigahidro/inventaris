<?php
// Panggil penjaga dan koneksi
include '../auth_guard.php';
include '../config.php';

// Pastikan hanya admin
if ($_SESSION['role'] != 'admin') {
    die("Akses ditolak.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pinjam = $_POST['id_pinjam'];
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];

    // 1. Tambahkan (kembalikan) stok ke tabel 'barang'
    $update_stok_query = "UPDATE barang SET jumlah_tersedia = jumlah_tersedia + $jumlah 
                          WHERE id = '$id_barang'";

    // 2. Set tanggal kembali di tabel 'peminjaman' untuk menandai transaksi selesai
    // CURRENT_TIMESTAMP() adalah fungsi SQL untuk mengambil waktu saat ini
    $update_pinjam_query = "UPDATE peminjaman SET tanggal_kembali = CURRENT_TIMESTAMP() 
                            WHERE id = '$id_pinjam'";

    // Eksekusi kedua query
    if (mysqli_query($koneksi, $update_stok_query) && mysqli_query($koneksi, $update_pinjam_query)) {
        header("Location: index.php?status=kembali_diterima");
    } else {
        header("Location: index.php?status=kembali_gagal");
    }
}
