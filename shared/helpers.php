<?php
/**
 * Call at the top of every dynamic PUBLIC endpoint (api/public/*.php) — cPanel
 * shared hosting commonly runs LiteSpeed with server-side page caching, which
 * combined with the browser's own cache can make CMS edits not show up on the
 * live site for a while. This forces every response to be treated as fresh.
 */
function no_cache_headers(): void
{
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('X-LiteSpeed-Cache-Control: no-cache');
}
