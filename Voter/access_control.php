<?php
// Simple Voter portal auth guard. Assumes bootstrap.php has been loaded (starts session).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$redirectTo = "../Index.php";

// Require a logged-in voter username.
if (empty($_SESSION['MM_Username'])) {
    $qsChar = strpos($redirectTo, '?') !== false ? '&' : '?';
    $referrer = $_SERVER['PHP_SELF'];
    if (!empty($_SERVER['QUERY_STRING'])) {
        $referrer .= '?' . $_SERVER['QUERY_STRING'];
    }
    $location = $redirectTo . $qsChar . 'accesscheck=' . urlencode($referrer);
    header('Location: ' . $location);
    exit;
}
