<?php
/** Manage who can log into this dashboard. GET list / POST create / DELETE remove. */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/auth.php';
require_admin();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $admins = $pdo->query(
        "SELECT id, username, DATE_FORMAT(created_at, '%Y-%m-%d') AS createdAt FROM admin_users ORDER BY created_at ASC"
    )->fetchAll();
    json_out(['admins' => $admins]);
}

if ($method === 'POST') {
    $b = read_json_body();
    $username = trim((string) ($b['username'] ?? ''));
    $password = (string) ($b['password'] ?? '');

    if ($username === '' || $password === '') {
        json_out(['error' => 'Username and password are required.'], 400);
    }
    if (mb_strlen($password) < 8) {
        json_out(['error' => 'Password must be at least 8 characters.'], 400);
    }

    $exists = $pdo->prepare('SELECT id FROM admin_users WHERE username = ?');
    $exists->execute([$username]);
    if ($exists->fetch()) {
        json_out(['error' => 'That username is already taken.'], 400);
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)');
    $stmt->execute([$username, $hash]);
    json_out(['ok' => true, 'id' => (int) $pdo->lastInsertId()], 201);
}

if ($method === 'DELETE') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_out(['error' => 'Missing id.'], 400);
    }
    if ($id === current_admin_id()) {
        json_out(['error' => "You can't delete the account you're logged in as."], 400);
    }

    $count = (int) $pdo->query('SELECT COUNT(*) AS c FROM admin_users')->fetch()['c'];
    if ($count <= 1) {
        json_out(['error' => 'At least one admin account must remain.'], 400);
    }

    $stmt = $pdo->prepare('DELETE FROM admin_users WHERE id = ?');
    $stmt->execute([$id]);
    json_out(['ok' => true]);
}

json_out(['error' => 'Method not allowed.'], 405);
