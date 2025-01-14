<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/session.php';

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method tidak diizinkan']);
    exit;
}

// Terima data JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validasi input
if (!isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Email dan password harus diisi']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Cari user berdasarkan email
    $stmt = $conn->prepare("SELECT id, name, email, password, subscription_type FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($data['password'], $user['password'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Email atau password salah']);
        exit;
    }

    // Buat session token
    $token = bin2hex(random_bytes(32));

    // Simpan session ke database
    $stmt = $conn->prepare("INSERT INTO user_sessions (user_id, token, ip_address, user_agent) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $user['id'],
        $token,
        $_SERVER['REMOTE_ADDR'],
        $_SERVER['HTTP_USER_AGENT']
    ]);

    // Set session PHP
    setUserSession($user);

    // Hapus password dari response
    unset($user['password']);

    echo json_encode([
        'success' => true,
        'message' => 'Login berhasil',
        'user' => $user,
        'token' => $token
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Terjadi kesalahan server']);
    exit;
}
