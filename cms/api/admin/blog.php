<?php
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/auth.php';
require __DIR__ . '/../../../shared/migrate.php'; // slugify()
require_admin();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $posts = $pdo->query(
        'SELECT p.id, p.slug, p.title, p.excerpt, p.seo_title AS seoTitle, p.seo_description AS seoDescription,
                p.body, p.cover_img AS coverImg, p.published,
                p.published_at AS publishedAt, p.sort_order AS sortOrder,
                p.created_at AS createdAt, p.updated_at AS updatedAt,
                cu.username AS createdByName, uu.username AS updatedByName
         FROM blog_posts p
         LEFT JOIN admin_users cu ON cu.id = p.created_by
         LEFT JOIN admin_users uu ON uu.id = p.updated_by
         ORDER BY p.published_at DESC, p.id DESC'
    )->fetchAll();
    json_out(['posts' => $posts]);
}

if ($method === 'POST') {
    $b = read_json_body();
    $title = trim((string) ($b['title'] ?? ''));
    if ($title === '') {
        json_out(['error' => 'Title is required.'], 400);
    }
    // Slug generated once here at creation — never regenerated on edit (see admin/projects.php
    // for why: re-slugging on every title edit breaks any link that's already been shared).
    $slug = slugify($title);
    $maxSort = (int) ($pdo->query('SELECT COALESCE(MAX(sort_order),0) AS m FROM blog_posts')->fetch()['m']);
    $adminId = current_admin_id();
    $stmt = $pdo->prepare(
        'INSERT INTO blog_posts (slug, title, excerpt, seo_title, seo_description, body, cover_img, published, published_at, sort_order, created_by, updated_by)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $slug,
        $title,
        (string) ($b['excerpt'] ?? ''),
        trim((string) ($b['seoTitle'] ?? '')) ?: null,
        trim((string) ($b['seoDescription'] ?? '')) ?: null,
        (string) ($b['body'] ?? ''),
        (string) ($b['coverImg'] ?? ''),
        (int) ($b['published'] ?? 1),
        (string) ($b['publishedAt'] ?? date('Y-m-d')),
        $maxSort + 1,
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
    $stmt = $pdo->prepare(
        'UPDATE blog_posts SET title=?, excerpt=?, seo_title=?, seo_description=?, body=?, cover_img=?, published=?, published_at=?, updated_by=? WHERE id=?'
    );
    $stmt->execute([
        (string) ($b['title'] ?? ''),
        (string) ($b['excerpt'] ?? ''),
        trim((string) ($b['seoTitle'] ?? '')) ?: null,
        trim((string) ($b['seoDescription'] ?? '')) ?: null,
        (string) ($b['body'] ?? ''),
        (string) ($b['coverImg'] ?? ''),
        (int) ($b['published'] ?? 1),
        (string) ($b['publishedAt'] ?? date('Y-m-d')),
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
    $stmt = $pdo->prepare('DELETE FROM blog_posts WHERE id = ?');
    $stmt->execute([$id]);
    json_out(['ok' => true]);
}

json_out(['error' => 'Method not allowed.'], 405);
