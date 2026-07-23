# Maharashtra Fire Safety WhatsApp Bot

A WhatsApp chatbot for Maharashtra Fire Safety information using **Twilio** + **Ollama AI** + **PHP** + **MySQL**.

**Features**
- Answers fire safety questions via WhatsApp
- Database for instant answers to common questions
- Ollama AI fallback for any unknown question
- Chat history tracking
- Admin panel for managing Q&A

**Tech Stack**
- PHP - Backend webhook
- MySQL - Knowledge base & chat history
- Twilio - WhatsApp messaging
- Ollama - Local AI (Llama 3.2)
- ngrok - Local development tunnel

**Setup**
1. Import `database/fire_bot.sql` into MySQL
2. Update `includes/db.php` with your MySQL password
3. Update `api/twilio_config.php` with Twilio credentials
4. Start Ollama: `ollama serve`
5. Pull model: `ollama pull llama3.1:8b`
6. Start PHP: `php -S localhost:8000`
7. Start ngrok: `ngrok http 8000`
8. Set Twilio webhook to `https://<ngrok-url>/api/webhook.php`

**Project Structure**

```
MAHARASHTRA-FIRE-BOT/
├── admin/
│   ├── dashboard.php        # Main admin control panel
│   ├── login.php            # Admin authentication
│   ├── logout.php           # Session termination
│   ├── logs.php             # System logs viewer
│   ├── qna.php              # Q&A management interface
│   └── setup.php            # System setup wizard
├── api/
│   ├── ollama.php           # Ollama AI API endpoint
│   ├── twilio_config.php    # Twilio configuration
│   ├── twilio_stats.php     # SMS statistics and analytics
│   └── webhook.php          # Webhook handler
├── database/
│   └── fire_bot.sql         # MySQL database schema
├── includes/
│   ├── config.php           # Main configuration file
│   ├── db.php               # Database connection handler
│   └── logger.php           # Logging utilities
├── index.php                # Main entry point
├── Questions.txt            # Predefined Q&A dataset
├── README.md                # This file
├── reset.php                # System reset utility
├── test_ollama.php          # Ollama integration test
├── test_send.php            # SMS sending test
└── test.php                 # General test script
```



**Default Admin Login**
- Username: `admin`
- Password: `admin123`

**Security**
Never commit `includes/config.php` with real credentials. Use placeholder values for Twilio SID, Auth Token, database passwords, and phone numbers. Add `config.php` to `.gitignore` before pushing to version control.






