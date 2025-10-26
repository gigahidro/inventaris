<?php
// Mulai session di setiap halaman
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detail koneksi database
$DB_HOST = 'localhost:3307';
$DB_USER = 'root'; // User default XAMPP
$DB_PASS = '';     // Password default XAMPP
$DB_NAME = 'inventaris_barang_kampus'; // Sesuaikan dengan nama database Anda

// Buat koneksi menggunakan MySQLi
$koneksi = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
