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
    $aksi = $_POST['aksi']; // 'setuju' atau 'tolak'

    if ($aksi == 'setuju') {
        // 1. Cek stok sekali lagi (untuk jaga-jaga)
        $cek_stok_query = "SELECT jumlah_tersedia FROM barang WHERE id = '$id_barang'";
        $hasil_stok = mysqli_query($koneksi, $cek_stok_query);
        $data_stok = mysqli_fetch_assoc($hasil_stok);

        if ($data_stok['jumlah_tersedia'] < $jumlah) {
            header("Location: index.php?status=verifikasi_gagal_stok");
            exit();
        }

        // 2. Kurangi stok di tabel 'barang'
        $update_stok_query = "UPDATE barang SET jumlah_tersedia = jumlah_tersedia - $jumlah 
                              WHERE id = '$id_barang'";

        // 3. Ubah status di tabel 'peminjaman'
        $update_pinjam_query = "UPDATE peminjaman SET status = 'Disetujui' 
                                WHERE id = '$id_pinjam'";

        // Eksekusi kedua query
        if (mysqli_query($koneksi, $update_stok_query) && mysqli_query($koneksi, $update_pinjam_query)) {
            header("Location: index.php?status=verifikasi_sukses");
        } else {
            header("Location: index.php?status=verifikasi_gagal");
        }
    } elseif ($aksi == 'tolak') {
        // Jika ditolak, cukup ubah status
        $update_pinjam_query = "UPDATE peminjaman SET status = 'Ditolak' 
                                WHERE id = '$id_pinjam'";

        if (mysqli_query($koneksi, $update_pinjam_query)) {
            header("Location: index.php?status=verifikasi_ditolak");
        } else {
            header("Location: index.php?status=verifikasi_gagal");
        }
    }
}
