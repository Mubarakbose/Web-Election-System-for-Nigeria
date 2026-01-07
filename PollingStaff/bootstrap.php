<?php

/**
 * PollingStaff Bootstrap
 * Initializes session, autoloads helpers, and sets up error handling.
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load database connection
require_once(__DIR__ . '/../Connections/db.php');

// Autoload helpers
require_once(__DIR__ . '/Helpers/autoload.php');
