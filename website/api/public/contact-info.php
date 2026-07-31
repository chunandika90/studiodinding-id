<?php
/** GET → { whatsapp, email, address, instagramUrl } shown in the homepage contact section. */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/helpers.php';
no_cache_headers();

$row = $pdo->query('SELECT whatsapp, email, address, instagram_url AS instagramUrl FROM contact_info WHERE id = 1')->fetch();

json_out($row ?: ['whatsapp' => '', 'email' => '', 'address' => '', 'instagramUrl' => '']);
