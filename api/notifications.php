<?php
// api/notifications.php
include 'db.php';

// Cek user login
if (!isset($_SESSION['user'])) {
    echo json_encode([]); // Kembalikan array kosong jika belum login
    exit;
}

$user_email = $_SESSION['user']['email'];

// Ambil notifikasi untuk user yang login
$stmt = $conn->prepare("
    SELECT id, title, message, created_at as timestamp 
    FROM notifications
    WHERE user_email = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $row['id'] = (int)$row['id'];
    // Format timestamp
    $row['timestamp'] = date('d M Y, H:i', strtotime($row['timestamp']));
    $notifications[] = $row;
}

echo json_encode($notifications);
$stmt->close();
$conn->close();
?>