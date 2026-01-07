<?php
// Simple flash messaging helper using session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function set_flash($type, $message)
{
    if (!isset($_SESSION['flash'])) $_SESSION['flash'] = [];
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function get_flash()
{
    if (empty($_SESSION['flash'])) return [];
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}

function render_flash()
{
    $flashes = get_flash();
    if (empty($flashes)) return '';
    $out = '';
    foreach ($flashes as $f) {
        $type = htmlspecialchars($f['type']);
        $msg = htmlspecialchars($f['message']);
        $out .= "<div class=\"flash-message flash-{$type}\">{$msg}</div>\n";
    }
    return $out;
}
