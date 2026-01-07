<?php
require_once('bootstrap.php');
require_once('access_control.php');

$pollingUnitService = new PollingUnitService();

if ((isset($_GET['UnitID'])) && ($_GET['UnitID'] != "")) {
  $unitId = intval($_GET['UnitID']);

  if ($pollingUnitService->deleteById($unitId, 'UnitID')) {
    ErrorHandler::redirectWithFlash('ListPollingUnit.php', 'Polling unit deleted successfully!', 'success');
  } else {
    ErrorHandler::redirectWithFlash('ListPollingUnit.php', 'Error deleting polling unit!', 'error');
  }
}

// Fallback for direct access
ErrorHandler::redirectWithFlash('ListPollingUnit.php', 'Invalid request', 'error');
