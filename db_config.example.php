<?php
/**
 * Database Configuration Template
 * Digital Business Card System
 * 
 * INSTRUCTIONS:
 * 1. Copy this file to db_config.php
 * 2. Update the credentials below with your actual database information
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('DB_CHARSET', 'utf8mb4');

// Base URL for card links
define('BASE_URL', 'https://yourdomain.com/card/');

/**
 * Get PDO database connection
 * @return PDO
 */
function getDbConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            );
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
        }
    }
    
    return $pdo;
}

/**
 * Send JSON response
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Generate URL-friendly slug
 */
function generateSlug($firstName, $lastName) {
    $slug = strtolower($firstName . '_' . $lastName);
    $slug = preg_replace('/[^a-z0-9_]/', '', $slug);
    return $slug;
}
?>
