<?php
echo "Server is working!<br>";
echo "PHP Version: " . phpversion() . "<br>";

// Test database
require_once __DIR__ . '/includes/config.php';
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    echo "Database: Connected!<br>";
} catch (Exception $e) {
    echo "Database Error: " . $e->getMessage() . "<br>";
}

// Test Ollama
echo "Ollama URL: " . OLLAMA_API . "<br>";
?>