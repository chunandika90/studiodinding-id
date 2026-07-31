<?php
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/auth.php';
require __DIR__ . '/../../../shared/migrate.php'; // slugify()
require_admin();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $projects = $pdo->query(
        'SELECT p.id, p.slug, p.name, p.type, p.category_id AS categoryId, c.name AS categoryName,
                p.location, p.services, p.status,
                p.project_type_label AS projectTypeLabel, p.quote,
                p.seo_title AS seoTitle, p.seo_description AS seoDescription,
                p.cover_img AS coverImg,
                p.sort_order AS sortOrder, p.published,
                p.created_at AS createdAt, p.updated_at AS updatedAt,
                cu.username AS createdByName, uu.username AS updatedByName
         FROM projects p
         LEFT JOIN project_categories c ON c.id = p.category_id
         LEFT JOIN admin_users cu ON cu.id = p.created_by
         LEFT JOIN admin_users uu ON uu.id = p.updated_by
         ORDER BY p.sort_order ASC'
    )->fetchAll();
    json_out(['projects' => $projects]);
}

if ($method === 'POST') {
    $b = read_json_body();
    $name = (string) ($b['name'] ?? '');
    if ($name === '') {
        json_out(['error' => 'name is required.'], 400);
    }
    $slug = (string) ($b['slug'] ?? '') ?: slugify($name);
    $categoryId = isset($b['categoryId']) && $b['categoryId'] !== '' ? (int) $b['categoryId'] : null;

    $adminId = current_admin_id();
    $stmt = $pdo->prepare(
        'INSERT INTO projects (slug, name, type, category_id, location, services, status, project_type_label, quote, seo_title, seo_description, cover_img, sort_order, published, created_by, updated_by)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $slug,
        $name,
        (string) ($b['type'] ?? 'residential'),
        $categoryId,
        (string) ($b['location'] ?? 'Indonesia'),
        (string) ($b['services'] ?? 'Architecture & Interior Design'),
        (string) ($b['status'] ?? 'Completed'),
        (string) ($b['projectTypeLabel'] ?? 'Private Residence'),
        (string) ($b['quote'] ?? ''),
        trim((string) ($b['seoTitle'] ?? '')) ?: null,
        trim((string) ($b['seoDescription'] ?? '')) ?: null,
        (string) ($b['coverImg'] ?? ''),
        (int) ($b['sortOrder'] ?? 0),
        (int) ($b['published'] ?? 1),
        $adminId,
        $adminId,
    ]);
    json_out(['ok' => true, 'id' => (int) $pdo->lastInsertId(), 'slug' => $slug], 201);
}

if ($method === 'PUT') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_out(['error' => 'Missing id.'], 400);
    }
    $b = read_json_body();
    $categoryId = isset($b['categoryId']) && $b['categoryId'] !== '' ? (int) $b['categoryId'] : null;
    $stmt = $pdo->prepare(
        'UPDATE projects SET name=?, type=?, category_id=?, location=?, services=?, status=?, project_type_label=?, quote=?, seo_title=?, seo_description=?, cover_img=?, sort_order=?, published=?, updated_by=? WHERE id=?'
    );
    $stmt->execute([
        (string) ($b['name'] ?? ''),
        (string) ($b['type'] ?? 'residential'),
        $categoryId,
        (string) ($b['location'] ?? 'Indonesia'),
        (string) ($b['services'] ?? 'Architecture & Interior Design'),
        (string) ($b['status'] ?? 'Completed'),
        (string) ($b['projectTypeLabel'] ?? 'Private Residence'),
        (string) ($b['quote'] ?? ''),
        trim((string) ($b['seoTitle'] ?? '')) ?: null,
        trim((string) ($b['seoDescription'] ?? '')) ?: null,
        (string) ($b['coverImg'] ?? ''),
        (int) ($b['sortOrder'] ?? 0),
        (int) ($b['published'] ?? 1),
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
    $stmt = $pdo->prepare('DELETE FROM projects WHERE id = ?');
    $stmt->execute([$id]);
    json_out(['ok' => true]);
}

json_out(['error' => 'Method not allowed.'], 405);
