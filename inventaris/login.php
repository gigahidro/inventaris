<?php
include 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];

// 1. Cari user berdasarkan username
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) === 1) {
    // 2. Ambil data user
    $user = mysqli_fetch_assoc($result);

    // 3. Verifikasi password yang di-hash
    if (password_verify($password, $user['password'])) {
        // Password cocok! Buat session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // 4. Arahkan (redirect) berdasarkan role
        if ($user['role'] == 'admin') {
            header("Location: admin/index.php");
        } else {
            header("Location: user/index.php");
        }
    } else {
        // Password salah
        header("Location: index.php?status=password_salah");
    }
} else {
    // User tidak ditemukan
    header("Location: index.php?status=user_tidak_ditemukan");
}
