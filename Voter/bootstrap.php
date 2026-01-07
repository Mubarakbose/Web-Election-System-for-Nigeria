<?php
// Voter portal bootstrap: session + database bootstrap
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Connections/db.php';
