<?php
/** Partner/brand logos shown in the homepage "Collaborators" section. */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/auth.php';
require_admin();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $collaborators = $pdo->query(
        'SELECT c.id, c.name, c.logo_img AS logoImg, c.url, c.sort_order AS sortOrder,
                c.created_at AS createdAt, c.updated_at AS updatedAt,
                cu.username AS createdByName, uu.username AS updatedByName
         FROM collaborators c
         LEFT JOIN admin_users cu ON cu.id = c.created_by
         LEFT JOIN admin_users uu ON uu.id = c.updated_by
         ORDER BY c.sort_order ASC'
    )->fetchAll();
    json_out(['collaborators' => $collaborators]);
}

if ($method === 'POST') {
    $b = read_json_body();
    $name = trim((string) ($b['name'] ?? ''));
    $logoImg = trim((string) ($b['logoImg'] ?? ''));
    if ($name === '' || $logoImg === '') {
        json_out(['error' => 'Name and logo are required.'], 400);
    }
    $maxSort = (int) ($pdo->query('SELECT COALESCE(MAX(sort_order),0) AS m FROM collaborators')->fetch()['m']);
    $adminId = current_admin_id();
    $stmt = $pdo->prepare('INSERT INTO collaborators (name, logo_img, url, sort_order, created_by, updated_by) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$name, $logoImg, (string) ($b['url'] ?? ''), $maxSort + 1, $adminId, $adminId]);
    json_out(['ok' => true, 'id' => (int) $pdo->lastInsertId()], 201);
}

if ($method === 'PUT') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_out(['error' => 'Missing id.'], 400);
    }
    $b = read_json_body();
    $stmt = $pdo->prepare('UPDATE collaborators SET name=?, logo_img=?, url=?, updated_by=? WHERE id=?');
    $stmt->execute([
        (string) ($b['name'] ?? ''),
        (string) ($b['logoImg'] ?? ''),
        (string) ($b['url'] ?? ''),
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
    $stmt = $pdo->prepare('DELETE FROM collaborators WHERE id = ?');
    $stmt->execute([$id]);
    json_out(['ok' => true]);
}

json_out(['error' => 'Method not allowed.'], 405);
