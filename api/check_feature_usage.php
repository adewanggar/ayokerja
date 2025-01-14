<?php
header('Content-Type: application/json');
require_once '../config/database.php';

// Ambil parameter
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$feature_type = isset($_GET['feature_type']) ? $_GET['feature_type'] : '';

if (!$user_id || !$feature_type) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Parameter tidak lengkap']);
    exit;
}

try {
    // Buat koneksi database
    $db = new Database();
    $conn = $db->getConnection();

    // Cek penggunaan fitur
    $query = "SELECT fu.usage_count, u.subscription_type 
             FROM feature_usage fu 
             JOIN users u ON u.id = fu.user_id 
             WHERE fu.user_id = ? AND fu.feature_type = ?";

    $stmt = $conn->prepare($query);
    $stmt->execute([$user_id, $feature_type]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo json_encode([
            'success' => true,
            'usage_count' => 0,
            'subscription_type' => 'free'
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'usage_count' => (int)$result['usage_count'],
            'subscription_type' => $result['subscription_type']
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
