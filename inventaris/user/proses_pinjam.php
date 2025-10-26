<?php
// Panggil penjaga dan koneksi
include '../auth_guard.php';
include '../config.php';

// Pastikan hanya user
if ($_SESSION['role'] != 'user') {
    die("Akses ditolak.");
}

// Ambil data dari form (di user/index.php)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_barang = $_POST['id_barang'];
    $jumlah_pinjam = $_POST['jumlah'];
    $id_user = $_SESSION['user_id'];

    // 1. Cek dulu ketersediaan stok
    $cek_stok_query = "SELECT jumlah_tersedia FROM barang WHERE id = '$id_barang'";
    $hasil_stok = mysqli_query($koneksi, $cek_stok_query);
    $data_stok = mysqli_fetch_assoc($hasil_stok);

    if ($data_stok['jumlah_tersedia'] < $jumlah_pinjam) {
        // Jika stok tidak cukup
        header("Location: index.php?status=stok_kurang");
    } else {
        // 2. Jika stok cukup, masukkan data ke tabel peminjaman
        // Status default adalah 'Pending'
        $query = "INSERT INTO peminjaman (id_user, id_barang, jumlah, status) 
                  VALUES ('$id_user', '$id_barang', '$jumlah_pinjam', 'Pending')";

        if (mysqli_query($koneksi, $query)) {
            // Berhasil mengajukan, kembali ke dashboard user
            header("Location: index.php?status=pinjam_sukses");
        } else {
            // Gagal
            header("Location: index.php?status=pinjam_gagal");
        }
    }
}
