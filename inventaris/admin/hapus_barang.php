<?php
include '../auth_guard.php';
include '../config.php';
if ($_SESSION['role'] != 'admin') die("Akses ditolak.");

if (!isset($_GET['id'])) {
    header('Location: index.php?status=id_tidak_ditemukan');
    exit();
}

$id_barang = $_GET['id'];

// 1. Cek apakah barang ini sedang dalam proses peminjaman (Pending atau Disetujui)
$cek_pinjam_query = "SELECT * FROM peminjaman 
                     WHERE id_barang = '$id_barang' AND (status = 'Pending' OR status = 'Disetujui')";
$hasil_cek = mysqli_query($koneksi, $cek_pinjam_query);

if (mysqli_num_rows($hasil_cek) > 0) {
    // JIKA ADA, jangan hapus barangnya
    header("Location: index.php?status=hapus_gagal_dipinjam");
} else {
    // JIKA AMAN, hapus barangnya
    // Catatan: Anda mungkin juga perlu menghapus riwayat peminjaman (status 'Dikembalikan'/'Ditolak')
    // jika Anda ingin data bersih (menggunakan ON DELETE CASCADE di DB lebih baik).
    // Untuk saat ini, kita hanya hapus barangnya.

    $query = "DELETE FROM barang WHERE id = '$id_barang'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?status=hapus_barang_sukses");
    } else {
        header("Location: index.php?status=hapus_barang_gagal");
    }
}
