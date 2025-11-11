<?php
// api/session.php
include 'db.php'; // Ini akan otomatis memulai sesi

if (isset($_SESSION['user'])) {
    echo json_encode(['success' => true, 'user' => $_SESSION['user']]);
} else {
    echo json_encode(['success' => false, 'user' => null]);
}
$conn->close();
?>