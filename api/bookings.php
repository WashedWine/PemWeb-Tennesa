<?php
// api/bookings.php
include 'db.php';

// Cek user login
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login.']);
    exit;
}

$user_email = $_SESSION['user']['email'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // Ambil riwayat booking untuk user yang login
    $stmt = $conn->prepare("
        SELECT b.*, c.name as courtName 
        FROM bookings b
        JOIN courts c ON b.court_id = c.id
        WHERE b.user_email = ?
        ORDER BY b.created_at DESC
    ");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $row['id'] = (int)$row['id'];
        $row['court_id'] = (int)$row['court_id'];
        $row['duration'] = (int)$row['duration'];
        $row['total_price'] = (int)$row['total_price'];
        $bookings[] = $row;
    }
    echo json_encode($bookings);
    $stmt->close();

} elseif ($method == 'POST') {
    // Buat booking baru
    $data = json_decode(file_get_contents('php://input'), true);

    $court_id = (int)$data['courtId'];
    $date = $data['date'];
    $time = $data['time'];
    $duration = (int)$data['duration'];
    $total_price = (int)$data['totalPrice'];
    $court_name = $data['courtName']; // Kita ambil dari data

    // TODO: Tambahkan validasi ketersediaan jam di sini

    $stmt = $conn->prepare("
        INSERT INTO bookings (user_email, court_id, booking_date, start_time, duration, total_price, status, payment_status)
        VALUES (?, ?, ?, ?, ?, ?, 'Aktif', 'Terbayar')
    ");
    $stmt->bind_param("sissii", $user_email, $court_id, $date, $time, $duration, $total_price);

    if ($stmt->execute()) {
        // Booking berhasil, sekarang buat notifikasi
        $title = "Booking Dikonfirmasi";
        $message = "Reservasi Anda untuk $court_name pada $date jam $time telah berhasil.";
        
        $stmt_notif = $conn->prepare("INSERT INTO notifications (user_email, title, message) VALUES (?, ?, ?)");
        $stmt_notif->bind_param("sss", $user_email, $title, $message);
        $stmt_notif->execute();
        $stmt_notif->close();

        echo json_encode(['success' => true, 'message' => 'Booking berhasil dibuat.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Booking gagal: ' . $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
?>