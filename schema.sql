-- Digital Business Card Database Schema
-- Run this script to create the table in your existing database

-- Use existing database
USE It_projects;

-- Business Cards Table
CREATE TABLE IF NOT EXISTS card_db (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    phone2 VARCHAR(50) NULL,
    email VARCHAR(255) NOT NULL,
    email2 VARCHAR(255) NULL,
    company VARCHAR(255) DEFAULT 'Group of Factories Abduljalil Mahdi Mohd Alasmawi LLC',
    title VARCHAR(150) NOT NULL,
    address TEXT,
    website VARCHAR(255) DEFAULT 'www.ajgroupuae.com',
    photo_url VARCHAR(500) NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_slug (slug),
    INDEX idx_active (is_active),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert existing cards from PHP files
INSERT INTO card_db (first_name, last_name, phone, email, company, title, address, website, slug) VALUES
('Sivaganesan', 'Sakthivel', '+971 501776191', 'sivaganesan.sakthivel@ajgroupuae.com', 
 'Group of Factories Abduljalil Mahdi Mohd Alasmawi LLC', 'IT - Projects Head', 
 'DIC, Dubai, United Arab Emirates', 'www.ajgroupuae.com', 'sivaganesan_sakthivel'),

('Kevin', 'Salins', '+971 501859825', 'kevin.salins@ajgroupuae.com',
 'Group of Factories Abduljalil Mahdi Mohd Alasmawi LLC', 'Procurement Manager',
 'Dubai Industrial City, Dubai, UAE', 'www.ajgroupuae.com', 'kevin_salins'),

('Mayed', 'Alasmawi', '+971 506721712', 'mayed.alasmawi@ajgroupuae.com',
 'Group of Factories Abduljalil Mahdi Mohd Alasmawi LLC', 'Director',
 'DIC, Dubai, United Arab Emirates', 'www.ajgroupuae.com', 'mayed_alasmawi')
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;
