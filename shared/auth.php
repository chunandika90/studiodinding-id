<?php
/** Session-based admin auth helpers. Include after config.php. */

function current_admin_id(): ?int
{
    return $_SESSION['admin_id'] ?? null;
}

/** Call at the top of every cms/api/admin/*.php endpoint. Halts with 401 if not logged in. */
function require_admin(): void
{
    if (current_admin_id() === null) {
        json_out(['error' => 'Not authenticated.'], 401);
    }
}
