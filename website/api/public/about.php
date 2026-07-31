<?php
/** GET → { headline, intro, img } for the About page hero section. */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/helpers.php';
no_cache_headers();

$row = $pdo->query('SELECT headline, intro_quote AS intro, img FROM about_hero WHERE id = 1')->fetch();

json_out($row ?: ['headline' => '', 'intro' => '', 'img' => '']);
