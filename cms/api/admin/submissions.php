<?php
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/auth.php';
require_admin();

$method = $_SERVER['REQUEST_METHOD'];
$validStatuses = ['new', 'in_progress', 'closed'];

if ($method === 'GET') {
    // Optional period filter: ?from=YYYY-MM-DD&to=YYYY-MM-DD (inclusive both ends).
    $from = $_GET['from'] ?? '';
    $to = $_GET['to'] ?? '';
    $where = [];
    $params = [];
    if ($from !== '') {
        $where[] = 'created_at >= ?';
        $params[] = $from . ' 00:00:00';
    }
    if ($to !== '') {
        $where[] = 'created_at <= ?';
        $params[] = $to . ' 23:59:59';
    }
    $sql = "SELECT id, name, email, message, attachment, status,
                   DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') AS createdAt, is_read AS isRead
            FROM contact_submissions";
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY created_at DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    json_out(['submissions' => $stmt->fetchAll()]);
}

if ($method === 'PUT') {
    // Always used when opening a ticket (marks read); optionally also
    // changes its status (new / in_progress / closed) in the same call.
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_out(['error' => 'Missing id.'], 400);
    }
    $body = read_json_body();
    $status = $body['status'] ?? null;

    if ($status !== null) {
        if (!in_array($status, $validStatuses, true)) {
            json_out(['error' => 'Invalid status.'], 400);
        }
        $stmt = $pdo->prepare('UPDATE contact_submissions SET is_read = 1, status = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
    } else {
        $stmt = $pdo->prepare('UPDATE contact_submissions SET is_read = 1 WHERE id = ?');
        $stmt->execute([$id]);
    }
    json_out(['ok' => true]);
}

if ($method === 'DELETE') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_out(['error' => 'Missing id.'], 400);
    }
    $stmt = $pdo->prepare('DELETE FROM contact_submissions WHERE id = ?');
    $stmt->execute([$id]);
    json_out(['ok' => true]);
}

json_out(['error' => 'Method not allowed.'], 405);
