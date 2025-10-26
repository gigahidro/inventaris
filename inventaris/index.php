<?php
// Cek jika ada notifikasi status
$status_msg = '';
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'sukses_daftar':
            $status_msg = '<div class="alert alert-success">Registrasi berhasil! Silakan login.</div>';
            break;
        case 'username_terpakai':
            $status_msg = '<div class="alert">Username sudah terpakai. Coba yang lain.</div>';
            break;
        case 'password_salah':
            $status_msg = '<div class="alert">Password salah.</div>';
            break;
        case 'belum_login':
            $status_msg = '<div class="alert alert-warning">Anda harus login terlebih dahulu.</div>';
            break;
        case 'logout_sukses':
            $status_msg = '<div class="alert alert-success">Anda berhasil logout.</div>';
            break;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sistem Inventaris</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <?php echo $status_msg; ?>

    <div class="form-container" id="form-login">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            Username:
            <input type="text" name="username" required>

            Password:
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p class="form-toggle">
            Belum punya akun? <a href="#form-signup">Buat akun</a>
        </p>
    </div>

    <div class="form-container" id="form-signup">
        <h2>Sign Up (Pengguna Baru)</h2>
        <form action="register.php" method="POST">
            Nama Lengkap:
            <input type="text" name="nama_lengkap" required>

            NIM Mahasiswa:
            <input type="text" name="nim" required>

            Username:
            <input type="text" name="username" required>

            Password:
            <input type="password" name="password" required>

            <button type="submit">Daftar</button>
        </form>

        <p class="form-toggle">
            Sudah punya akun? <a href="#form-login">Login di sini</a>
        </p>
    </div>

    <script>
        // Tunggu sampai halaman selesai dimuat
        document.addEventListener("DOMContentLoaded", function() {

            // 1. Cari elemen notifikasi (div dengan class "alert")
            const alertElement = document.querySelector(".alert");

            // 2. Cek apakah notifikasi itu ada di halaman
            if (alertElement) {

                // 3. Tunggu 3 detik (3000 milidetik)
                setTimeout(function() {

                    // 4. Tambahkan class "fade-out" untuk memulai animasi
                    alertElement.classList.add("fade-out");

                    // 5. Tunggu animasi selesai (0.5 detik), lalu hapus total
                    //    agar tidak memakan tempat di halaman
                    setTimeout(function() {
                        alertElement.remove(); // Hapus elemen dari halaman
                    }, 500); // 500ms = 0.5 detik (sesuai durasi transisi CSS)

                }, 3000); // 3000ms = 3 detik (waktu tunggu sebelum notifikasi hilang)
            }
        });
    </script>

</body>

</html>