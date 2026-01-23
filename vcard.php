<?php
/**
 * Dynamic vCard Redirect
 * Allows access to vCards via friendly URLs like /card/{slug}
 */

// Get the slug from URL
$requestUri = $_SERVER['REQUEST_URI'];
$parts = explode('/', trim($requestUri, '/'));
$slug = end($parts);

// Remove .php extension if present
$slug = str_replace('.php', '', $slug);

// Redirect to API vCard endpoint
if (!empty($slug) && $slug !== 'vcard') {
    header('Location: api/vcard.php?slug=' . urlencode($slug));
    exit;
}

// If no slug, show error
http_response_code(400);
echo "Please specify a card slug. Example: /card/siva";
?>
