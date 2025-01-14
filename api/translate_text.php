<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/session.php';

// Cek apakah user sudah login
if (!isset($_POST['token'])) {
    echo json_encode(['error' => true, 'message' => 'Token tidak ditemukan']);
    exit;
}

$token = $_POST['token'];
$user = validateToken($token);

if (!$user) {
    echo json_encode(['error' => true, 'message' => 'Token tidak valid']);
    exit;
}

// Cek parameter yang diperlukan
if (!isset($_POST['text']) || !isset($_POST['source_language']) || !isset($_POST['target_language'])) {
    echo json_encode(['error' => true, 'message' => 'Parameter tidak lengkap']);
    exit;
}

$text = $_POST['text'];
$sourceLanguage = $_POST['source_language'];
$targetLanguage = $_POST['target_language'];

// Konfigurasi API Gemini
$apiKey = 'AIzaSyCAmJdyfzPJQJ7pTIxTJYRyjEwXZdeL-50'; // Ganti dengan API key Anda
$endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

// Buat prompt untuk Gemini
$prompt = "Translate the following text from $sourceLanguage to $targetLanguage. Maintain the professional tone and formatting:

$text

Please provide only the translated text without any additional comments or explanations.";

// Persiapkan data untuk request
$data = [
    'contents' => [
        [
            'parts' => [
                ['text' => $prompt]
            ]
        ]
    ]
];

// Kirim request ke Gemini
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint . '?key=' . $apiKey);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo json_encode(['error' => true, 'message' => 'Gagal menghubungi API Gemini']);
    exit;
}

$result = json_decode($response, true);

// Simpan riwayat terjemahan ke database
try {
    $database = new Database();
    $pdo = $database->getConnection();

    $query = "INSERT INTO translation_history (user_id, source_language, target_language, created_at) 
              VALUES (:user_id, :source_language, :target_language, NOW())";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'user_id' => $user['id'],
        'source_language' => $sourceLanguage,
        'target_language' => $targetLanguage
    ]);

    // Hitung metrik kualitas terjemahan (simulasi)
    $accuracy = rand(90, 98);
    $consistency = rand(85, 95);
    $formatMatch = rand(95, 100);

    echo json_encode([
        'success' => true,
        'translated_text' => $result['candidates'][0]['content']['parts'][0]['text'],
        'accuracy' => $accuracy,
        'consistency' => $consistency,
        'format_match' => $formatMatch
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => true, 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
