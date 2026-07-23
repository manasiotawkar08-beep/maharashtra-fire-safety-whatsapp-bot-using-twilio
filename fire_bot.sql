CREATE DATABASE IF NOT EXISTS fire_bot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fire_bot;

-- Admin users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE
);

-- Q&A knowledge base
CREATE TABLE IF NOT EXISTS qna (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    question TEXT NOT NULL,
    keywords TEXT,
    answer TEXT NOT NULL,
    language VARCHAR(10) DEFAULT 'en',
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Answer variations for non-repetitive responses
CREATE TABLE IF NOT EXISTS answer_variations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    qna_id INT,
    answer TEXT NOT NULL,
    FOREIGN KEY (qna_id) REFERENCES qna(id) ON DELETE CASCADE
);

-- Chat history
CREATE TABLE IF NOT EXISTS chat_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_phone VARCHAR(30),
    message TEXT,
    response TEXT,
    source ENUM('database','ollama') DEFAULT 'database',
    answer_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Logs
CREATE TABLE IF NOT EXISTS logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin (password: admin123)
INSERT INTO users (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Sample categories
INSERT INTO categories (name, slug) VALUES
('Fire NOC', 'fire-noc'),
('Fire Extinguishers', 'fire-extinguishers'),
('Hydrants', 'hydrants'),
('Emergency Exits', 'emergency-exits'),
('High Rise Buildings', 'high-rise-buildings'),
('SOPs', 'sops'),
('Penalty', 'penalty'),
('Latest Amendments', 'latest-amendments');

-- Sample Q&A
INSERT INTO qna (category_id, question, keywords, answer) VALUES
(1, 'What is Fire NOC?', 'fire noc, noc, no objection certificate', 'Fire NOC is issued after inspection confirming compliance with Maharashtra Fire Prevention and Life Safety Measures Act, 2006.'),
(2, 'How often should fire extinguishers be inspected?', 'extinguisher inspection, maintenance, check', 'Fire extinguishers must be inspected monthly and undergo annual maintenance by a licensed agency.'),
(3, 'What is the required pressure for hydrant systems?', 'hydrant pressure, fire hydrant, bar', 'Hydrant riser pressure should be maintained above 5 Bar as per the ACMS Appendix of the 2025 Amendment Rules.'),
(4, 'How many emergency exits are required in a high-rise building?', 'emergency exits, high rise exits, nbc', 'As per National Building Code, a minimum of two emergency exits are required for high-rise buildings.'),
(5, 'What is ACMS?', 'acms, automated continuous monitoring, iot', 'ACMS stands for Automated Continuous Monitoring System. It is an IoT-based system that continuously monitors fire fighting systems in a building.'),
(8, 'What is IoT in Fire Safety?', 'iot, internet of things, iot meaning, what is iot, iot fire', 'IoT (Internet of Things) in fire safety means connecting fire equipment like pumps, detectors, and alarms to the internet for real-time monitoring. In Maharashtra, this is implemented through ACMS.'),
(8, 'How does IoT help in fire safety?', 'iot benefits, iot advantages, why iot, iot help', 'IoT helps by: 1) Real-time monitoring of fire equipment, 2) Instant SMS/email alerts if systems fail, 3) Automatic maintenance tracking, 4) Cloud storage of all safety data, 5) Remote access for fire departments.'),
(6, 'What is Form A-1?', 'form a-1, certificate, acms compliance', 'Form A-1 is the certificate issued by a Licensed Agency confirming initial compliance of ACMS after installation.'),
(6, 'What is Form B-1?', 'form b-1, certificate, half yearly, maintenance', 'Form B-1 is a half-yearly certificate (January and July) confirming that ACMS is maintained in good repair and efficient working condition.'),
(6, 'How often should fire drills be conducted?', 'fire drill, sop, emergency drill', 'Fire drills should be conducted at least twice a year for all building occupants as per Maharashtra Fire Safety SOPs.'),
(7, 'What is the penalty for non-compliance?', 'penalty, fine, punishment, non-compliance', 'Under the Maharashtra Fire Prevention Act, authorities can levy fines, seal premises, or initiate legal action for non-compliance.'),
(8, 'What changed in the 2025 amendment?', 'amendment, 2025, changes, new rules', 'The 2025 amendment introduced ACMS (IoT monitoring), Forms A-1 and B-1, Fire & Life Safety Auditors, and extended license validity to two years.');

-- ============================================
-- ANSWER VARIATIONS (5 per question)
-- ============================================

-- Fire NOC (qna_id = 1)
INSERT INTO answer_variations (qna_id, answer) VALUES
(1, 'Fire NOC is a No Objection Certificate issued by the Fire Department after verifying your building meets all fire safety norms under the Maharashtra Fire Prevention Act.'),
(1, 'A Fire NOC confirms your building complies with fire safety regulations. You need it before occupying any commercial or high-rise building in Maharashtra.'),
(1, 'Think of Fire NOC as a safety clearance — it proves your building has working fire extinguishers, alarms, hydrants, and emergency exits as per Maharashtra rules.'),
(1, 'Fire NOC is an official document from the Maharashtra Fire Department stating your building is safe from fire hazards. Mandatory for all commercial and high-rise buildings.'),
(1, 'Getting a Fire NOC means the fire department has inspected your premises and verified all fire prevention and life safety measures are in place as per the 2006 Act.');

-- Fire Extinguishers (qna_id = 2)
INSERT INTO answer_variations (qna_id, answer) VALUES
(2, 'Fire extinguishers must be inspected every month visually and serviced annually by a licensed agency. The 2025 amendment enforces this through ACMS digital tracking.'),
(2, 'Check your extinguishers monthly for proper pressure and damage. Every year, a certified agency must do a full service. This is now monitored digitally under the new rules.'),
(2, 'Monthly visual checks + annual professional service = mandatory for all fire extinguishers in Maharashtra. Dont skip this — penalties apply under the Fire Prevention Act.'),
(2, 'The rule is simple: inspect monthly, service yearly. The ACMS system helps track these maintenance schedules automatically and alerts you when service is due.'),
(2, 'Every extinguisher needs a monthly once-over and an annual deep service by licensed professionals. The new IoT monitoring makes compliance tracking effortless.');

-- Hydrant Pressure (qna_id = 3)
INSERT INTO answer_variations (qna_id, answer) VALUES
(3, 'Hydrant riser pressure must stay above 5 Bar. The ACMS continuously monitors this and alerts you and the fire department if it drops.'),
(3, '5 Bar is the minimum pressure required in hydrant systems. IoT sensors track this 24/7 and trigger alarms if the pressure falls below the safe threshold.'),
(3, 'Maintaining 5 Bar pressure in your hydrant system is critical. The new ACMS watches this parameter every minute and sends real-time alerts if there is any drop.'),
(3, 'Your hydrant system needs at least 5 Bar pressure at all times. Thanks to ACMS monitoring, you will know instantly if the pressure dips — no more manual checks needed.'),
(3, 'A hydrant without pressure is useless in a fire. That is why the 2025 amendment mandates 5 Bar minimum pressure, monitored continuously by IoT-based ACMS.');

-- Emergency Exits (qna_id = 4)
INSERT INTO answer_variations (qna_id, answer) VALUES
(4, 'As per National Building Code, every high-rise needs at least two emergency exits. The exact number depends on the occupancy load and floor area.'),
(4, 'Minimum two emergency exits are mandatory for high-rise buildings. They must be clearly marked, illuminated, and kept unlocked during occupancy hours.'),
(4, 'Two exits minimum — that is the NBC rule for high-rises. More may be required based on how many people occupy the building and the floor layout.'),
(4, 'Every high-rise building in Maharashtra must have at least two emergency exits. These exits must be free of obstructions and have backup lighting.'),
(4, 'The law requires two emergency exits minimum in high-rise buildings. Both must open outwards, have panic bars, and be connected to fire escape routes.');

-- ACMS (qna_id = 5)
INSERT INTO answer_variations (qna_id, answer) VALUES
(5, 'ACMS stands for Automated Continuous Monitoring System — an IoT platform that keeps an eye on your fire equipment 24/7 and sends alerts if anything malfunctions.'),
(5, 'ACMS is like a digital watchdog for fire safety. It uses sensors and cloud technology to monitor pumps, tanks, and alarms in real time, as mandated by the 2025 amendment.'),
(5, 'The Automated Continuous Monitoring System connects all your fire fighting equipment to the internet. It checks everything every minute and alerts you instantly if there is a problem.'),
(5, 'ACMS = your building fire safety on autopilot. Sensors track hydrant pressure, pump status, water levels, and more — all reported to a cloud dashboard in real time.'),
(5, 'Under the 2025 Maharashtra amendment, ACMS is now mandatory. It uses IoT sensors to continuously verify that fire fighting systems are in good working condition.');

-- IoT (qna_id = 6)
INSERT INTO answer_variations (qna_id, answer) VALUES
(6, 'IoT means connecting fire safety equipment to the internet. In Maharashtra, the ACMS system uses IoT sensors to monitor pumps, water tanks, and fire alarms continuously. If anything goes wrong, it sends alerts.'),
(6, 'Think of IoT as a smart watchdog for fire safety. It connects all fire equipment to a cloud platform that watches them 24/7. Maharashtra 2025 amendment makes this mandatory via ACMS.'),
(6, 'IoT (Internet of Things) in fire safety = sensors on every fire device sending data to the cloud. This lets building owners and fire departments monitor safety in real time.'),
(6, 'IoT transforms fire safety from reactive to proactive. Instead of waiting for an inspection, sensors monitor your fire systems every minute and alert you before problems become disasters.'),
(6, 'In simple terms, IoT for fire safety means your fire equipment is always online. Pumps, tanks, alarms — all connected and monitored. ACMS is Maharashtra implementation of this technology.');