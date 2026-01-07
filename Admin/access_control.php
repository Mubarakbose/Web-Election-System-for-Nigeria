<?php
// Ensure session is started safely
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once('../Connections/db.php');

// Check if the user is logged in
if (!isset($_SESSION['AdminSes'])) {
  redirectToIndex("Access denied. You are not logged in.");
}

// Function to display an error message and redirect to the index page
function redirectToIndex($errorMessage)
{
  // Use session-based flash and a proper HTTP redirect to avoid inline JS alerts
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  $_SESSION['flash'][] = array('type' => 'error', 'text' => $errorMessage);
  header('Location: ../Index.php');
  exit;
}
