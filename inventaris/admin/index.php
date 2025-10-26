<?php
// (PERBAIKAN) SEMUA KODE PHP HARUS DI ATAS SEBELUM HTML
// 1. Panggil penjaga (auth_guard). Ini akan otomatis menjalankan session_start()
include '../auth_guard.php';

// 2. Panggil koneksi database
include '../config.php';

// 3. Pastikan hanya admin yang bisa akses
if ($_SESSION['role'] != 'admin') {
    die("Akses ditolak.");
}

// 4. Ambil semua data yang diperlukan
$data_barang = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY nama_barang ASC");
$data_user = mysqli_query($koneksi, "SELECT id, username, nama_lengkap, role FROM users ORDER BY username ASC");

// 5. Ambil data Permintaan Pinjam (Pending)
$req_pinjam = mysqli_query($koneksi, "SELECT p.*, u.username, b.nama_barang 
                                    FROM peminjaman p
                                    JOIN users u ON p.id_user = u.id
                                    JOIN barang b ON p.id_barang = b.id
                                    WHERE p.status = 'Pending'");
// 6. Ambil data Permintaan Kembali (Status 'Dikembalikan' tapi tanggal_kembali masih NULL)
$req_kembali = mysqli_query($koneksi, "SELECT p.*, u.username, b.nama_barang 
                                     FROM peminjaman p
                                     JOIN users u ON p.id_user = u.id
                                     JOIN barang b ON p.id_barang = b.id
                                     WHERE p.status = 'Dikembalikan' AND p.tanggal_kembali IS NULL");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

    <div class="container">

        <div class="header">
            <h2>Admin: <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <a href="../logout.php">Logout</a>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'hapus_gagal_dipinjam'): ?>
                <div class="alert">Gagal menghapus! Barang sedang aktif dipinjam.</div>
            <?php elseif (strpos($_GET['status'], 'sukses') !== false): ?>
                <div class="alert alert-success">Aksi berhasil!</div>
            <?php endif; ?>
        <?php endif; ?>


        <h3>1. Verifikasi Peminjaman (Pending)</h3>
        <table>
            <tr>
                <th>User</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Aksi</th>
            </tr>
            <?php while ($req = mysqli_fetch_assoc($req_pinjam)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($req['username']); ?></td>
                    <td><?php echo htmlspecialchars($req['nama_barang']); ?></td>
                    <td><?php echo $req['jumlah']; ?></td>
                    <td><?php echo $req['tanggal_pinjam']; ?></td>
                    <td>
                        <form action="verifikasi_pinjam.php" method="POST" class="aksi-form">
                            <input type="hidden" name="id_pinjam" value="<?php echo $req['id']; ?>">
                            <input type="hidden" name="id_barang" value="<?php echo $req['id_barang']; ?>">
                            <input type="hidden" name="jumlah" value="<?php echo $req['jumlah']; ?>">
                            <button type="submit" name="aksi" value="setuju">Setujui</button>
                            <button type="submit" name="aksi" value="tolak" class="button-danger">Tolak</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($req_pinjam) == 0) echo "<tr><td colspan='5'>Tidak ada permintaan peminjaman baru.</td></tr>"; ?>
        </table>

        <br>
        <h3>2. Verifikasi Pengembalian</h3>
        <table>
            <tr>
                <th>User</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php while ($req = mysqli_fetch_assoc($req_kembali)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($req['username']); ?></td>
                    <td><?php echo htmlspecialchars($req['nama_barang']); ?></td>
                    <td><?php echo $req['jumlah']; ?></td>
                    <td><?php echo $req['status']; ?></td>
                    <td>
                        <form action="verifikasi_kembali.php" method="POST" class="aksi-form">
                            <input type="hidden" name="id_pinjam" value="<?php echo $req['id']; ?>">
                            <input type="hidden" name="id_barang" value="<?php echo $req['id_barang']; ?>">
                            <input type="hidden" name="jumlah" value="<?php echo $req['jumlah']; ?>">
                            <button type="submit">Konfirmasi Pengembalian</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($req_kembali) == 0) echo "<tr><td colspan='5'>Tidak ada barang yang perlu dikonfirmasi pengembaliannya.</td></tr>"; ?>
        </table>


        <br>
        <h3>3. Data Barang Inventaris</h3>
        <a href="tambah_barang.php" class="button">[+] Tambah Barang Baru</a>
        <br><br>
        <table>
            <tr>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Total</th>
                <th>Tersedia</th>
                <th>Aksi</th>
            </tr>
            <?php while ($barang = mysqli_fetch_assoc($data_barang)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($barang['kode_barang']); ?></td>
                    <td><?php echo htmlspecialchars($barang['nama_barang']); ?></td>
                    <td><?php echo $barang['jumlah_total']; ?></td>
                    <td><?php echo $barang['jumlah_tersedia']; ?></td>
                    <td>
                        <a href="edit_barang.php?id=<?php echo $barang['id']; ?>">Edit</a> |
                        <a href="hapus_barang.php?id=<?php echo $barang['id']; ?>"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus <?php echo htmlspecialchars($barang['nama_barang']); ?>?');">
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <br>
        <h3>4. Data User Terdaftar</h3>
        <table>
            <tr>
                <th>Username</th>
                <th>Nama Lengkap</th>
                <th>Role</th>
            </tr>
            <?php while ($user = mysqli_fetch_assoc($data_user)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['nama_lengkap']); ?></td>
                    <td><?php echo $user['role']; ?></td>
                </tr>
            <?php endwhile; ?>
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