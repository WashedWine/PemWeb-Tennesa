<?php
// api/register.php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$phone = $data['phone'] ?? '';
$password = $data['password'] ?? '';

if (empty($name) || empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Semua field (Nama, Email, Password) wajib diisi.']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password minimal 6 karakter.']);
    exit;
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'user')");
$stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Registrasi berhasil! Silakan login.']);
} else {
    if ($conn->errno == 1062) { // Error duplikat email
        echo json_encode(['success' => false, 'message' => 'Email sudah terdaftar.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registrasi gagal: ' . $stmt->error]);
    }
}

$stmt->close();
$conn->close();
?>