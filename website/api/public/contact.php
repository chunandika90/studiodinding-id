<?php
/**
 * POST multipart/form-data: name, email, message (+ optional file field
 * "attachment") → stores a contact submission for the CMS inbox.
 * multipart, not JSON, because an optional file upload needs $_FILES.
 */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/helpers.php';
require __DIR__ . '/../../../shared/upload.php';
no_cache_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(['error' => 'Method not allowed.'], 405);
}

$name = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

if ($name === '' || $email === '' || $message === '') {
    json_out(['error' => 'Name, email and message are required.'], 400);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_out(['error' => 'Invalid email address.'], 400);
}
if (mb_strlen($name) > 255 || mb_strlen($email) > 255 || mb_strlen($message) > 5000) {
    json_out(['error' => 'Input too long.'], 400);
}

$attachmentUrl = null;
if (!empty($_FILES['attachment']) && $_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {
    $result = handle_attachment_upload($_FILES['attachment']);
    if (!$result['ok']) {
        json_out(['error' => $result['error']], 400);
    }
    $attachmentUrl = $result['url'];
}

$stmt = $pdo->prepare('INSERT INTO contact_submissions (name, email, message, attachment) VALUES (?, ?, ?, ?)');
$stmt->execute([$name, $email, $message, $attachmentUrl]);

json_out(['ok' => true]);
