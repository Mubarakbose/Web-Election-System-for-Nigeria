<?php
require_once('bootstrap.php');
require_once('access_control.php');

$staffService = new StaffService();

if ((isset($_GET['UserID'])) && ($_GET['UserID'] != "")) {
  $userId = intval($_GET['UserID']);

  if ($staffService->deleteById($userId, 'UserID')) {
    ErrorHandler::redirectWithFlash('ListPollingStaffs.php', 'Staff deleted successfully!', 'success');
  } else {
    ErrorHandler::redirectWithFlash('ListPollingStaffs.php', 'Error deleting staff!', 'error');
  }
}

// Fallback for direct access
ErrorHandler::redirectWithFlash('ListPollingStaffs.php', 'Invalid request', 'error');
