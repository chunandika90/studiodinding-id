<?php
/**
 * POST { module: 'homepage'|'projects'|'team', confirm: bool }
 * confirm=false (or omitted) → dry-run, returns counts/summary only, no DB writes.
 * confirm=true → actually upserts. Existing rows are matched by natural key
 * (slide position, project slug, team member name) and UPDATED, not skipped —
 * new ones are inserted. Safe to re-run any time the live .dc.html files change.
 */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/auth.php';
require __DIR__ . '/../../../shared/migrate.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(['error' => 'Method not allowed.'], 405);
}

$body = read_json_body();
$module = (string) ($body['module'] ?? '');
$confirm = !empty($body['confirm']);

if (!in_array($module, ['homepage', 'projects', 'team'], true)) {
    json_out(['error' => 'Unknown module.'], 400);
}

if ($module === 'homepage') {
    $src = read_site_file('index.html');
    if ($src === null) {
        json_out(['error' => 'Could not find index.html (or Studio Dinding Homepage.dc.html) in the site folder.'], 404);
    }
    $block = extract_js_assignment($src, 'slides0');
    $slides = $block ? array_map('parse_js_object_fields', split_js_objects($block)) : [];

    if (!$confirm) {
        json_out([
            'ok' => true,
            'preview' => true,
            'count' => count($slides),
            'items' => array_map(fn($s) => ($s['titleA'] ?? '') . ' ' . ($s['titleB'] ?? ''), $slides),
        ]);
    }

    // Fetch existing slide ids once, ordered — avoids a parameterized LIMIT/OFFSET
    // per row, which some PDO/MySQL configs (native prepares, no emulation)
    // reject outright since MySQL's binary protocol wants LIMIT/OFFSET as literal
    // integers, not bound params.
    $existingSlides = $pdo->query('SELECT id, img FROM slides ORDER BY sort_order ASC')->fetchAll();

    $updated = 0;
    $created = 0;
    foreach ($slides as $i => $s) {
        if (isset($existingSlides[$i])) {
            $currentIsUpload = strpos((string) $existingSlides[$i]['img'], UPLOAD_URL_BASE) === 0;
            $img = $currentIsUpload ? $existingSlides[$i]['img'] : to_absolute_site_url($s['img'] ?? '');
            $stmt = $pdo->prepare('UPDATE slides SET img=?, title_a=?, title_b=?, subtitle=?, sort_order=? WHERE id=?');
            $stmt->execute([$img, $s['titleA'] ?? '', $s['titleB'] ?? '', $s['sub'] ?? '', $i, $existingSlides[$i]['id']]);
            $updated++;
        } else {
            $stmt = $pdo->prepare('INSERT INTO slides (sort_order, img, title_a, title_b, subtitle) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$i, to_absolute_site_url($s['img'] ?? ''), $s['titleA'] ?? '', $s['titleB'] ?? '', $s['sub'] ?? '']);
            $created++;
        }
    }
    json_out(['ok' => true, 'preview' => false, 'updated' => $updated, 'created' => $created, 'total' => count($slides)]);
}

if ($module === 'projects') {
    $files = list_project_files();
    $parsed = [];
    foreach ($files as $path) {
        $src = file_get_contents($path);
        $projBlock = extract_js_assignment($src, 'project0');
        $galBlock = extract_js_assignment($src, 'gallery0');
        if (!$projBlock) {
            continue;
        }
        $proj = parse_js_object_fields($projBlock);
        $gallery = $galBlock ? array_map('parse_js_object_fields', split_js_objects($galBlock)) : [];
        $proj['coverImg'] = to_absolute_site_url(extract_first_background_image($src) ?? ($gallery[0]['img'] ?? ''));
        $parsed[] = ['project' => $proj, 'gallery' => $gallery];
    }

    if (!$confirm) {
        json_out([
            'ok' => true,
            'preview' => true,
            'count' => count($parsed),
            'items' => array_map(fn($p) => ($p['project']['name'] ?? '?') . ' (' . count($p['gallery']) . ' photos)', $parsed),
        ]);
    }

    $updated = 0;
    $created = 0;
    $maxSort = (int) ($pdo->query('SELECT COALESCE(MAX(sort_order),0) AS m FROM projects')->fetch()['m']);
    foreach ($parsed as $p) {
        $proj = $p['project'];
        $slug = $proj['slug'] ?? slugify($proj['name'] ?? '');
        if ($slug === '') {
            continue;
        }
        $existing = $pdo->prepare('SELECT id, cover_img FROM projects WHERE slug = ?');
        $existing->execute([$slug]);
        $row = $existing->fetch();

        if ($row) {
            // Keep whatever's there if it was uploaded manually via the dashboard;
            // otherwise it's a leftover plain relative path from the original seed
            // (or a previous buggy migrate run) — refresh it to an absolute URL.
            $currentIsUpload = strpos((string) $row['cover_img'], UPLOAD_URL_BASE) === 0;
            $coverImg = $currentIsUpload ? $row['cover_img'] : $proj['coverImg'];

            $stmt = $pdo->prepare(
                'UPDATE projects SET name=?, type=?, location=?, services=?, status=?, project_type_label=?, quote=?, cover_img=? WHERE id=?'
            );
            $stmt->execute([
                $proj['name'] ?? '', $proj['type'] ?? 'residential', $proj['location'] ?? '',
                $proj['services'] ?? '', $proj['status'] ?? 'Completed', $proj['projectTypeLabel'] ?? '',
                $proj['quote'] ?? '', $coverImg, $row['id'],
            ]);
            $projectId = (int) $row['id'];
            $updated++;
        } else {
            $maxSort++;
            $stmt = $pdo->prepare(
                'INSERT INTO projects (slug, name, type, category_id, location, services, status, project_type_label, quote, cover_img, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $coverImg = $proj['coverImg'] ?? '';
            $type = $proj['type'] ?? 'residential';
            $catRow = $pdo->prepare('SELECT id FROM project_categories WHERE slug = ? LIMIT 1');
            $catRow->execute([$type]);
            $categoryId = ($catRow->fetch() ?: [])['id'] ?? null;
            $stmt->execute([
                $slug, $proj['name'] ?? '', $type, $categoryId, $proj['location'] ?? '',
                $proj['services'] ?? '', $proj['status'] ?? 'Completed', $proj['projectTypeLabel'] ?? '',
                $proj['quote'] ?? '', $coverImg, $maxSort,
            ]);
            $projectId = (int) $pdo->lastInsertId();
            $created++;
        }

        // Gallery is fully replaced from source on every migrate run — simplest
        // correct behavior, and gallery rows aren't hand-edited independently
        // of the source file the way e.g. a project's quote might be.
        $del = $pdo->prepare('DELETE FROM project_gallery WHERE project_id = ?');
        $del->execute([$projectId]);
        $insGal = $pdo->prepare('INSERT INTO project_gallery (project_id, img, span, ratio, sort_order) VALUES (?, ?, ?, ?, ?)');
        foreach ($p['gallery'] as $gi => $g) {
            $insGal->execute([$projectId, to_absolute_site_url($g['img'] ?? ''), $g['span'] ?? 'span 1', $g['ratio'] ?? '4/5', $gi]);
        }
    }
    json_out(['ok' => true, 'preview' => false, 'updated' => $updated, 'created' => $created, 'total' => count($parsed)]);
}

if ($module === 'team') {
    $src = read_site_file('About.dc.html');
    if ($src === null) {
        json_out(['error' => 'Could not find About.dc.html in the site folder.'], 404);
    }
    $block = extract_js_assignment($src, 'team0');
    $team = $block ? array_map('parse_js_object_fields', split_js_objects($block)) : [];

    if (!$confirm) {
        json_out([
            'ok' => true,
            'preview' => true,
            'count' => count($team),
            'items' => array_map(fn($m) => $m['name'] ?? '?', $team),
        ]);
    }

    $updated = 0;
    $created = 0;
    foreach ($team as $i => $m) {
        $existing = $pdo->prepare('SELECT id, img FROM team_members WHERE name = ?');
        $existing->execute([$m['name'] ?? '']);
        $row = $existing->fetch();
        if ($row) {
            $currentIsUpload = strpos((string) $row['img'], UPLOAD_URL_BASE) === 0;
            $img = $currentIsUpload ? $row['img'] : to_absolute_site_url($m['img'] ?? '');
            $stmt = $pdo->prepare(
                'UPDATE team_members SET role=?, qualification=?, bio_p1=?, bio_p2=?, img=?, sort_order=? WHERE id=?'
            );
            $stmt->execute([
                $m['role'] ?? '', $m['qualification'] ?? '', $m['bioP1'] ?? '', $m['bioP2'] ?? '',
                $img, $i, $row['id'],
            ]);
            $updated++;
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO team_members (sort_order, name, role, qualification, bio_p1, bio_p2, img) VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $i, $m['name'] ?? '', $m['role'] ?? '', $m['qualification'] ?? '',
                $m['bioP1'] ?? '', $m['bioP2'] ?? '', to_absolute_site_url($m['img'] ?? ''),
            ]);
            $created++;
        }
    }
    json_out(['ok' => true, 'preview' => false, 'updated' => $updated, 'created' => $created, 'total' => count($team)]);
}
