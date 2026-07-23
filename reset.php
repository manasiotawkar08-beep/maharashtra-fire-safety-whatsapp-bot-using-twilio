<?php
require_once __DIR__ . '/includes/db.php';

$newPassword = 'admin123';
$hash = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
$stmt->execute([$hash]);

echo "Password reset!<br>";
echo "Username: <b>admin</b><br>";
echo "Password: <b>admin123</b><br>";
echo "New hash: " . $hash;
?>