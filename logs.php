<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$type = $_GET['type'] ?? 'bot';
$date = $_GET['date'] ?? date('Y-m-d');

$logFiles = [
    'bot' => "logs/bot_$date.log",
    'twilio' => "logs/twilio_$date.log", 
    'errors' => "logs/errors_$date.log",
    'stats' => "logs/twilio_stats_$date.log"
];

$logFile = $logFiles[$type] ?? $logFiles['bot'];
$logs = file_exists(__DIR__ . "/../$logFile") ? file_get_contents(__DIR__ . "/../$logFile") : 'No logs for this date.';

// Get available dates
$files = glob(__DIR__ . '/../logs/bot_*.log');
$dates = [];
foreach ($files as $f) {
    preg_match('/bot_(\d{4}-\d{2}-\d{2})\.log/', basename($f), $m);
    if (isset($m[1])) $dates[] = $m[1];
}
rsort($dates);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logs - Fire Safety Bot</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: monospace; display: flex; min-height: 100vh; background: #1a1a2e; color: #00ff00; }
        .sidebar { width: 200px; background: #111; padding: 20px; }
        .sidebar h3 { color: #fff; margin-bottom: 15px; }
        .sidebar a { color: #00ff00; text-decoration: none; display: block; padding: 8px 0; }
        .main { flex: 1; padding: 20px; }
        .filters { margin-bottom: 15px; }
        .filters a { padding: 8px 15px; background: #333; color: #00ff00; text-decoration: none; border-radius: 5px; margin-right: 5px; }
        .filters a.active { background: #00ff00; color: #000; }
        pre { background: #000; padding: 20px; border-radius: 8px; max-height: 80vh; overflow-y: auto; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>📋 Logs</h3>
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="logs.php?type=bot">📝 Bot</a>
        <a href="logs.php?type=twilio">📱 Twilio</a>
        <a href="logs.php?type=errors">❌ Errors</a>
        <a href="logs.php?type=stats">📊 Stats</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
    <div class="main">
        <h2 style="color:#fff;">📋 <?= strtoupper($type) ?> Logs - <?= $date ?></h2>
        <div class="filters">
            <a href="?type=bot&date=<?=$date?>" class="<?=$type=='bot'?'active':''?>">Bot</a>
            <a href="?type=twilio&date=<?=$date?>" class="<?=$type=='twilio'?'active':''?>">Twilio</a>
            <a href="?type=errors&date=<?=$date?>" class="<?=$type=='errors'?'active':''?>">Errors</a>
            <a href="?type=stats&date=<?=$date?>" class="<?=$type=='stats'?'active':''?>">Stats</a>
            <select onchange="location.href='?type=<?=$type?>&date='+this.value" style="margin-left:10px;padding:8px;background:#333;color:#00ff00;border:1px solid #555;">
                <?php foreach ($dates as $d): ?>
                <option value="<?=$d?>" <?=$d==$date?'selected':''?>><?=$d?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <pre><?= htmlspecialchars($logs) ?></pre>
    </div>
</body>
</html>