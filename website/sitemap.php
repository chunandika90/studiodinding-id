<?php
/**
 * Auto-generated sitemap listing every static page + every published
 * project/blog post from the database. Served as /sitemap.xml via the
 * .htaccess rewrite rule below.
 */
require __DIR__ . '/../shared/config.php';
header('Content-Type: application/xml; charset=utf-8');

$urls = [
    ['loc' => SITE_URL . '/', 'priority' => '1.0'],
    ['loc' => SITE_URL . '/about', 'priority' => '0.8'],
    ['loc' => SITE_URL . '/journal', 'priority' => '0.7'],
];

$projects = $pdo->query("SELECT slug FROM projects WHERE published = 1")->fetchAll();
foreach ($projects as $p) {
    $urls[] = ['loc' => SITE_URL . '/project/' . urlencode($p['slug']), 'priority' => '0.9'];
}

$posts = $pdo->query("SELECT slug, published_at FROM blog_posts WHERE published = 1")->fetchAll();
foreach ($posts as $p) {
    $urls[] = [
        'loc' => SITE_URL . '/journal/' . urlencode($p['slug']),
        'priority' => '0.6',
        'lastmod' => $p['published_at'],
    ];
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($u['loc']) . "</loc>\n";
    if (!empty($u['lastmod'])) {
        echo '    <lastmod>' . htmlspecialchars($u['lastmod']) . "</lastmod>\n";
    }
    echo '    <priority>' . $u['priority'] . "</priority>\n";
    echo "  </url>\n";
}
echo '</urlset>' . "\n";
