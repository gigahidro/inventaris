<?php
// Selalu mulai session di awal
session_start();

// Hapus semua data session
session_unset();

// Hancurkan session
session_destroy();

// Arahkan kembali (redirect) ke halaman login dengan pesan
header("Location: index.php?status=logout_sukses");
exit();
