<?php
header('Content-Type: application/json');
require_once '../config/database.php';

// Validasi parameter yang diperlukan
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID terjemahan tidak ditemukan']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Mulai transaksi
    $conn->beginTransaction();

    // Hapus terjemahan
    $query = "DELETE FROM resume_translations WHERE id = ?";
    $stmt = $conn->prepare($query);
    $result = $stmt->execute([$_GET['id']]);

    if (!$result) {
        throw new PDOException('Gagal menghapus terjemahan');
    }

    // Commit transaksi
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Terjemahan berhasil dihapus'
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
