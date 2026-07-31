<?php
/**
 * Reads the live .dc.html files straight off disk (same server, same cPanel
 * account — see shared/upload.php for the same cross-folder trick) and parses
 * the hardcoded JS object/array literals out of each `data-dc-script` block.
 * Used by cms/api/admin/migrate.php to (re)import homepage slides, projects
 * + galleries, and team members from whatever is actually live on
 * new.studiodinding.id, instead of trusting a one-time manual seed forever.
 */

function site_dir(): string
{
    return __DIR__ . '/../' . SITE_DIR_BASE;
}

function read_site_file(string $filename): ?string
{
    $candidates = [$filename];
    // The homepage file may or may not have been renamed to index.html yet —
    // try both so this keeps working either way.
    if ($filename === 'index.html') {
        $candidates[] = 'Studio Dinding Homepage.dc.html';
    }
    foreach ($candidates as $name) {
        $path = site_dir() . '/' . $name;
        if (is_file($path)) {
            return file_get_contents($path);
        }
    }
    return null;
}

/** List "Project - *.dc.html" files present in the site folder. */
function list_project_files(): array
{
    $files = glob(site_dir() . '/Project - *.dc.html') ?: [];
    sort($files);
    return $files;
}

/**
 * Extract the contents of `name = [ ... ]` or `name = { ... }` (a single
 * top-level bracket/brace pair, depth-matched so nested braces inside string
 * values or object fields don't confuse it).
 */
function extract_js_assignment(string $src, string $varName): ?string
{
    if (!preg_match('/\b' . preg_quote($varName, '/') . '\s*=\s*(\[|\{)/', $src, $m, PREG_OFFSET_CAPTURE)) {
        return null;
    }
    $openChar = $m[1][0];
    $closeChar = $openChar === '[' ? ']' : '}';
    $start = $m[1][1];
    $depth = 0;
    for ($i = $start; $i < strlen($src); $i++) {
        $c = $src[$i];
        if ($c === $openChar) {
            $depth++;
        } elseif ($c === $closeChar) {
            $depth--;
            if ($depth === 0) {
                return substr($src, $start, $i - $start + 1);
            }
        }
    }
    return null;
}

/** Split a `[ {...}, {...} ]` block into its individual `{...}` object strings. */
function split_js_objects(string $arrayBlock): array
{
    $objects = [];
    $depth = 0;
    $start = null;
    for ($i = 0; $i < strlen($arrayBlock); $i++) {
        $c = $arrayBlock[$i];
        if ($c === '{') {
            if ($depth === 0) {
                $start = $i;
            }
            $depth++;
        } elseif ($c === '}') {
            $depth--;
            if ($depth === 0 && $start !== null) {
                $objects[] = substr($arrayBlock, $start, $i - $start + 1);
                $start = null;
            }
        }
    }
    return $objects;
}

/** Parse `key: 'value'` / `key: "value"` string fields out of a single `{ ... }` object literal. */
function parse_js_object_fields(string $objectBlock): array
{
    $fields = [];
    preg_match_all(
        '/([a-zA-Z_][a-zA-Z0-9_]*)\s*:\s*(\'((?:[^\'\\\\]|\\\\.)*)\'|"((?:[^"\\\\]|\\\\.)*)")/s',
        $objectBlock,
        $matches,
        PREG_SET_ORDER
    );
    foreach ($matches as $m) {
        $key = $m[1];
        $raw = $m[3] !== '' ? $m[3] : $m[4];
        $fields[$key] = stripcslashes($raw);
    }
    return $fields;
}

/**
 * The project's own hero photo — the FIRST `background-image:url('...')` in
 * the file. (There's a second one further down, in the "next project"
 * teaser card, which points at a *different* project's image — order
 * matters here, don't just grep for any match.)
 */
function extract_first_background_image(string $src): ?string
{
    if (preg_match("/background-image:url\('([^']+)'\)/", $src, $m)) {
        return $m[1];
    }
    return null;
}

/**
 * Image paths parsed straight out of the site's .dc.html source are plain
 * relative strings like 'assets/img/home-01.jpg' — correct when rendered
 * FROM the site itself, but broken when the admin dashboard (a different
 * subdomain) tries to preview them, since a relative path resolves against
 * whatever domain is currently open. Every image path written to the DB
 * during migration goes through this first, matching the same
 * always-absolute convention shared/upload.php already uses for CMS uploads.
 */
function to_absolute_site_url(string $path): string
{
    if ($path === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    return SITE_URL . '/' . ltrim($path, '/');
}

function slugify(string $name): string
{
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}
