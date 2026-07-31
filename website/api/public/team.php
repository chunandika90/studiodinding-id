<?php
/** GET → { team: [...] } for the About page. */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/helpers.php';
no_cache_headers();

$team = $pdo->query(
    'SELECT name, role, qualification,
            bio_p1 AS bioP1, bio_p2 AS bioP2, img
     FROM team_members ORDER BY sort_order ASC'
)->fetchAll();
foreach ($team as $i => &$m) {
    $m = ['key' => $i] + $m;
}
unset($m);

json_out(['team' => $team]);
