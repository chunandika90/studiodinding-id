<?php
/** GET → { posts: [...] } for the blog listing page. */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/helpers.php';
no_cache_headers();

$posts = $pdo->query(
    "SELECT slug, title, excerpt, cover_img AS coverImg,
            DATE_FORMAT(published_at, '%d %b %Y') AS publishedAt
     FROM blog_posts WHERE published = 1 ORDER BY published_at DESC, id DESC"
)->fetchAll();
foreach ($posts as $i => &$p) {
    $p = ['key' => $i] + $p;
    $p['href'] = 'journal/' . rawurlencode($p['slug']);
}
unset($p);

json_out(['posts' => $posts]);
