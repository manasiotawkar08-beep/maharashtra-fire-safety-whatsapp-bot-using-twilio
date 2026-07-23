<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing Ollama connection...<br><br>";

$url = 'http://localhost:11434/api/generate';
$data = [
   'model' => 'llama3.2:3b',
    'prompt' => 'Say hello in one word.',
    'stream' => false
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);

echo "Sending request...<br>";
$start = time();
$response = curl_exec($ch);
$end = time();
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "Time taken: " . ($end - $start) . " seconds<br>";
echo "HTTP Code: $httpCode<br><br>";

if ($error) {
    echo "<b>cURL Error:</b> $error<br>";
} elseif ($response) {
    $result = json_decode($response, true);
    echo "<b>SUCCESS!</b><br>";
    echo "Response: " . ($result['response'] ?? 'No response field');
} else {
    echo "<b>No response received</b>";
}
?>