<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

$voterId = RequestInput::get('VoterID', 'int', 0);

if ($voterId <= 0) {
  ErrorHandler::redirectWithFlash('AuthenticateVoter.php', 'Invalid voter ID provided', 'warning');
}

try {
  db_query('DELETE FROM voter WHERE VoterID = :id', [':id' => $voterId]);
  ErrorHandler::redirectWithFlash('AuthenticateVoter.php', 'Voter deleted successfully', 'success');
} catch (Exception $e) {
  ErrorHandler::handle($e, 'DeleteVoter', 'AuthenticateVoter.php');
}

// fallback redirect
header('Location: AuthenticateVoter.php');
exit;
