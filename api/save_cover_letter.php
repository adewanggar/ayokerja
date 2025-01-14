<?php
header('Content-Type: application/json');
require_once '../config/database.php';

// Terima data JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['user_id']) || !isset($data['content'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Data tidak lengkap']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Mulai transaksi
    $conn->beginTransaction();

    // Simpan cover letter
    $query = "INSERT INTO cover_letters (user_id, company_name, position, recipient_name, content) 
              VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->execute([
        $data['user_id'],
        $data['company_name'],
        $data['position'],
        $data['recipient_name'],
        $data['content']
    ]);

    $cover_letter_id = $conn->lastInsertId();

    // Cek apakah user sudah ada di feature_usage
    $checkQuery = "SELECT COUNT(*) as count FROM feature_usage 
                  WHERE user_id = ? AND feature_type = 'cover_letter'";
    $stmt = $conn->prepare($checkQuery);
    $stmt->execute([$data['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        // Update usage count jika user sudah ada
        $updateQuery = "UPDATE feature_usage 
                       SET usage_count = usage_count + 1,
                           last_used = NOW()
                       WHERE user_id = ? AND feature_type = 'cover_letter'";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([$data['user_id']]);
    } else {
        // Insert data baru jika user belum ada
        $insertQuery = "INSERT INTO feature_usage (user_id, feature_type, usage_count, last_used) 
                       VALUES (?, 'cover_letter', 1, NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->execute([$data['user_id']]);
    }

    // Catat aktivitas di activity_logs
    $details = "Cover letter untuk posisi {$data['position']} di {$data['company_name']} telah dibuat";
    $logQuery = "INSERT INTO activity_logs (user_id, activity_type, activity_id, action, details) 
                 VALUES (?, 'cover_letter', ?, 'create', ?)";
    $stmt = $conn->prepare($logQuery);
    $stmt->execute([
        $data['user_id'],
        $cover_letter_id,
        $details
    ]);

    // Commit transaksi
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Cover letter berhasil disimpan',
        'id' => $cover_letter_id
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
