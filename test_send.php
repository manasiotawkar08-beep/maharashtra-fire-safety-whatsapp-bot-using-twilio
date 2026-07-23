<?php
require_once __DIR__ . '/includes/config.php';

echo "Testing Twilio Send...<br><br>";

echo "Account SID: " . substr(TWILIO_ACCOUNT_SID, 0, 10) . "...<br>";
echo "From: " . TWILIO_PHONE_NUMBER . "<br>";
echo "To: whatsapp:+917039598009<br><br>";

$to = "whatsapp:+917039598009";
$message = "Test message from bot at " . date('H:i:s');

$url = "https://api.twilio.com/2010-04-01/Accounts/" . TWILIO_ACCOUNT_SID . "/Messages.json";
$data = [
    'From' => TWILIO_PHONE_NUMBER,
    'To' => $to,
    'Body' => $message
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_USERPWD, TWILIO_ACCOUNT_SID . ":" . TWILIO_AUTH_TOKEN);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode<br><br>";
echo "Response:<br><pre>";
print_r(json_decode($result, true));
echo "</pre>";

if ($httpCode == 201) {
    echo "<br><b>✅ Message sent successfully! Check your WhatsApp.</b>";
} else {
    echo "<br><b>❌ Failed to send message.</b>";
    $error = json_decode($result, true);
    echo "<br>Error: " . ($error['message'] ?? 'Unknown');
}
?>