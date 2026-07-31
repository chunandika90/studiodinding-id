<?php
/**
 * Image upload, shared by every admin endpoint that accepts a photo.
 * Files are always written into the PUBLIC SITE's assets/img/uploads
 * folder — even when this runs from an admin.studiodinding request —
 * because that's the folder actually served to visitors. The returned
 * URL is always absolute (SITE_URL/...), never root-relative, so it
 * resolves correctly no matter which domain is rendering the <img> tag
 * (the public site itself, or the admin panel on a different subdomain).
 */

function site_uploads_dir(): string
{
    // shared/ is a sibling of the site's document root folder (SITE_DIR_BASE)
    // in the cPanel home directory — see shared/config.local.example.php.
    $dir = __DIR__ . '/../' . SITE_DIR_BASE . '/assets/img/uploads';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    return $dir;
}

/**
 * @return array{ok:true,url:string}|array{ok:false,error:string}
 */
function handle_image_upload(array $file): array
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'No file uploaded, or upload error.'];
    }

    $maxBytes = 8 * 1024 * 1024; // 8MB
    if ($file['size'] > $maxBytes) {
        return ['ok' => false, 'error' => 'File too large (max 8MB).'];
    }

    $imageInfo = @getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        return ['ok' => false, 'error' => 'File is not a valid image.'];
    }

    $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    $mime = $imageInfo['mime'];
    if (!isset($allowedMimes[$mime])) {
        return ['ok' => false, 'error' => 'Only JPG, PNG or WEBP images are allowed.'];
    }

    $ext = $allowedMimes[$mime];
    $filename = bin2hex(random_bytes(12)) . '.' . $ext;
    $destination = site_uploads_dir() . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['ok' => false, 'error' => 'Failed to save uploaded file.'];
    }

    downscale_image_if_needed($destination, $mime, $imageInfo[0], $imageInfo[1]);

    return ['ok' => true, 'url' => UPLOAD_URL_BASE . '/' . $filename];
}

/**
 * Every photo slot on the site is displayed with `object-fit:cover` /
 * `background-size:cover`, so any uploaded image gets cropped to fit — this
 * isn't about matching an exact size, it's about not shipping a 6000px
 * camera-original when 2000px is already more than the largest slot ever
 * renders at. Only ever shrinks (never upscales a smaller photo), keeps
 * aspect ratio, and silently no-ops if the GD extension isn't available
 * rather than failing the upload.
 */
function downscale_image_if_needed(string $path, string $mime, int $width, int $height): void
{
    $maxDimension = 2000;
    if (max($width, $height) <= $maxDimension || !extension_loaded('gd')) {
        return;
    }

    $ratio = $maxDimension / max($width, $height);
    $newWidth = (int) round($width * $ratio);
    $newHeight = (int) round($height * $ratio);

    if ($mime === 'image/jpeg') {
        $src = @imagecreatefromjpeg($path);
    } elseif ($mime === 'image/png') {
        $src = @imagecreatefrompng($path);
    } elseif ($mime === 'image/webp') {
        $src = @imagecreatefromwebp($path);
    } else {
        $src = false;
    }
    if (!$src) {
        return;
    }

    $dst = imagecreatetruecolor($newWidth, $newHeight);
    if ($mime === 'image/png') {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
    }
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if ($mime === 'image/jpeg') {
        imagejpeg($dst, $path, 85);
    } elseif ($mime === 'image/png') {
        imagepng($dst, $path, 6);
    } elseif ($mime === 'image/webp') {
        imagewebp($dst, $path, 85);
    }

    imagedestroy($src);
    imagedestroy($dst);
}

/**
 * General file attachments (contact form uploads) — separate from photo
 * uploads: no resize, and PDFs are allowed alongside images. Stored in its
 * own folder so it's easy to reason about/clean up separately from
 * assets/img/uploads. Same cross-domain absolute-URL convention.
 */
function site_attachments_dir(): string
{
    $dir = __DIR__ . '/../' . SITE_DIR_BASE . '/assets/uploads/attachments';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    return $dir;
}

/**
 * @return array{ok:true,url:string,filename:string}|array{ok:false,error:string}
 */
function handle_attachment_upload(array $file): array
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'No file uploaded, or upload error.'];
    }

    $maxBytes = 10 * 1024 * 1024; // 10MB
    if ($file['size'] > $maxBytes) {
        return ['ok' => false, 'error' => 'File too large (max 10MB).'];
    }

    $originalName = $file['name'] ?? 'attachment';
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
    if (!in_array($ext, $allowedExt, true)) {
        return ['ok' => false, 'error' => 'Only JPG, PNG, WEBP or PDF files are allowed.'];
    }

    // Belt-and-suspenders: confirm images are really images (extension alone is spoofable).
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) && @getimagesize($file['tmp_name']) === false) {
        return ['ok' => false, 'error' => 'File is not a valid image.'];
    }

    $filename = bin2hex(random_bytes(12)) . '.' . $ext;
    $destination = site_attachments_dir() . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['ok' => false, 'error' => 'Failed to save uploaded file.'];
    }

    return [
        'ok' => true,
        'url' => SITE_URL . '/assets/uploads/attachments/' . $filename,
        'filename' => $originalName,
    ];
}
