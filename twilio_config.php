<?php
// api/twilio_config.php
// Twilio WhatsApp messaging functions

function sendWhatsApp($to, $message) {
    $sid   = TWILIO_ACCOUNT_SID;
    $token = TWILIO_AUTH_TOKEN;
    $from  = TWILIO_PHONE_NUMBER;
    $url   = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";
    
    $data = [
        'From' => $from,
        'To'   => $to,
        'Body' => $message
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_USERPWD, "{$sid}:{$token}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_exec($ch);
    curl_close($ch);
}

function sendAndSave($from, $body, $reply, $source) {
    global $pdo;
    try {
        $pdo->prepare("INSERT INTO chat_history (user_phone, message, response, source) VALUES (?,?,?,?)")
            ->execute([$from, $body, $reply, $source]);
    } catch (Exception $e) {
        error_log("Save error: " . $e->getMessage());
    }
    sendWhatsApp($from, $reply);
}
?>
