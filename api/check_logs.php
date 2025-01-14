<?php
require_once '../config/database.php';
header('Content-Type: application/json');

try {
    $database = new Database();
    $conn = $database->getConnection();

    $stmt = $conn->query("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 5");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'count' => count($logs),
        'logs' => $logs
    ], JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
