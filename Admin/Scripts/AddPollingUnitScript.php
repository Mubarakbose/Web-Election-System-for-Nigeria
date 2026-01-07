<?php
require_once('../../Connections/db.php');
require_once('../Helpers/autoload.php');

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Validate required fields
$required = ['State', 'LGA', 'PUName'];
$missing = RequestInput::validateRequired($required);
if (!empty($missing)) {
	ErrorHandler::redirectWithFlash(
		'../AddPollingUnit.php',
		'Please fill in all required fields: ' . implode(', ', $missing),
		'warning'
	);
}

// Collect input (sanitized)
$State = RequestInput::post('State', 'trim') ?? '';
$LGA = RequestInput::post('LGA', 'trim') ?? '';
$PUName = RequestInput::post('PUName', 'trim') ?? '';

$Query = "INSERT INTO pollingunit (State, LGA, PUName) VALUES (:state, :lga, :puname)";

try {
	db_query($Query, array(':state' => $State, ':lga' => $LGA, ':puname' => $PUName));
	ErrorHandler::redirectWithFlash('../AddPollingUnit.php', 'New Polling Unit has been added!', 'success');
} catch (Exception $e) {
	ErrorHandler::handle($e, 'AddPollingUnitScript', '../AddPollingUnit.php');
}
