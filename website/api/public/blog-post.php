<?php
/** GET ?slug=... → single blog post + prev/next for navigation. */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/helpers.php';
no_cache_headers();

$slug = $_GET['slug'] ?? '';
if ($slug === '') {
    json_out(['error' => 'Missing slug.'], 400);
}

$stmt = $pdo->prepare(
    "SELECT id, slug, title, excerpt, body, cover_img AS coverImg,
            DATE_FORMAT(published_at, '%d %b %Y') AS publishedAt, sort_order AS sortOrder
     FROM blog_posts WHERE slug = ? AND published = 1"
);
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    json_out(['error' => 'Post not found.'], 404);
}

$nextStmt = $pdo->prepare(
    'SELECT slug, title FROM blog_posts WHERE published = 1 AND sort_order > ? ORDER BY sort_order ASC LIMIT 1'
);
$nextStmt->execute([$post['sortOrder']]);
$next = $nextStmt->fetch();
if (!$next) {
    $next = $pdo->query('SELECT slug, title FROM blog_posts WHERE published = 1 ORDER BY sort_order ASC LIMIT 1')->fetch();
}
$post['nextPost'] = $next ?: null;
unset($post['id'], $post['sortOrder']);

json_out($post);
