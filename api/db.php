<?php
// api/db.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Ganti dengan username database Anda
define('DB_PASS', ''); // Ganti dengan password database Anda
define('DB_NAME', 'tennesa_db'); // Nama database yang kita buat

// Buat koneksi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

// Set header untuk JSON
header('Content-Type: application/json');

// Mulai session di semua file yang membutuhkannya
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>