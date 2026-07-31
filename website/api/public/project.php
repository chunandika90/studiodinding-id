<?php
/** GET ?slug=bs-residence → project hero/meta fields + gallery array. */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/helpers.php';
no_cache_headers();

$slug = $_GET['slug'] ?? '';
if ($slug === '') {
    json_out(['error' => 'Missing slug.'], 400);
}

$stmt = $pdo->prepare(
    'SELECT p.id, p.slug, p.name, p.type, p.location, p.services, p.status,
            p.project_type_label AS projectTypeLabel, p.quote, p.cover_img AS coverImg, p.sort_order AS sortOrder,
            c.slug AS categorySlug, c.name AS categoryName
     FROM projects p LEFT JOIN project_categories c ON c.id = p.category_id
     WHERE p.slug = ? AND p.published = 1'
);
$stmt->execute([$slug]);
$project = $stmt->fetch();

if (!$project) {
    json_out(['error' => 'Project not found.'], 404);
}

// Next project in display order, wrapping back to the first one at the end —
// same "Selected Work" ordering used on the homepage grid.
$nextStmt = $pdo->prepare(
    'SELECT slug, name, cover_img AS coverImg FROM projects WHERE published = 1 AND sort_order > ? ORDER BY sort_order ASC LIMIT 1'
);
$nextStmt->execute([$project['sortOrder']]);
$next = $nextStmt->fetch();
if (!$next) {
    $next = $pdo->query('SELECT slug, name, cover_img AS coverImg FROM projects WHERE published = 1 ORDER BY sort_order ASC LIMIT 1')->fetch();
}
$project['nextProject'] = $next ?: null;
unset($project['id'], $project['sortOrder']);

$galleryStmt = $pdo->prepare(
    'SELECT img, span, ratio FROM project_gallery
     WHERE project_id = (SELECT id FROM projects WHERE slug = ?)
     ORDER BY sort_order ASC'
);
$galleryStmt->execute([$slug]);
$gallery = $galleryStmt->fetchAll();
foreach ($gallery as $i => &$g) {
    $g = ['key' => $i] + $g;
}
unset($g);

$project['gallery'] = $gallery;

json_out($project);
