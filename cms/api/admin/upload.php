<?php
/** POST multipart/form-data, field "file" → saves into the site's uploads folder, returns { url } (absolute). */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/auth.php';
require __DIR__ . '/../../../shared/upload.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(['error' => 'Method not allowed.'], 405);
}

if (!isset($_FILES['file'])) {
    json_out(['error' => 'No file uploaded.'], 400);
}

$result = handle_image_upload($_FILES['file']);
if (!$result['ok']) {
    json_out(['error' => $result['error']], 400);
}

json_out(['ok' => true, 'url' => $result['url']]);
