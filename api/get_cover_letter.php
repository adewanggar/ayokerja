<?php
require_once '../config/database.php';
header('Content-Type: application/json');

// Validasi parameter yang diperlukan
if (!isset($_GET['id']) || !isset($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID dan User ID diperlukan']);
    exit;
}

$id = $_GET['id'];
$userId = $_GET['user_id'];

try {
    // Siapkan dan jalankan query
    $stmt = $conn->prepare("
        SELECT * FROM cover_letters 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$id, $userId]);
    $coverLetter = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cek apakah data ditemukan
    if (!$coverLetter) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Cover letter tidak ditemukan']);
        exit;
    }

    // Kembalikan data
    echo json_encode([
        'success' => true,
        'data' => $coverLetter
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Terjadi kesalahan server']);
    exit;
}
