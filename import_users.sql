-- Import all existing users from PHP files into card_db table
-- Run this in phpMyAdmin
USE It_projects;

-- Insert all users from files folder
INSERT INTO card_db (first_name, last_name, phone, phone2, email, email2, company, title, address, website, slug, is_active) VALUES
('James', 'Anderson', '+971 500000001', NULL, 'james.anderson@example.com', NULL, 
 'Acme Global Holdings LLC', 'Group CEO', 
 'Ras Al Khor - Dubai, UAE', 'www.acmeglobal.com', 'james_anderson', 1),
('Sarah', 'Mitchell', '+971 500000002', NULL, 'sarah.mitchell@example.com', NULL,
 'Acme Global Holdings LLC', 'Group CFO & COO',
 'Dubai Industrial City, Dubai, UAE', 'www.acmeglobal.com', 'sarah_mitchell', 1),
('David', 'Thompson', '+971 500000003', NULL, 'david.thompson@example.com', NULL,
 'Acme Global Holdings LLC', 'Group HR Manager',
 'Ras Al Khor, Dubai, United Arab Emirates', 'www.acmeglobal.com', 'david_thompson', 1),
('Laura', 'Bennett', '+971 500000004', NULL, 'laura.bennett@example.com', NULL,
 'Acme Global Holdings LLC', 'Group IT Manager',
 'Ras Al Khor, Dubai, United Arab Emirates', 'www.acmeglobal.com', 'laura_bennett', 1),
('Robert', 'Clarke', '+971 500000005', NULL, 'robert.clarke@example.com', NULL,
 'Acme Global Holdings LLC', 'Procurement Manager',
 'Dubai Industrial City, Dubai, UAE', 'www.acmeglobal.com', 'robert_clarke', 1),
('Emily', 'Watson', '+971 500000006', '+971 500000007', 'emily.watson@example.com', NULL,
 'Acme Industries (Oil & Gas)', 'Sales Manager',
 'Dubai Industrial City - Dubai, UAE', 'www.acmeglobal.com', 'emily_watson', 1),
('Michael', 'Harris', '+971 500000008', NULL, 'michael.harris@example.com', NULL,
 'Acme Global Holdings LLC', 'IT - Projects Head',
 'DIC, Dubai, United Arab Emirates', 'www.acmeglobal.com', 'michael_harris', 1)
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Verify the import
SELECT id, first_name, last_name, title, slug FROM card_db ORDER BY created_at DESC;
