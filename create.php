<?php

$passPath = __DIR__ . '/pass/';
$outputPassFile = __DIR__ . '/businesscard.pkpass';

// Step 1: Create manifest.json
$manifest = [];
$files = ['pass.json', 'icon.png', 'logo.png'];

foreach ($files as $file) {
    $content = file_get_contents($passPath . $file);
    $manifest[$file] = sha1($content);
}

file_put_contents($passPath . 'manifest.json', json_encode($manifest, JSON_UNESCAPED_SLASHES));

// Step 2: Sign the manifest
$p12cert = 'Certificates/certificate.p12'; // Your Pass Type ID certificate
$p12password = '12345';            // Your .p12 password
$wwdrCert = 'Certificates/AppleWWDRCA.pem'; // Apple root cert

// Read p12
openssl_pkcs12_read(file_get_contents($p12cert), $certs, $p12password);
$privateKey = $certs['pkey'];
$certificate = $certs['cert'];

// Create signature
$manifestData = file_get_contents($passPath . 'manifest.json');
openssl_pkcs7_sign(
    'file://' . $passPath . 'manifest.json',
    $passPath . 'signature',
    $certificate,
    [$privateKey, $p12password],
    [],
    PKCS7_BINARY | PKCS7_DETACHED
);

// Convert signature to binary
$signature = file_get_contents($passPath . 'signature');
$begin = strpos($signature, "filename=\"smime.p7s\"");
$signature = substr($signature, $begin);
$signature = substr($signature, strpos($signature, "\n\n") + 2);
file_put_contents($passPath . 'signature', base64_decode($signature));

// Step 3: Create ZIP (.pkpass)
$zip = new ZipArchive;
if ($zip->open($outputPassFile, ZipArchive::CREATE) === TRUE) {
    foreach (array_merge($files, ['manifest.json', 'signature']) as $file) {
        $zip->addFile($passPath . $file, $file);
    }
    $zip->close();
    echo "PKPASS file created: $outputPassFile";
} else {
    echo "Failed to create .pkpass";
}
