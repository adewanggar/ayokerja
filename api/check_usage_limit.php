<?php
header('Content-Type: application/json');
require_once 'config.php';

// Fungsi untuk mengecek jumlah penggunaan fitur dalam sebulan
function checkUsageLimit($userId, $featureType)
{
    global $conn;

    // Ambil tanggal awal bulan ini
    $startOfMonth = date('Y-m-01 00:00:00');

    // Query untuk menghitung penggunaan fitur bulan ini
    $sql = "SELECT COUNT(*) as usage_count FROM activity_logs 
            WHERE user_id = $userId 
            AND feature_type = '$featureType' 
            AND created_at >= '$startOfMonth'";

    $result = $conn->query($sql);
    $data = $result->fetch(PDO::FETCH_ASSOC);

    return $data['usage_count'];
}

// Fungsi untuk mendapatkan batas penggunaan berdasarkan tipe subscription
function getUsageLimit($subscriptionType)
{
    switch ($subscriptionType) {
        case 'pro':
            return PHP_INT_MAX; // Unlimited
        case 'basic':
            return 10;
        default: // free
            return 3;
    }
}

// Terima parameter dari request
$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$featureType = isset($_GET['feature_type']) ? $_GET['feature_type'] : '';

if (!$userId || !$featureType) {
    echo json_encode([
        'success' => false,
        'error' => 'Parameter tidak lengkap'
    ]);
    exit;
}

try {
    // Ambil subscription type dari database
    $sql = "SELECT subscription_type FROM users WHERE id = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['userId' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('User tidak ditemukan');
    }

    $subscriptionType = $user['subscription_type'];

    // Cek penggunaan saat ini
    $currentUsage = checkUsageLimit($userId, $featureType);

    // Dapatkan batas penggunaan
    $usageLimit = getUsageLimit($subscriptionType);

    // Cek apakah masih dalam batas
    $canUseFeature = false;

    if ($subscriptionType == 'pro') {
        $canUseFeature = true; // Pro selalu bisa menggunakan fitur
    } else if ($subscriptionType == 'basic') {
        $canUseFeature = $currentUsage < 10; // Basic maksimal 10x
    } else {
        $canUseFeature = $currentUsage < 3; // Free maksimal 3x
    }

    echo json_encode([
        'success' => true,
        'can_use_feature' => $canUseFeature,
        'current_usage' => $currentUsage,
        'usage_limit' => $usageLimit,
        'remaining_usage' => max(0, $usageLimit - $currentUsage),
        'subscription_type' => $subscriptionType
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
