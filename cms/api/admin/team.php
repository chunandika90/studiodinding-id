<?php
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/auth.php';
require_admin();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $team = $pdo->query(
        'SELECT t.id, t.sort_order AS sortOrder, t.name, t.role, t.qualification,
                t.bio_p1 AS bioP1, t.bio_p2 AS bioP2, t.img,
                t.created_at AS createdAt, t.updated_at AS updatedAt,
                cu.username AS createdByName, uu.username AS updatedByName
         FROM team_members t
         LEFT JOIN admin_users cu ON cu.id = t.created_by
         LEFT JOIN admin_users uu ON uu.id = t.updated_by
         ORDER BY t.sort_order ASC'
    )->fetchAll();
    json_out(['team' => $team]);
}

if ($method === 'POST') {
    $b = read_json_body();
    $adminId = current_admin_id();
    $stmt = $pdo->prepare(
        'INSERT INTO team_members (sort_order, name, role, qualification, bio_p1, bio_p2, img, created_by, updated_by)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        (int) ($b['sortOrder'] ?? 0),
        (string) ($b['name'] ?? ''),
        (string) ($b['role'] ?? ''),
        (string) ($b['qualification'] ?? ''),
        (string) ($b['bioP1'] ?? ''),
        (string) ($b['bioP2'] ?? ''),
        (string) ($b['img'] ?? ''),
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
        'UPDATE team_members SET sort_order=?, name=?, role=?, qualification=?, bio_p1=?, bio_p2=?, img=?, updated_by=? WHERE id=?'
    );
    $stmt->execute([
        (int) ($b['sortOrder'] ?? 0),
        (string) ($b['name'] ?? ''),
        (string) ($b['role'] ?? ''),
        (string) ($b['qualification'] ?? ''),
        (string) ($b['bioP1'] ?? ''),
        (string) ($b['bioP2'] ?? ''),
        (string) ($b['img'] ?? ''),
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
    $stmt = $pdo->prepare('DELETE FROM team_members WHERE id = ?');
    $stmt->execute([$id]);
    json_out(['ok' => true]);
}

json_out(['error' => 'Method not allowed.'], 405);
