<?php
// api/courts.php
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

// Cek autentikasi (jika perlu)
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin';
}

if ($method == 'GET') {
    // Ambil semua data lapangan
    $result = $conn->query("SELECT * FROM courts ORDER BY id");
    $courts = [];
    while ($row = $result->fetch_assoc()) {
        $row['id'] = (int)$row['id'];
        $row['price'] = (int)$row['price'];
        $courts[] = $row;
    }
    echo json_encode($courts);

} elseif ($method == 'POST') {
    // Tambah lapangan baru (hanya admin)
    if (!isAdmin()) {
        echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $facilities = $data['facilities'];
    $price = (int)$data['price'];
    $status = $data['status'] ?? 'Tersedia';

    $stmt = $conn->prepare("INSERT INTO courts (name, facilities, price, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $facilities, $price, $status);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
    $stmt->close();

} elseif ($method == 'DELETE') {
    // Hapus lapangan (hanya admin)
    if (!isAdmin()) {
        echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
        exit;
    }

    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM courts WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
?>