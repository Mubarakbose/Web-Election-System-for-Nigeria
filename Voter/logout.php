<?php
// Logout logic
if (isset($_GET['doLogout']) && $_GET['doLogout'] == "true") {
    session_destroy();
    header("Location: ../Index.php");
    exit;
}
