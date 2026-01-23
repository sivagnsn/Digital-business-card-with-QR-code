<?php
/**
 * vCard Generator API
 * Generates downloadable vCard (.vcf) files from database
 */

require_once __DIR__ . '/../db_config.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : null;
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

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
    
    // Build vCard content
    $vcard = "BEGIN:VCARD\r\n";
    $vcard .= "VERSION:3.0\r\n";
    $vcard .= "N:{$card['last_name']};{$card['first_name']};;;\r\n";
    $vcard .= "FN:{$card['first_name']} {$card['last_name']}\r\n";
    $vcard .= "ORG:{$card['company']}\r\n";
    $vcard .= "TITLE:{$card['title']}\r\n";
    $vcard .= "TEL;TYPE=WORK,VOICE:{$card['phone']}\r\n";
    
    if (!empty($card['phone2'])) {
        $vcard .= "TEL;TYPE=CELL:{$card['phone2']}\r\n";
    }
    
    $vcard .= "EMAIL:{$card['email']}\r\n";
    
    if (!empty($card['email2'])) {
        $vcard .= "EMAIL;TYPE=HOME:{$card['email2']}\r\n";
    }
    
    if (!empty($card['address'])) {
        $vcard .= "ADR;TYPE=WORK:;;{$card['address']}\r\n";
    }
    
    if (!empty($card['website'])) {
        $website = $card['website'];
        if (!preg_match('/^https?:\/\//', $website)) {
            $website = 'https://' . $website;
        }
        $vcard .= "URL:{$website}\r\n";
    }
    
    if (!empty($card['photo_url'])) {
        $vcard .= "PHOTO;TYPE=JPEG;VALUE=URI:{$card['photo_url']}\r\n";
    }
    
    $vcard .= "END:VCARD\r\n";
    
    // Generate filename
    $filename = strtolower(str_replace(' ', '_', "{$card['first_name']}_{$card['last_name']}_business_card.vcf"));
    
    // Set headers for download
    header('Content-Type: text/vcard; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($vcard));
    
    echo $vcard;
    
} catch (PDOException $e) {
    http_response_code(500);
    die('Database error: ' . $e->getMessage());
}
?>
