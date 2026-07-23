<?php
// includes/config.php
// ⚠️ Fill in your own credentials before using

// Database settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'fire_bot');
define('DB_USER', 'your_mysql_username');
define('DB_PASS', 'your_mysql_password');

// Twilio credentials
define('TWILIO_ACCOUNT_SID', 'your_twilio_sid');
define('TWILIO_AUTH_TOKEN', 'your_twilio_auth_token');
define('TWILIO_PHONE_NUMBER', 'whatsapp:+14155238886');

// Ollama
define('OLLAMA_URL', 'http://localhost:11434/api/generate');
define('OLLAMA_MODEL', 'llama3.2:3b');

// Base URL (your ngrok URL)
define('BASE_URL', 'https://your-ngrok-url.ngrok-free.dev');
?>
