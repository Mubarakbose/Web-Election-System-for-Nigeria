<?php
require_once('bootstrap.php');
require_once('access_control.php');

// initialize the session if needed
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
    $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
    // fully clear session variables
    $_SESSION['MM_Staff'] = null;
    $_SESSION['MM_UserGroup'] = null;
    $_SESSION['PrevUrl'] = null;
    unset($_SESSION['MM_Staff'], $_SESSION['MM_UserGroup'], $_SESSION['PrevUrl'], $_SESSION['flash']);

    session_regenerate_id(true);

    $logoutGoTo = "../Index.php";
    if ($logoutGoTo) {
        header("Location: $logoutGoTo");
        exit;
    }
}
