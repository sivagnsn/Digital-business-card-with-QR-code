-- Run this to add phone2 and email2 columns to existing card_db table
-- Execute in phpMyAdmin

ALTER TABLE card_db 
ADD COLUMN phone2 VARCHAR(50) NULL AFTER phone,
ADD COLUMN email2 VARCHAR(255) NULL AFTER email;
