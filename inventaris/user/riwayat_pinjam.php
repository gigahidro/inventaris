<?php
// Mulai session di setiap halaman
session_start();

// Detail koneksi database
$DB_HOST = 'localhost';
$DB_USER = 'root'; // User default XAMPP
$DB_PASS = '';     // Password default XAMPP
$DB_NAME = 'db_inventaris'; // Sesuaikan dengan nama database Anda

// Buat koneksi menggunakan MySQLi
$koneksi = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
