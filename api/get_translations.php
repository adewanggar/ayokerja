<?php
header('Content-Type: application/json');
require_once '../config/database.php';

// Debug: Log request
error_log("GET request received: " . print_r($_GET, true));

// Validasi parameter yang diperlukan
if (!isset($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'User ID tidak ditemukan']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Debug: Log database connection
    error_log("Database connection established");

    // Ambil riwayat terjemahan
    $query = "SELECT id, source_language, target_language, original_content, 
              translated_content, translation_quality, created_at 
              FROM resume_translations 
              WHERE user_id = ? 
              ORDER BY created_at DESC 
              LIMIT 10";

    // Debug: Log query
    error_log("Executing query: " . $query);
    error_log("User ID: " . $_GET['user_id']);

    $stmt = $conn->prepare($query);
    $stmt->execute([$_GET['user_id']]);
    $translations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug: Log hasil query
    error_log("Query results: " . print_r($translations, true));

    // Format response
    $response = [
        'success' => true,
        'translations' => array_map(function ($item) {
            return [
                'id' => (int)$item['id'],
                'source_language' => $item['source_language'],
                'target_language' => $item['target_language'],
                'original_content' => $item['original_content'],
                'translated_content' => $item['translated_content'],
                'translation_quality' => (float)$item['translation_quality'],
                'created_at' => $item['created_at']
            ];
        }, $translations)
    ];

    // Debug: Log response sebelum dikirim
    error_log("Response to be sent: " . json_encode($response));

    echo json_encode($response);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("General error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error: ' . $e->getMessage()
    ]);
}
