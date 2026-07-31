<?php
require __DIR__ . '/../../../shared/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(['error' => 'Method not allowed.'], 405);
}

$body = read_json_body();
$username = trim((string) ($body['username'] ?? ''));
$password = (string) ($body['password'] ?? '');

if ($username === '' || $password === '') {
    json_out(['error' => 'Username and password are required.'], 400);
}

$stmt = $pdo->prepare('SELECT id, password_hash FROM admin_users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    json_out(['error' => 'Invalid username or password.'], 401);
}

session_regenerate_id(true);
$_SESSION['admin_id'] = (int) $user['id'];

json_out(['ok' => true]);
