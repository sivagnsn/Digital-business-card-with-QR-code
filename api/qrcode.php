<?php
/**
 * QR Code Generator API
 * Generates QR codes for business cards pointing to vCard download
 */

require_once __DIR__ . '/../db_config.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : null;
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$size = isset($_GET['size']) ? intval($_GET['size']) : 300;

if (!$slug && !$id) {
    http_response_code(400);
    die('Card identifier (slug or id) is required');
}

try {
    $pdo = getDbConnection();
    
    if ($slug) {
        $stmt = $pdo->prepare("SELECT * FROM card_db WHERE slug = ? AND is_active = 1");
        $stmt->execute(array($slug));
    } else {
        $stmt = $pdo->prepare("SELECT * FROM card_db WHERE id = ? AND is_active = 1");
        $stmt->execute(array($id));
    }
    
    $card = $stmt->fetch();
    
    if (!$card) {
        http_response_code(404);
        die('Business card not found');
    }
    
    // Generate vCard URL
    $vcardUrl = rtrim(BASE_URL, '/') . '/api/vcard.php?slug=' . urlencode($card['slug']);
    
    // Use QR Server API for QR code generation (free and reliable)
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . urlencode($vcardUrl);
    
    // Redirect to QR code image
    header('Location: ' . $qrUrl);
    exit;
    
} catch (PDOException $e) {
    http_response_code(500);
    die('Database error: ' . $e->getMessage());
}
?>
