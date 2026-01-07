<?php
require_once('../../Connections/db.php');
require_once('../Helpers/autoload.php');

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Collect and validate input
$firstName = RequestInput::post('FirstName', 'trim') ?? '';
$otherName = RequestInput::post('OtherNames', 'trim') ?? '';
$partyName = RequestInput::post('PartyName', 'trim') ?? '';
$position = RequestInput::post('Position', 'trim') ?? '';
$state = RequestInput::post('State', 'trim') ?? '';
$fedConstituency = RequestInput::post('FedConstituency', 'trim') ?? '';
$stateConstituency = RequestInput::post('StateConstituency', 'trim') ?? '';
$senateZone = RequestInput::post('SenateZone', 'trim') ?? '';

// Validate required fields
$required = ['FirstName', 'OtherNames', 'PartyName', 'Position', 'State'];
$missing = RequestInput::validateRequired($required);
if (!empty($missing)) {
	ErrorHandler::redirectWithFlash(
		'../AddContestant.php',
		'Please fill in all required fields: ' . implode(', ', $missing),
		'warning'
	);
}

// Validate and process image upload
$fileUploader = new FileUploadValidator(
	AdminConstants::UPLOAD_VALID_EXTENSIONS,
	AdminConstants::UPLOAD_MAX_SIZE,
	AdminConstants::UPLOAD_VALID_MIME_TYPES
);

$uploadFile = RequestInput::file('Image');
$validation = $fileUploader->validate($uploadFile);

if (!$validation['valid']) {
	ErrorHandler::redirectWithFlash('../AddContestant.php', $validation['error'], 'error');
}

$filename = $validation['filename'];

// Move the file to the upload directory
$moveResult = $fileUploader->moveFile($uploadFile, AdminConstants::CONTESTANT_UPLOAD_DIR, $filename);
if (!$moveResult['success']) {
	ErrorHandler::redirectWithFlash('../AddContestant.php', $moveResult['error'], 'error');
}

// Insert into database
$query = "INSERT INTO contestant (
	FirstName, OtherNames, PartyName, Position, State, Image, FedConstituency, StateConstituency, SenateZone
) VALUES (
	:first, :other, :party, :position, :state, :image, :fed, :stateconst, :senate
)";

try {
	db_query($query, array(
		':first' => $firstName,
		':other' => $otherName,
		':party' => $partyName,
		':position' => $position,
		':state' => $state,
		':image' => $filename,
		':fed' => $fedConstituency,
		':stateconst' => $stateConstituency,
		':senate' => $senateZone,
	));
	ErrorHandler::redirectWithFlash('../AddContestant.php', 'Contestant added successfully!', 'success');
} catch (Exception $e) {
	ErrorHandler::handle($e, 'AddContestantScript', '../AddContestant.php');
}
