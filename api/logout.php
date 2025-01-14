<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/session.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Hapus token dari database jika ada
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        $stmt = $conn->prepare("DELETE FROM user_sessions WHERE token = ?");
        $stmt->execute([$token]);
    }

    // Hapus session PHP
    destroyUserSession();

    echo json_encode([
        'success' => true,
        'message' => 'Logout berhasil'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Terjadi kesalahan server'
    ]);
}
