<?php
require_once '../config/database.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method tidak diizinkan']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Validasi input
if (!isset($data['name']) || !isset($data['email']) || !isset($data['password']) || !isset($data['confirm-password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Semua field harus diisi']);
    exit;
}

$name = trim($data['name']);
$email = trim($data['email']);
$password = $data['password'];
$confirmPassword = $data['confirm-password'];

// Validasi password
if ($password !== $confirmPassword) {
    http_response_code(400);
    echo json_encode(['error' => 'Password tidak cocok']);
    exit;
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['error' => 'Password minimal 8 karakter']);
    exit;
}

// Cek email sudah terdaftar
try {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Email sudah terdaftar']);
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Simpan user baru
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $hashedPassword]);

    echo json_encode([
        'success' => true,
        'message' => 'Registrasi berhasil'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Terjadi kesalahan server']);
    exit;
}
