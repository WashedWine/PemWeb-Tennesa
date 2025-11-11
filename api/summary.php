<?php
// api/summary.php
include 'db.php';

// Hanya admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

$summary = [
    'total_booking' => 0,
    'total_pendapatan' => 0,
    'total_lapangan' => 0
];

// Total Booking
$result = $conn->query("SELECT COUNT(*) as total FROM bookings");
$summary['total_booking'] = (int)$result->fetch_assoc()['total'];

// Total Pendapatan
$result = $conn->query("SELECT SUM(total_price) as total FROM bookings");
$summary['total_pendapatan'] = (int)$result->fetch_assoc()['total'];

// Total Lapangan
$result = $conn->query("SELECT COUNT(*) as total FROM courts");
$summary['total_lapangan'] = (int)$result->fetch_assoc()['total'];

echo json_encode($summary);

$conn->close();
?>