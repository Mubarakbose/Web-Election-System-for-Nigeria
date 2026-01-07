<?php

/**
 * Admin Bootstrap
 * Include this file at the top of Admin pages to load all helpers and services.
 * Usage: require_once(__DIR__ . '/bootstrap.php'); or require_once('../bootstrap.php');
 */

// Load database helpers first
require_once(__DIR__ . '/../Connections/db.php');

// Load Admin helpers
require_once(__DIR__ . '/Helpers/autoload.php');

// Load Admin services
require_once(__DIR__ . '/Services/autoload.php');

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
