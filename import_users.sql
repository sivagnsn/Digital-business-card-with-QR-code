-- Import all existing users from PHP files into card_db table
-- Run this in phpMyAdmin

USE It_projects;

-- Insert all users from files folder
INSERT INTO card_db (first_name, last_name, phone, phone2, email, email2, company, title, address, website, slug, is_active) VALUES
('Mayed', 'Alasmawi', '+971 507022228', NULL, 'mayed.alasmawi@ajgroupuae.com', NULL, 
 'Group of Factories Abduljalil Mahdi Mohd Alasmawi LLC', 'Group CEO', 
 'Ras Al Khor - Dubai, UAE', 'www.ajgroupuae.com', 'mayed_alasmawi', 1),

('Ali', 'AlAzazi', '+971 502175656', NULL, 'ali.alazazi@ajgroupuae.com', NULL,
 'Group of Factories Abduljalil Mahdi Mohd Alasmawi LLC', 'Group CFO & COO',
 'Dubai Industrial City, Dubai, UAE', 'www.ajgroupuae.com', 'ali_alazazi', 1),

('Ahmed', 'Farag', '+971 567090509', NULL, 'ahmed.farag@ajgroupuae.com', NULL,
 'Group of Factories Abduljalil Mahdi Mohd Alasmawi LLC', 'Group HR Manager',
 'Ras Al Khor, Dubai, United Arab Emirates', 'www.ajgroupuae.com', 'ahmed_farag', 1),

('Sahad', 'Malliveettil', '+971 542470837', NULL, 'sahad.malliveettil@ajgroupuae.com', NULL,
 'Group of Factories Abduljalil Mahdi Mohd Alasmawi LLC', 'Group IT Manager',
 'Ras Al Khor, Dubai, United Arab Emirates', 'www.ajgroupuae.com', 'sahad_malliveettil', 1),

('Kevin', 'Salins', '+971 501859825', NULL, 'kevin.salins@ajgroupuae.com', NULL,
 'Group of Factories Abduljalil Mahdi Mohd Alasmawi LLC', 'Procurement Manager',
 'Dubai Industrial City, Dubai, UAE', 'www.ajgroupuae.com', 'kevin_salins', 1),

('Kumar', 'SM', '+971 545057757', '+971 551029532', 'jisree.vibishnan@ajgroupuae.com', NULL,
 'AJ Alasmawi Group (Oil & Gas Industries)', 'Sales Manager',
 'Dubai Industrial City - Dubai, UAE', 'www.ajgroupuae.com', 'kumar_sm', 1),

('Sivaganesan', 'Sakthivel', '+971 501776191', NULL, 'sivaganesan.sakthivel@ajgroupuae.com', NULL,
 'Group of Factories Abduljalil Mahdi Mohd Alasmawi LLC', 'IT - Projects Head',
 'DIC, Dubai, United Arab Emirates', 'www.ajgroupuae.com', 'sivaganesan_sakthivel', 1)

ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Verify the import
SELECT id, first_name, last_name, title, slug FROM card_db ORDER BY created_at DESC;
