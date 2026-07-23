# Maharashtra Fire Safety WhatsApp Bot

A WhatsApp chatbot for Maharashtra Fire Safety information using Twilio + PHP + MySQL + Ollama AI.

## Setup
1. Import `database/fire_bot.sql` into MySQL
2. Update `includes/db.php` with your MySQL password
3. Update `api/twilio_config.php` with Twilio credentials
4. Start Ollama: `ollama serve`
5. Pull model: `ollama pull llama3.1:8b`
6. Start PHP: `php -S localhost:8000`
7. Start ngrok: `ngrok http 8000`
8. Set Twilio webhook to `https://<ngrok-url>/api/webhook.php`  