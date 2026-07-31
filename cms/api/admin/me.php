<?php
/** GET → { loggedIn: bool, id?: int }. Used by the dashboard on load, and to know which admin row is "you" (can't self-delete). */
require __DIR__ . '/../../../shared/config.php';
require __DIR__ . '/../../../shared/auth.php';

$id = current_admin_id();
json_out(['loggedIn' => $id !== null, 'id' => $id]);
