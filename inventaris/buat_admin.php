<?php
include 'config.php'; // Panggil koneksi database

// Data admin yang ingin Anda buat
$nama_lengkap = "Admin Utama";
$username = "admin";
$password = "admin123"; // Ganti dengan password yang kuat
$role = "admin";

// Hash passwordnya
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Query untuk memasukkan admin
$query = "INSERT INTO users (nama_lengkap, username, password, role) 
          VALUES ('$nama_lengkap', '$username', '$hashed_password', '$role')";

if (mysqli_query($koneksi, $query)) {
    echo "<h1>Akun Admin berhasil dibuat!</h1>";
    echo "Username: $username <br>";
    echo "Password: $password <br>";
    echo "<h3>Harap HAPUS file 'buat_admin.php' ini sekarang juga!</h3>";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
