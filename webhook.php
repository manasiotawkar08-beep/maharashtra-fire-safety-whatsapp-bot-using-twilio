<?php
// api/webhook.php
// Main WhatsApp webhook handler

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/logger.php';
require_once __DIR__ . '/twilio_config.php';

$from = $_POST['From'] ?? '';
$body = trim($_POST['Body'] ?? '');
if ($from === '' || $body === '') exit;

file_put_contents(__DIR__ . '/../debug.log', date('Y-m-d H:i:s') . " | From: $from | Body: $body\n", FILE_APPEND);
$lowerBody = strtolower($body);

// ========== GREETINGS ==========
$greetings = ['hi','hello','hey','hii','helo','hlo','good morning','good afternoon','good evening','yo','sup','hola','namaste','namaskar'];
if (in_array($lowerBody, $greetings)) {
    $reply = "🔥 *Maharashtra Fire Safety Bot* 🔥\n\nI can answer ANY fire‑safety question!\n\n🚨 Emergencies: 🚒 Fire 101 | 🚑 Ambulance 108";
    sendAndSave($from, $body, $reply, 'database');
    exit;
}

// ========== THANKS ==========
$thanks = ['thanks','thank you','thx','ty','thankyou','dhanyavad','thank u'];
if (in_array($lowerBody, $thanks)) {
    $reply = "😊 You're welcome! Stay safe.";
    sendAndSave($from, $body, $reply, 'database');
    exit;
}

// ========== SEARCH DATABASE ==========
$stmt = $pdo->prepare("SELECT answer FROM qna WHERE status='active' AND (question LIKE ? OR keywords LIKE ?) LIMIT 1");
$stmt->execute(["%$lowerBody%", "%$lowerBody%"]);
$match = $stmt->fetch();

if ($match) {
    sendAndSave($from, $body, $match['answer'], 'database');
    exit;
}

// ========== OLLAMA AI FALLBACK ==========
sendWhatsApp($from, "🤔 *Let me check my knowledge base...*\n\n⏳ This will take just a few seconds.");

require_once __DIR__ . '/ollama.php';
$answer = askOllama($body);
$source = 'ollama';

if (!$answer || strlen(trim($answer)) < 10) {
    $answer = "⚠️ I couldn't find specific information on that.\n\nTry asking about:\n- Fire NOC\n- Fire extinguishers\n- Emergency exits\n- High rise buildings\n\n🚨 Emergencies: 🚒 101 | 🚑 108";
    $source = 'fallback';
}

if (strlen($answer) > 1500) {
    $answer = substr($answer, 0, 1497) . '...';
}

try {
    $pdo->prepare("INSERT INTO chat_history (user_phone, message, response, source) VALUES (?,?,?,?)")
        ->execute([$from, $body, $answer, $source]);
} catch (Exception $e) {}

sendWhatsApp($from, $answer);
file_put_contents(__DIR__ . '/../debug.log', "Reply sent | Source: $source\n\n", FILE_APPEND);
?>
