<?php
header('Content-Type: application/json');
require_once 'config.php';

function logActivity($userId, $featureType, $details = '')
{
    global $conn;

    $sql = "INSERT INTO activity_logs (user_id, feature_type, details, created_at) 
            VALUES (:userId, :featureType, :details, NOW())";

    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        'userId' => $userId,
        'featureType' => $featureType,
        'details' => $details
    ]);
}

// Terima parameter dari request
$userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$featureType = isset($_POST['feature_type']) ? $_POST['feature_type'] : '';
$details = isset($_POST['details']) ? $_POST['details'] : '';

if (!$userId || !$featureType) {
    echo json_encode([
        'success' => false,
        'error' => 'Parameter tidak lengkap'
    ]);
    exit;
}

try {
    // Cek tipe subscription user
    $sql = "SELECT subscription_type FROM users WHERE id = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['userId' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('User tidak ditemukan');
    }

    // Hitung penggunaan bulan ini
    $startOfMonth = date('Y-m-01 00:00:00');
    $sql = "SELECT COUNT(*) as usage_count FROM activity_logs 
            WHERE user_id = :userId 
            AND feature_type = :featureType 
            AND created_at >= :startOfMonth";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'userId' => $userId,
        'featureType' => $featureType,
        'startOfMonth' => $startOfMonth
    ]);
    $usage = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentUsage = $usage['usage_count'];

    // Cek batasan berdasarkan tipe subscription
    $canUseFeature = false;
    if ($user['subscription_type'] == 'pro') {
        $canUseFeature = true;
    } else if ($user['subscription_type'] == 'basic') {
        $canUseFeature = $currentUsage < 10;
    } else {
        $canUseFeature = $currentUsage < 3;
    }

    if (!$canUseFeature) {
        echo json_encode([
            'success' => false,
            'error' => 'Anda telah mencapai batas penggunaan fitur ini. Silakan upgrade paket Anda untuk menggunakan fitur lebih banyak.',
            'current_usage' => $currentUsage,
            'subscription_type' => $user['subscription_type']
        ]);
        exit;
    }

    // Catat aktivitas
    $success = logActivity($userId, $featureType, $details);

    echo json_encode([
        'success' => $success,
        'message' => $success ? 'Aktivitas berhasil dicatat' : 'Gagal mencatat aktivitas',
        'current_usage' => $currentUsage + 1,
        'subscription_type' => $user['subscription_type']
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
