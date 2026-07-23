<?php
// includes/logger.php
// Logging functions for bot activity

function log_event($pdo, $desc) {
    try {
        $stmt = $pdo->prepare("INSERT INTO logs (event_type, description) VALUES ('event', ?)");
        $stmt->execute([$desc]);
    } catch (Exception $e) {
        error_log("Log error: " . $e->getMessage());
    }
}

function log_error_full($pdo, $msg, $data = []) {
    $desc = $msg;
    if (!empty($data)) {
        $desc .= ' | ' . json_encode($data);
    }
    try {
        $stmt = $pdo->prepare("INSERT INTO logs (event_type, description) VALUES ('error', ?)");
        $stmt->execute([$desc]);
    } catch (Exception $e) {
        error_log("Log error: " . $e->getMessage());
    }
    file_put_contents(__DIR__ . '/../debug.log', date('Y-m-d H:i:s') . " [ERROR] $desc\n", FILE_APPEND);
}

function log_twilio($pdo, $type, $desc) {
    try {
        $stmt = $pdo->prepare("INSERT INTO logs (event_type, description) VALUES (?, ?)");
        $stmt->execute([$type, $desc]);
    } catch (Exception $e) {
        error_log("Log error: " . $e->getMessage());
    }
}

function write_log($pdo, $level, $message) {
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }
    
    $date = date('Y-m-d');
    $time = date('H:i:s');
    $logFile = "$logDir/bot_$date.log";
    $logLine = "[$time] [$level] $message" . PHP_EOL;
    file_put_contents($logFile, $logLine, FILE_APPEND);
    
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO logs (event_type, description) VALUES (?, ?)");
            $stmt->execute([$level, $message]);
        } catch (Exception $e) {}
    }
}
?>
