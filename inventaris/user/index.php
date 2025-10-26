<?php
// (PERBAIKAN 1) Mulai session di baris paling atas
session_start();

// Panggil penjaga dan koneksi
include '../auth_guard.php';
include '../config.php';

// Pastikan hanya user
if ($_SESSION['role'] != 'user') {
    die("Akses ditolak.");
}

// Ambil ID user yang sedang login
$id_user_login = $_SESSION['user_id'];

// (PERBAIKAN 2) Ambil data barang yang tersedia (stok > 0)
$data_barang = mysqli_query($koneksi, "SELECT * FROM barang WHERE jumlah_tersedia > 0 ORDER BY nama_barang ASC");

// (PERBAIKAN 3) Ambil riwayat pinjam user ini
$riwayat = mysqli_query($koneksi, "SELECT p.*, b.nama_barang 
                                  FROM peminjaman p 
                                  JOIN barang b ON p.id_barang = b.id
                                  WHERE p.id_user = '$id_user_login'
                                  ORDER BY p.tanggal_pinjam DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard User</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

    <div class="container">

        <div class="header">
            <h2>Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <a href="../logout.php">Logout</a>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'pinjam_sukses'): ?>
                <div class="alert alert-success">Berhasil mengajukan peminjaman, tunggu persetujuan admin.</div>
            <?php elseif ($_GET['status'] == 'stok_kurang'): ?>
                <div class="alert">Gagal, stok barang tidak mencukupi.</div>
            <?php elseif ($_GET['status'] == 'kembali_sukses'): ?>
                <div class="alert alert-success">Berhasil mengajukan pengembalian, tunggu verifikasi admin.</div>
            <?php endif; ?>
        <?php endif; ?>

        <br>
        <h3>1. Daftar Barang Tersedia</h3>
        <table>
            <tr>
                <th>Nama Barang</th>
                <th>Deskripsi</th>
                <th>Stok Tersedia</th>
                <th>Aksi Pinjam</th>
            </tr>
            <?php while ($barang = mysqli_fetch_assoc($data_barang)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($barang['nama_barang']); ?></td>
                    <td><?php echo htmlspecialchars($barang['deskripsi']); ?></td>
                    <td><?php echo $barang['jumlah_tersedia']; ?></td>
                    <td>
                        <form action="proses_pinjam.php" method="POST">
                            <input type="hidden" name="id_barang" value="<?php echo $barang['id']; ?>">
                            Jumlah:
                            <input type="number" name="jumlah" min="1" max="<?php echo $barang['jumlah_tersedia']; ?>" value="1" required>
                            <button type="submit">Pinjam</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($data_barang) == 0) echo "<tr><td colspan='4'>Tidak ada barang yang tersedia saat ini.</td></tr>"; ?>
        </table>

        <br>
        <h3>2. Riwayat Peminjaman Anda</h3>
        <table>
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Pinjam</th>
                <th>Status</th>
                <th>Tanggal Kembali</th>
                <th>Aksi</th>
            </tr>
            <?php while ($item = mysqli_fetch_assoc($riwayat)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['nama_barang']); ?></td>
                    <td><?php echo $item['jumlah']; ?></td>
                    <td><?php echo $item['tanggal_pinjam']; ?></td>

                    <td class="status-<?php echo strtolower($item['status']); ?>">
                        <?php echo $item['status']; ?>
                    </td>

                    <td>
                        <?php echo $item['tanggal_kembali'] ? $item['tanggal_kembali'] : '-'; ?>
                    </td>

                    <td>
                        <?php
                        // Tampilkan tombol "Kembalikan" HANYA jika statusnya 'Disetujui'
                        if ($item['status'] == 'Disetujui') :
                        ?>
                            <form action="proses_kembali.php" method="POST">
                                <input type="hidden" name="id_pinjam" value="<?php echo $item['id']; ?>">
                                <button type="submit">Kembalikan</button>
                            </form>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($riwayat) == 0) echo "<tr><td colspan='6'>Anda belum pernah meminjam barang.</td></tr>"; ?>
        </table>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Cari elemen notifikasi
            const alertElement = document.querySelector(".alert");

            // 2. Cek apakah notifikasi ada
            if (alertElement) {

                // 3. Tunggu 3 detik
                setTimeout(function() {

                    // 4. Tambahkan class "fade-out"
                    alertElement.classList.add("fade-out");

                    // 5. Hapus elemen setelah animasi selesai
                    setTimeout(function() {
                        alertElement.remove();
                    }, 500); // 0.5 detik

                }, 3000); // 3 detik
            }
        });
    </script>

</body>

</html>