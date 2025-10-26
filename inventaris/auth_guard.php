<?php
// (PERBAIKAN) Mulai sesi di sini, dengan aman.
// Ini akan memperbaiki error $_SESSION undefined di SEMUA halaman admin dan user.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum, tendang ke halaman login
    header("Location: ../index.php?status=belum_login");
    exit(); // Pastikan script berhenti
}
