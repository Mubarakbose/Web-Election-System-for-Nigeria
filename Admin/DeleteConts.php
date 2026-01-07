<?php
require_once('bootstrap.php');
require_once('access_control.php');

$contestantService = new ContestantService();

if (isset($_GET['ContestantID']) && is_numeric($_GET['ContestantID'])) {
  $contestantId = intval($_GET['ContestantID']);

  if ($contestantService->deleteById($contestantId, 'ContestantID')) {
    ErrorHandler::redirectWithFlash('ListContestant.php', 'Contestant deleted successfully!', 'success');
  } else {
    ErrorHandler::redirectWithFlash('ListContestant.php', 'Error deleting contestant!', 'error');
  }
}

// Fallback for direct access (shouldn't happen in normal flow)
ErrorHandler::redirectWithFlash('ListContestant.php', 'Invalid request', 'error');
