<?php
/** GET current About hero content / PUT to update it. Single row, id always 1. */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/auth.php';
require_admin();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $row = $pdo->query('SELECT headline, intro_quote AS intro, img FROM about_hero WHERE id = 1')->fetch();
    json_out($row ?: ['headline' => '', 'intro' => '', 'img' => '']);
}

if ($method === 'PUT') {
    $b = read_json_body();
    $stmt = $pdo->prepare(
        'INSERT INTO about_hero (id, headline, intro_quote, img) VALUES (1, ?, ?, ?)
         ON DUPLICATE KEY UPDATE headline = VALUES(headline), intro_quote = VALUES(intro_quote), img = VALUES(img)'
    );
    $stmt->execute([
        (string) ($b['headline'] ?? ''),
        (string) ($b['intro'] ?? ''),
        (string) ($b['img'] ?? ''),
    ]);
    json_out(['ok' => true]);
}

json_out(['error' => 'Method not allowed.'], 405);
