<?php
header('Content-Type: application/json');
require_once '../config/database.php';

// Fungsi untuk validasi token
function validateToken($token)
{
    global $conn;

    $stmt = $conn->prepare("SELECT user_id FROM user_sessions WHERE token = ? AND last_activity > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return false;
    }

    $row = $result->fetch_assoc();
    return $row['user_id'];
}

// Ambil token dari header atau query parameter
$token = $_GET['token'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$token = str_replace('Bearer ', '', $token);

// Validasi token
$user_id = validateToken($token);
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ambil data profil user dan statistik
    $stmt = $conn->prepare("
        SELECT 
            u.*,
            us.language,
            us.theme,
            us.email_notifications,
            us.browser_notifications,
            (SELECT COUNT(*) FROM resumes WHERE user_id = u.id) as resume_count,
            (SELECT COUNT(*) FROM cover_letters WHERE user_id = u.id) as cover_letter_count,
            (SELECT COUNT(*) FROM interview_sessions WHERE user_id = u.id) as interview_count
        FROM users u 
        LEFT JOIN user_settings us ON u.id = us.user_id 
        WHERE u.id = ?
    ");

    // Debug query
    error_log("User ID: " . $user_id);

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'User not found']);
        exit;
    }

    $user = $result->fetch_assoc();
    unset($user['password']); // Hapus password dari response

    // Debug output
    error_log("Profile data: " . json_encode($user));

    echo json_encode(['success' => true, 'data' => $user]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Terima data dari request
    $data = json_decode(file_get_contents('php://input'), true);

    // Update data user
    $stmt = $conn->prepare("
        UPDATE users 
        SET name = ?, email = ?, current_position = ?, 
            company = ?, bio = ?, updated_at = CURRENT_TIMESTAMP 
        WHERE id = ?
    ");
    $stmt->bind_param(
        "sssssi",
        $data['name'],
        $data['email'],
        $data['current_position'],
        $data['company'],
        $data['bio'],
        $user_id
    );

    $success = $stmt->execute();

    if ($success) {
        // Update user settings
        $stmt = $conn->prepare("
            INSERT INTO user_settings (user_id, language, email_notifications, browser_notifications)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            language = VALUES(language),
            email_notifications = VALUES(email_notifications),
            browser_notifications = VALUES(browser_notifications)
        ");
        $stmt->bind_param(
            "isii",
            $user_id,
            $data['language'],
            $data['email_notifications'],
            $data['browser_notifications']
        );
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update profile']);
    }
}
