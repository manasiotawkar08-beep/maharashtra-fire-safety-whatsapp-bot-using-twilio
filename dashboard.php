<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../includes/db.php';

$totalQnA = $pdo->query("SELECT COUNT(*) FROM qna")->fetchColumn();
$totalChats = $pdo->query("SELECT COUNT(*) FROM chat_history")->fetchColumn();
$totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$recentChats = $pdo->query("SELECT * FROM chat_history ORDER BY created_at DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Fire Safety Bot</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial; display: flex; min-height: 100vh; }
        .sidebar { width: 220px; background: #2c3e50; color: white; padding: 20px; }
        .sidebar h3 { margin-bottom: 20px; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 10px 0; }
        .sidebar a:hover { color: #1abc9c; }
        .main { flex: 1; padding: 30px; background: #ecf0f1; }
        .stats { display: flex; gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 25px; border-radius: 10px; flex: 1; text-align: center; font-size: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card span { display: block; font-size: 36px; font-weight: bold; color: #075e54; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; }
        th { background: #075e54; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        tr:hover td { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>🔥 Fire Safety Bot</h3>
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="qna.php">📝 Manage Q&A</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
    <div class="main">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['admin']) ?>!</h2>
        <div class="stats">
            <div class="card">Questions<span><?= $totalQnA ?></span></div>
            <div class="card">Categories<span><?= $totalCategories ?></span></div>
            <div class="card">Conversations<span><?= $totalChats ?></span></div>
        </div>
        <h3>Recent Conversations</h3>
        <table>
            <tr><th>Phone</th><th>Message</th><th>Response</th><th>Source</th><th>Time</th></tr>
            <?php if (count($recentChats) === 0): ?>
            <tr><td colspan="5" style="text-align:center;padding:20px;">No conversations yet</td></tr>
            <?php else: ?>
            <?php foreach ($recentChats as $chat): ?>
            <tr>
                <td><?= htmlspecialchars($chat['user_phone']) ?></td>
                <td><?= htmlspecialchars(substr($chat['message'], 0, 30)) ?></td>
                <td><?= htmlspecialchars(substr($chat['response'], 0, 50)) ?>...</td>
                <td><?= $chat['source'] === 'database' ? '📚 DB' : '🤖 AI' ?></td>
                <td><?= $chat['created_at'] ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>