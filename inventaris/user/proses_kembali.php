<?php
// Panggil penjaga dan koneksi
include '../auth_guard.php';
include '../config.php';

// Pastikan hanya user
if ($_SESSION['role'] != 'user') {
    die("Akses ditolak.");
}

// Ambil id peminjaman dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pinjam = $_POST['id_pinjam'];
    $id_user = $_SESSION['user_id'];

    // Ubah status menjadi 'Dikembalikan'
    // Cek juga id_user agar user tidak bisa mengembalikan barang orang lain
    $query = "UPDATE peminjaman SET status = 'Dikembalikan' 
              WHERE id = '$id_pinjam' AND id_user = '$id_user'";

    if (mysqli_query($koneksi, $query)) {
        // Berhasil, barang menunggu verifikasi admin
        header("Location: index.php?status=kembali_sukses");
    } else {
        // Gagal
        header("Location: index.php?status=kembali_gagal");
    }
}
