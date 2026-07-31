<?php
require __DIR__ . '/../../../shared/config.php';

$_SESSION = [];
session_destroy();

json_out(['ok' => true]);
