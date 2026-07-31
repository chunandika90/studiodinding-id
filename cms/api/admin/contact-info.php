<?php
/** GET current contact details / PUT to update them. Single row, id always 1. */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/auth.php';
require_admin();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $row = $pdo->query('SELECT whatsapp, email, address, instagram_url AS instagramUrl FROM contact_info WHERE id = 1')->fetch();
    json_out($row ?: ['whatsapp' => '', 'email' => '', 'address' => '', 'instagramUrl' => '']);
}

if ($method === 'PUT') {
    $b = read_json_body();
    $stmt = $pdo->prepare(
        'INSERT INTO contact_info (id, whatsapp, email, address, instagram_url) VALUES (1, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE whatsapp = VALUES(whatsapp), email = VALUES(email), address = VALUES(address), instagram_url = VALUES(instagram_url)'
    );
    $stmt->execute([
        (string) ($b['whatsapp'] ?? ''),
        (string) ($b['email'] ?? ''),
        (string) ($b['address'] ?? ''),
        (string) ($b['instagramUrl'] ?? ''),
    ]);
    json_out(['ok' => true]);
}

json_out(['error' => 'Method not allowed.'], 405);
