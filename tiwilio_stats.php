<?php
// api/twilio_stats.php
// Twilio usage statistics and monitoring

function checkTwilioBalance() {
    $url = "https://api.twilio.com/2010-04-01/Accounts/" . TWILIO_ACCOUNT_SID . "/Balance.json";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, TWILIO_ACCOUNT_SID . ":" . TWILIO_AUTH_TOKEN);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    return $result['balance'] ?? 'Unknown';
}

function checkTwilioUsage($date = null) {
    $date = $date ?? date('Y-m-d');
    $url = "https://api.twilio.com/2010-04-01/Accounts/" . TWILIO_ACCOUNT_SID . "/Usage/Records/Daily.json?Category=total&StartDate={$date}&EndDate={$date}";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, TWILIO_ACCOUNT_SID . ":" . TWILIO_AUTH_TOKEN);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    return $result['usage_records'][0] ?? null;
}

function getMessageCount($pdo, $date = null) {
    $date = $date ?? date('Y-m-d');
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM chat_history WHERE DATE(created_at) = ?");
    $stmt->execute([$date]);
    return $stmt->fetchColumn();
}

function getActiveUsers($pdo, $days = 7) {
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT user_phone) FROM chat_history WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)");
    $stmt->execute([$days]);
    return $stmt->fetchColumn();
}
?>
