<?php
/**
 * Shared bootstrap, required by every entry point in both website/api/public
 * and cms/api/admin. Defines the central path/URL constants so nothing else
 * ever hardcodes a folder name or domain — see shared/config.local.example.php
 * for what config.local.php (same folder, not committed/shared) must contain.
 */

$localConfigFile = __DIR__ . '/config.local.php';
if (!file_exists($localConfigFile)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'shared/config.local.php is missing. Copy config.local.example.php to config.local.php and fill in your values.',
    ]);
    exit;
}

$localConfig = require $localConfigFile;

define('SITE_URL', rtrim($localConfig['site_url'], '/'));
define('SITE_DIR_BASE', $localConfig['site_dir_base']);
define('CMS_URL', rtrim($localConfig['cms_url'], '/'));
define('CMS_DIR_BASE', $localConfig['cms_dir_base']);
define('UPLOAD_URL_BASE', SITE_URL . '/assets/img/uploads');
define('DEBUG_MODE', (bool) $localConfig['debug_mode']);

if (DEBUG_MODE) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = new PDO(
        "mysql:host={$localConfig['db_host']};dbname={$localConfig['db_name']};charset=utf8mb4",
        $localConfig['db_user'],
        $localConfig['db_pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.' . (DEBUG_MODE ? ' ' . $e->getMessage() : '')]);
    exit;
}

/** Read the JSON body of a POST/PUT request as an associative array. */
function read_json_body(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || $raw === '') {
        return [];
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function json_out($data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data);
    exit;
}
