<?php
include 'config.php';

// 1. Ambil data dari form (termasuk NIM)
$nama_lengkap = $_POST['nama_lengkap'];
$nim = $_POST['nim']; // <-- Data baru
$username = $_POST['username'];
$password = $_POST['password'];

// 2. Validasi sederhana
if (empty($nama_lengkap) || empty($nim) || empty($username) || empty($password)) {
    die("Data tidak boleh kosong!");
}

// 3. Cek username duplikat (Sudah aman pakai Prepared Statement)
$stmt_cek = mysqli_prepare($koneksi, "SELECT id FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt_cek, "s", $username);
mysqli_stmt_execute($stmt_cek);
mysqli_stmt_store_result($stmt_cek);

if (mysqli_stmt_num_rows($stmt_cek) > 0) {
    mysqli_stmt_close($stmt_cek);
    header("Location: index.php?status=username_terpakai#form-signup"); // Kembali ke form signup
    exit();
}
mysqli_stmt_close($stmt_cek);


// 4. Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);


// 5. (PERUBAHAN) Masukkan user baru (dengan NIM)
// Query diubah: (nama_lengkap, nim, username, password, role) VALUES (?, ?, ?, ?, 'user')
$stmt_insert = mysqli_prepare($koneksi, "INSERT INTO users (nama_lengkap, nim, username, password, role) VALUES (?, ?, ?, ?, 'user')");
// Bind param diubah: "sss" menjadi "ssss"
mysqli_stmt_bind_param($stmt_insert, "ssss", $nama_lengkap, $nim, $username, $hashed_password);

if (mysqli_stmt_execute($stmt_insert)) {
    // Sukses
    mysqli_stmt_close($stmt_insert);
    mysqli_close($koneksi);
    header("Location: index.php?status=sukses_daftar#form-login"); // Arahkan ke form login
    exit();
} else {
    // Gagal
    mysqli_stmt_close($stmt_insert);
    mysqli_close($koneksi);
    echo "Error: Registrasi gagal. Silakan coba lagi.";
}
