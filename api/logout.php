<?php
// api/logout.php
include 'db.php'; // Ini akan otomatis memulai sesi

// Hancurkan sesi
session_unset();
session_destroy();

echo json_encode(['success' => true, 'message' => 'Logout berhasil.']);
?>