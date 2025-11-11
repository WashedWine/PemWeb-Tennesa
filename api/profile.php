<?php
// api/profile.php
include 'db.php';

// Cek user login
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login.']);
    exit;
}

$user_email = $_SESSION['user']['email'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    // Update profile
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $phone = $data['phone'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ? WHERE email = ?");
    $stmt->bind_param("sss", $name, $phone, $user_email);

    if ($stmt->execute()) {
        // Update sesi juga
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone;

        echo json_encode(['success' => true, 'user' => $_SESSION['user']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update profil gagal: ' . $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
?>