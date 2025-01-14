<?php
header('Content-Type: application/json');
require_once '../config/database.php';

// Terima data JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validasi data yang diperlukan
if (
    !$data || !isset($data['user_id']) || !isset($data['original_content']) ||
    !isset($data['translated_content']) || !isset($data['source_language']) ||
    !isset($data['target_language']) || !isset($data['translation_quality'])
) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Data tidak lengkap']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Mulai transaksi
    $conn->beginTransaction();

    // Simpan terjemahan
    $query = "INSERT INTO resume_translations (user_id, source_language, target_language, 
              original_content, translated_content, translation_quality) 
              VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->execute([
        $data['user_id'],
        $data['source_language'],
        $data['target_language'],
        $data['original_content'],
        $data['translated_content'],
        $data['translation_quality']
    ]);

    $translation_id = $conn->lastInsertId();

    // Cek apakah user sudah ada di feature_usage
    $checkQuery = "SELECT COUNT(*) as count FROM feature_usage 
                  WHERE user_id = ? AND feature_type = 'translation'";
    $stmt = $conn->prepare($checkQuery);
    $stmt->execute([$data['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        // Update usage count jika user sudah ada
        $updateQuery = "UPDATE feature_usage 
                       SET usage_count = usage_count + 1,
                           last_used = NOW()
                       WHERE user_id = ? AND feature_type = 'translation'";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([$data['user_id']]);
    } else {
        // Insert data baru jika user belum ada
        $insertQuery = "INSERT INTO feature_usage (user_id, feature_type, usage_count, last_used) 
                       VALUES (?, 'translation', 1, NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->execute([$data['user_id']]);
    }

    // Catat aktivitas di activity_logs
    $details = "Terjemahan dari {$data['source_language']} ke {$data['target_language']} telah dibuat";
    $logQuery = "INSERT INTO activity_logs (user_id, activity_type, activity_id, action, details) 
                 VALUES (?, 'translation', ?, 'create', ?)";
    $stmt = $conn->prepare($logQuery);
    $stmt->execute([
        $data['user_id'],
        $translation_id,
        $details
    ]);

    // Commit transaksi
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Terjemahan berhasil disimpan',
        'id' => $translation_id
    ]);
} catch (PDOException $e) {
    // Rollback jika terjadi error
    if ($conn) {
        $conn->rollBack();
    }

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
