<?php
require_once '../config/database.php';
header('Content-Type: application/json');

// Validasi method request
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method tidak diizinkan']);
    exit;
}

// Validasi parameter yang diperlukan
if (!isset($_GET['id']) || !isset($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID dan User ID diperlukan']);
    exit;
}

$id = $_GET['id'];
$user_id = $_GET['user_id'];

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Mulai transaksi
    $conn->beginTransaction();

    // Cek apakah cover letter ada dan milik user yang benar
    $stmt = $conn->prepare("SELECT id FROM cover_letters WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);

    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Cover letter tidak ditemukan']);
        exit;
    }

    // Hapus cover letter
    $stmt = $conn->prepare("DELETE FROM cover_letters WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);

    // Catat aktivitas penghapusan
    $logQuery = "INSERT INTO activity_logs (user_id, activity_type, activity_id, action, details) 
                 VALUES (?, 'cover_letter', ?, 'delete', 'Cover letter telah dihapus')";
    $stmt = $conn->prepare($logQuery);
    $stmt->execute([$user_id, $id]);

    // Commit transaksi
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Cover letter berhasil dihapus'
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
