<?php
/** Master data for project categories (replaces hardcoded residential/commercial). */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/auth.php';
require __DIR__ . '/../../../shared/migrate.php'; // slugify()
require_admin();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $categories = $pdo->query(
        'SELECT id, name, slug, sort_order AS sortOrder FROM project_categories ORDER BY sort_order ASC'
    )->fetchAll();
    json_out(['categories' => $categories]);
}

if ($method === 'POST') {
    $b = read_json_body();
    $name = trim((string) ($b['name'] ?? ''));
    if ($name === '') {
        json_out(['error' => 'Name is required.'], 400);
    }
    $slug = slugify($name);
    $maxSort = (int) ($pdo->query('SELECT COALESCE(MAX(sort_order),0) AS m FROM project_categories')->fetch()['m']);
    $stmt = $pdo->prepare('INSERT INTO project_categories (name, slug, sort_order) VALUES (?, ?, ?)');
    $stmt->execute([$name, $slug, $maxSort + 1]);
    json_out(['ok' => true, 'id' => (int) $pdo->lastInsertId()], 201);
}

if ($method === 'PUT') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_out(['error' => 'Missing id.'], 400);
    }
    $b = read_json_body();
    $name = trim((string) ($b['name'] ?? ''));
    if ($name === '') {
        json_out(['error' => 'Name is required.'], 400);
    }
    $stmt = $pdo->prepare('UPDATE project_categories SET name = ? WHERE id = ?');
    $stmt->execute([$name, $id]);
    json_out(['ok' => true]);
}

if ($method === 'DELETE') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_out(['error' => 'Missing id.'], 400);
    }
    $inUse = $pdo->prepare('SELECT COUNT(*) AS c FROM projects WHERE category_id = ?');
    $inUse->execute([$id]);
    if ((int) $inUse->fetch()['c'] > 0) {
        json_out(['error' => 'Category is still used by one or more projects — reassign them first.'], 400);
    }
    $stmt = $pdo->prepare('DELETE FROM project_categories WHERE id = ?');
    $stmt->execute([$id]);
    json_out(['ok' => true]);
}

json_out(['error' => 'Method not allowed.'], 405);
