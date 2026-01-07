<?php
require_once(__DIR__ . '/../bootstrap.php');
require_once(__DIR__ . '/../access_control.php');

if (empty($_SESSION['MM_Staff'])) {
	ErrorHandler::redirectWithFlash('../StaffLogin.php', 'Please log in to send messages', 'warning');
}

// Validate required fields
$required = ['StaffID', 'StaffName', 'UnitID', 'MessageTittle', 'MainMessage'];
$missing = RequestInput::validateRequired($required);
if (!empty($missing)) {
	ErrorHandler::redirectWithFlash(
		'../SendMessage.php',
		'Please fill in all required fields: ' . implode(', ', $missing),
		'warning'
	);
}

// Collect and sanitize input
$staffID = RequestInput::post('StaffID', 'int', 0);
$staffName = RequestInput::post('StaffName', 'trim') ?? '';
$unitID = RequestInput::post('UnitID', 'int', 0);
$messageTittle = RequestInput::post('MessageTittle', 'trim') ?? '';
$mainMessage = RequestInput::post('MainMessage', 'trim') ?? '';

// Insert message
$query = "INSERT INTO message (StaffID, StaffName, UnitID, MessageTittle, MainMessage)
	         VALUES(:staffid, :staffname, :unitid, :tittle, :message)";

try {
	db_query($query, [
		':staffid' => $staffID,
		':staffname' => $staffName,
		':unitid' => $unitID,
		':tittle' => $messageTittle,
		':message' => $mainMessage,
	]);
	ErrorHandler::redirectWithFlash('../SendMessage.php', 'Your message has been sent successfully! INEC technical team will respond as soon as possible.', 'success');
} catch (Exception $e) {
	ErrorHandler::handle($e, 'SendMessageScript', '../SendMessage.php');
}
