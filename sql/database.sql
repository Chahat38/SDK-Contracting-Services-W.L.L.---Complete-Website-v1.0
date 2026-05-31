CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) UNIQUE NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `email` VARCHAR(150) DEFAULT NULL,
    `reset_token` VARCHAR(100) DEFAULT NULL,
    `reset_expires` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) UNIQUE NOT NULL,
    `setting_value` TEXT,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `services` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `image` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT DEFAULT 0,
    `status` ENUM('active','hidden') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `projects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `category` VARCHAR(100),
    `image` VARCHAR(255),
    `status` ENUM('active','hidden') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(50),
    `subject` VARCHAR(255),
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE admins ADD COLUMN IF NOT EXISTS `email` VARCHAR(150) DEFAULT NULL;
ALTER TABLE admins ADD COLUMN IF NOT EXISTS `reset_token` VARCHAR(100) DEFAULT NULL;
ALTER TABLE admins ADD COLUMN IF NOT EXISTS `reset_expires` DATETIME DEFAULT NULL;

INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`) VALUES
('whatsapp_enabled', '1'),
('whatsapp_number', '97466927592'),
('whatsapp_message', 'Hello SDK Contracting & Services!'),
('hero_badge', 'Qatars Trusted Contracting and Services Partner'),
('hero_title_line1', 'Qatars Leading'),
('hero_title_line2', 'Contracting Company'),
('hero_subtitle', 'From villa construction to road asphalt, cleaning services, legal advisory, and building materials supply — SDK delivers quality across Qatar.'),
('stat_1_num', '1'),
('stat_1_label', 'Years Active'),
('stat_2_num', '50'),
('stat_2_label', 'Projects Completed'),
('stat_3_num', '8'),
('stat_3_label', 'Services Offered'),
('stat_4_num', '30'),
('stat_4_label', 'Happy Clients'),
('why_title', 'Why Clients Choose SDK in Qatar'),
('why_card_1_icon', '🏗️'),
('why_card_1_title', 'Complete Contracting'),
('why_card_1_desc', 'From villa construction and road works to demolition and excavation — we handle every type of contracting project in Qatar.'),
('why_card_2_icon', '🧹'),
('why_card_2_title', 'Professional Cleaning'),
('why_card_2_desc', 'Residential and commercial cleaning services across Doha — reliable, thorough, and affordable.'),
('why_card_3_icon', '⚖️'),
('why_card_3_title', 'Legal Advisory'),
('why_card_3_desc', 'Expert legal advisory for court cases, contracts, and business matters in Qatar.'),
('why_card_4_icon', '📦'),
('why_card_4_title', 'Materials Supply'),
('why_card_4_desc', 'Supply of dual sand, wash sand, tabook, aggregate, gabbro, backfill, subbase and all building materials in Qatar.');

INSERT IGNORE INTO `services` (`title`, `description`, `sort_order`, `status`) VALUES
('Villa & Building Construction', 'We construct residential villas, apartment buildings, and commercial structures across Qatar to the highest quality standards.', 1, 'active'),
('Earth Leveling', 'Professional earth leveling and land preparation services for construction sites across Qatar.', 2, 'active'),
('Road Asphalt & Subbase', 'Complete road asphalt laying and subbase works for private properties, commercial areas, and government contracts in Qatar.', 3, 'active'),
('Demolition', 'Safe and efficient demolition of buildings and structures across Doha and Qatar, fully compliant with municipality regulations.', 4, 'active'),
('Excavation', 'Expert excavation services for foundations, utilities, and earthworks across Qatar.', 5, 'active'),
('Building Materials Supply', 'Supply of dual sand, wash sand, tabook, aggregate, gabbro, backfill, and subbase material throughout Qatar.', 6, 'active'),
('Cleaning Services', 'Professional residential and commercial cleaning services across Doha. Reliable, thorough, and affordable.', 7, 'active'),
('Legal Advisory', 'Expert legal advisory and representation for court cases, contracts, and business disputes in Qatar.', 8, 'active'),
('Typing & Translation', 'Professional typing and translation services in Arabic and English for legal, commercial, and personal documents in Qatar.', 9, 'active');
