<?php
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'User ID diperlukan']);
    exit;
}

$userId = $_GET['user_id'];

try {
    $database = new Database();
    $conn = $database->getConnection();

    // Handle POST request untuk update profil
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        // Update data user
        $stmt = $conn->prepare("
            UPDATE users 
            SET name = ?, current_position = ?, 
                company = ?, bio = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        $stmt->execute([
            $data['name'],
            $data['current_position'],
            $data['company'],
            $data['bio'],
            $userId
        ]);

        // Update user settings
        $stmt = $conn->prepare("
            INSERT INTO user_settings (user_id, language, email_notifications, browser_notifications)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            language = VALUES(language),
            email_notifications = VALUES(email_notifications),
            browser_notifications = VALUES(browser_notifications)
        ");
        $stmt->execute([
            $userId,
            $data['language'],
            $data['email_notifications'],
            $data['browser_notifications']
        ]);

        echo json_encode(['success' => true, 'message' => 'Profil berhasil diperbarui']);
        exit;
    }

    // GET request untuk mengambil data profil
    // Ambil data user dan settings
    $stmt = $conn->prepare("
        SELECT 
            u.*,
            us.language,
            us.theme,
            us.email_notifications,
            us.browser_notifications
        FROM users u 
        LEFT JOIN user_settings us ON u.id = us.user_id 
        WHERE u.id = ?
    ");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        http_response_code(404);
        echo json_encode(['error' => 'User tidak ditemukan']);
        exit;
    }

    // Hapus password dari response
    unset($userData['password']);

    // Ambil jumlah resume
    $stmt = $conn->prepare("SELECT COUNT(*) as resume_count FROM resumes WHERE user_id = ?");
    $stmt->execute([$userId]);
    $resumeCount = $stmt->fetch()['resume_count'];

    // Ambil jumlah cover letter
    $stmt = $conn->prepare("SELECT COUNT(*) as cover_letter_count FROM cover_letters WHERE user_id = ?");
    $stmt->execute([$userId]);
    $coverLetterCount = $stmt->fetch()['cover_letter_count'];

    // Ambil jumlah interview practice
    $stmt = $conn->prepare("SELECT COUNT(*) as interview_count FROM interview_sessions WHERE user_id = ?");
    $stmt->execute([$userId]);
    $interviewCount = $stmt->fetch()['interview_count'];

    // Ambil jumlah resume yang diterjemahkan
    $stmt = $conn->prepare("SELECT COUNT(*) as translation_count FROM resume_translations WHERE user_id = ?");
    $stmt->execute([$userId]);
    $translationCount = $stmt->fetch()['translation_count'];

    // Ambil aktivitas terbaru
    $stmt = $conn->prepare("
        SELECT 
            activity_type,
            details,
            created_at
        FROM activity_logs
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 3
    ");
    $stmt->execute([$userId]);
    $recentActivities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'user' => $userData,
        'stats' => [
            'resume_count' => $resumeCount,
            'cover_letter_count' => $coverLetterCount,
            'interview_count' => $interviewCount,
            'translation_count' => $translationCount
        ],
        'recent_activities' => $recentActivities
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Terjadi kesalahan server: ' . $e->getMessage()]);
    exit;
}
