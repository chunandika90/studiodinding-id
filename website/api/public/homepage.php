<?php
/** GET → { slides: [...], projects: [...] } for the homepage hero carousel + portfolio grid. */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/helpers.php';
no_cache_headers();

$slides = $pdo->query(
    'SELECT img, title_a AS titleA, title_b AS titleB, subtitle AS sub FROM slides ORDER BY sort_order ASC'
)->fetchAll();
foreach ($slides as $i => &$s) {
    $s = ['key' => $i] + $s;
}
unset($s);

$projects = $pdo->query(
    "SELECT p.slug AS `key`, p.name, p.type, p.cover_img AS img, c.slug AS categorySlug, c.name AS categoryName
     FROM projects p LEFT JOIN project_categories c ON c.id = p.category_id
     WHERE p.published = 1 ORDER BY p.sort_order ASC"
)->fetchAll();
foreach ($projects as &$p) {
    // Slug-based, not name-based — stays correct even if the project is renamed later.
    $p['href'] = 'project/' . rawurlencode($p['key']);
}
unset($p);

$categories = $pdo->query(
    'SELECT slug, name FROM project_categories ORDER BY sort_order ASC'
)->fetchAll();

$collaborators = $pdo->query(
    'SELECT name, logo_img AS img, url FROM collaborators ORDER BY sort_order ASC'
)->fetchAll();
foreach ($collaborators as $i => &$col) {
    $col = ['key' => $i] + $col;
}
unset($col);

json_out(['slides' => $slides, 'projects' => $projects, 'categories' => $categories, 'collaborators' => $collaborators]);
