<?php
// ... (kode PHP) ...
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tambah Barang - Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

    <div class="container">
        <h2>Tambah Barang Inventaris Baru</h2>
        <a href="index.php">Kembali ke Dashboard</a>
        <hr>

        <form action="proses_tambah_barang.php" method="POST">
            Kode Barang: <br>
            <input type="text" name="kode_barang" required><br>

            Nama Barang: <br>
            <input type="text" name="nama_barang" required><br>

            Deskripsi: <br>
            <textarea name="deskripsi"></textarea><br>

            Jumlah Total: <br>
            <input type="number" name="jumlah_total" min="1" required><br>

            <button type="submit">Tambah Barang</button>
        </form>
    </div>

</body>

</html>