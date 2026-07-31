<?php
/** Gallery items for one project. GET/POST use ?projectId=, PUT/DELETE use ?id= (the gallery row's own id). */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/auth.php';
require_admin();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $projectId = (int) ($_GET['projectId'] ?? 0);
    if ($projectId <= 0) {
        json_out(['error' => 'Missing projectId.'], 400);
    }
    $stmt = $pdo->prepare(
        'SELECT id, img, span, ratio, sort_order AS sortOrder
         FROM project_gallery WHERE project_id = ? ORDER BY sort_order ASC'
    );
    $stmt->execute([$projectId]);
    json_out(['gallery' => $stmt->fetchAll()]);
}

if ($method === 'POST') {
    $b = read_json_body();
    $projectId = (int) ($b['projectId'] ?? 0);
    if ($projectId <= 0) {
        json_out(['error' => 'Missing projectId.'], 400);
    }
    $stmt = $pdo->prepare(
        'INSERT INTO project_gallery (project_id, img, span, ratio, sort_order) VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $projectId,
        (string) ($b['img'] ?? ''),
        (string) ($b['span'] ?? 'span 1'),
        (string) ($b['ratio'] ?? '4/5'),
        (int) ($b['sortOrder'] ?? 0),
    ]);
    json_out(['ok' => true, 'id' => (int) $pdo->lastInsertId()], 201);
}

if ($method === 'PUT') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_out(['error' => 'Missing id.'], 400);
    }
    $b = read_json_body();
    $stmt = $pdo->prepare('UPDATE project_gallery SET img=?, span=?, ratio=?, sort_order=? WHERE id=?');
    $stmt->execute([
        (string) ($b['img'] ?? ''),
        (string) ($b['span'] ?? 'span 1'),
        (string) ($b['ratio'] ?? '4/5'),
        (int) ($b['sortOrder'] ?? 0),
        $id,
    ]);
    json_out(['ok' => true]);
}

if ($method === 'DELETE') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_out(['error' => 'Missing id.'], 400);
    }
    $stmt = $pdo->prepare('DELETE FROM project_gallery WHERE id = ?');
    $stmt->execute([$id]);
    json_out(['ok' => true]);
}

json_out(['error' => 'Method not allowed.'], 405);
