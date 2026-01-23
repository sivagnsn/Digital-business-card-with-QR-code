<?php
/**
 * Business Cards API
 * Handles CRUD operations for business cards
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../db_config.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

try {
    $pdo = getDbConnection();
    
    switch ($method) {
        case 'GET':
            if ($id) {
                // Get single card
                $stmt = $pdo->prepare("SELECT * FROM card_db WHERE id = ?");
                $stmt->execute(array($id));
                $card = $stmt->fetch();
                
                if ($card) {
                    jsonResponse($card);
                } else {
                    jsonResponse(['error' => 'Card not found'], 404);
                }
            } else {
                // Get all cards
                $stmt = $pdo->query("SELECT * FROM card_db ORDER BY created_at DESC");
                $cards = $stmt->fetchAll();
                jsonResponse($cards);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate required fields
            $required = array('first_name', 'last_name', 'phone', 'email', 'title');
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    jsonResponse(array('error' => "Field '$field' is required"), 400);
                }
            }
            
            // Generate slug
            $slug = generateSlug($data['first_name'], $data['last_name']);
            
            // Check if slug exists, append number if needed
            $baseSlug = $slug;
            $counter = 1;
            while (true) {
                $stmt = $pdo->prepare("SELECT id FROM card_db WHERE slug = ?");
                $stmt->execute(array($slug));
                if (!$stmt->fetch()) break;
                $slug = $baseSlug . '_' . $counter++;
            }
            
            $stmt = $pdo->prepare("
                INSERT INTO card_db 
                (first_name, last_name, phone, phone2, email, email2, company, title, address, website, photo_url, slug, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute(array(
                $data['first_name'],
                $data['last_name'],
                $data['phone'],
                isset($data['phone2']) ? $data['phone2'] : null,
                $data['email'],
                isset($data['email2']) ? $data['email2'] : null,
                isset($data['company']) ? $data['company'] : 'Group of Factories Abduljalil Mahdi Mohd Alasmawi LLC',
                $data['title'],
                isset($data['address']) ? $data['address'] : 'Dubai Industrial City, Dubai, UAE',
                isset($data['website']) ? $data['website'] : 'www.ajgroupuae.com',
                isset($data['photo_url']) ? $data['photo_url'] : null,
                $slug,
                isset($data['is_active']) ? (bool)$data['is_active'] : true
            ));
            
            $newId = $pdo->lastInsertId();
            $stmt = $pdo->prepare("SELECT * FROM card_db WHERE id = ?");
            $stmt->execute(array($newId));
            
            jsonResponse($stmt->fetch(), 201);
            break;
            
        case 'PUT':
            if (!$id) {
                jsonResponse(array('error' => 'Card ID is required'), 400);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Build dynamic update query
            $fields = array();
            $values = array();
            $allowed = array('first_name', 'last_name', 'phone', 'phone2', 'email', 'email2', 'company', 'title', 'address', 'website', 'photo_url', 'is_active');
            
            foreach ($allowed as $field) {
                if (isset($data[$field])) {
                    $fields[] = "$field = ?";
                    $values[] = $data[$field];
                }
            }
            
            if (empty($fields)) {
                jsonResponse(array('error' => 'No fields to update'), 400);
            }
            
            // Update slug if name changed
            if (isset($data['first_name']) || isset($data['last_name'])) {
                $stmt = $pdo->prepare("SELECT first_name, last_name FROM card_db WHERE id = ?");
                $stmt->execute(array($id));
                $current = $stmt->fetch();
                
                $firstName = isset($data['first_name']) ? $data['first_name'] : $current['first_name'];
                $lastName = isset($data['last_name']) ? $data['last_name'] : $current['last_name'];
                $newSlug = generateSlug($firstName, $lastName);
                
                // Append id to ensure uniqueness
                $fields[] = "slug = ?";
                $values[] = $newSlug . '_' . $id;
            }
            
            $values[] = $id;
            $sql = "UPDATE card_db SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);
            
            // Return updated card
            $stmt = $pdo->prepare("SELECT * FROM card_db WHERE id = ?");
            $stmt->execute(array($id));
            jsonResponse($stmt->fetch());
            break;
            
        case 'DELETE':
            if (!$id) {
                jsonResponse(array('error' => 'Card ID is required'), 400);
            }
            
            $stmt = $pdo->prepare("DELETE FROM card_db WHERE id = ?");
            $stmt->execute(array($id));
            
            if ($stmt->rowCount() > 0) {
                jsonResponse(array('message' => 'Card deleted successfully'));
            } else {
                jsonResponse(array('error' => 'Card not found'), 404);
            }
            break;
            
        default:
            jsonResponse(array('error' => 'Method not allowed'), 405);
    }
} catch (PDOException $e) {
    jsonResponse(array('error' => 'Database error: ' . $e->getMessage()), 500);
}
?>
