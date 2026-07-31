<?php
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/auth.php';
require_admin();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $slides = $pdo->query(
        'SELECT s.id, s.sort_order AS sortOrder, s.img, s.title_a AS titleA, s.title_b AS titleB, s.subtitle AS sub,
                s.created_at AS createdAt, s.updated_at AS updatedAt,
                cu.username AS createdByName, uu.username AS updatedByName
         FROM slides s
         LEFT JOIN admin_users cu ON cu.id = s.created_by
         LEFT JOIN admin_users uu ON uu.id = s.updated_by
         ORDER BY s.sort_order ASC'
    )->fetchAll();
    json_out(['slides' => $slides]);
}

if ($method === 'POST') {
    $b = read_json_body();
    $adminId = current_admin_id();
    $stmt = $pdo->prepare(
        'INSERT INTO slides (sort_order, img, title_a, title_b, subtitle, created_by, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        (int) ($b['sortOrder'] ?? 0),
        (string) ($b['img'] ?? ''),
        (string) ($b['titleA'] ?? ''),
        (string) ($b['titleB'] ?? ''),
        (string) ($b['sub'] ?? ''),
        $adminId,
        $adminId,
    ]);
    json_out(['ok' => true, 'id' => (int) $pdo->lastInsertId()], 201);
}

if ($method === 'PUT') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_out(['error' => 'Missing id.'], 400);
    }
    $b = read_json_body();
    $stmt = $pdo->prepare(
        'UPDATE slides SET sort_order = ?, img = ?, title_a = ?, title_b = ?, subtitle = ?, updated_by = ? WHERE id = ?'
    );
    $stmt->execute([
        (int) ($b['sortOrder'] ?? 0),
        (string) ($b['img'] ?? ''),
        (string) ($b['titleA'] ?? ''),
        (string) ($b['titleB'] ?? ''),
        (string) ($b['sub'] ?? ''),
        current_admin_id(),
        $id,
    ]);
    json_out(['ok' => true]);
}

if ($method === 'DELETE') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_out(['error' => 'Missing id.'], 400);
    }
    $stmt = $pdo->prepare('DELETE FROM slides WHERE id = ?');
    $stmt->execute([$id]);
    json_out(['ok' => true]);
}

json_out(['error' => 'Method not allowed.'], 405);
