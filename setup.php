<?php
require_once __DIR__ . '/../includes/db.php';

// Create a new admin with a known password
$username = 'Tyler Durden';
$password = 'FIGHT CLUB';
$hash = password_hash($password, PASSWORD_DEFAULT);

// Delete old admin if exists
$pdo->exec("DELETE FROM users WHERE username = 'admin'");

// Insert new admin
$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->execute([$username, $hash]);

echo "✅ Admin user created successfully!<br>";
echo "Username: <b>admin</b><br>";
echo "Password: <b>admin123</b><br>";
echo "<a href='login.php'>Go to Login</a>";
?>