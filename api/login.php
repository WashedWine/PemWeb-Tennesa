<?php
// api/login.php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Email dan Password wajib diisi.']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    
    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Buat sesi
        $_SESSION['user'] = [
            'email' => $user['email'],
            'name' => $user['name'],
            'role' => $user['role'],
            'phone' => $user['phone']
        ];
        
        // Kirim data user ke client (untuk disimpan di localStorage)
        echo json_encode([
            'success' => true,
            'user' => $_SESSION['user']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Email atau Password salah.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Email atau Password salah.']);
}

$stmt->close();
$conn->close();
?>