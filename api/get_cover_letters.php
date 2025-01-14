<?php
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'User ID diperlukan'
    ]);
    exit;
}

$user_id = (int)$_GET['user_id'];

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Ambil daftar cover letter
    $query = "SELECT id, company_name, position, recipient_name, created_at 
              FROM cover_letters 
              WHERE user_id = ? 
              ORDER BY created_at DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute([$user_id]);
    $letters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $letters
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Terjadi kesalahan saat mengambil data'
    ]);
}
