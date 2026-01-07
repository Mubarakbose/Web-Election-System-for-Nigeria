<?php
require_once(__DIR__ . '/../bootstrap.php');
require_once(__DIR__ . '/../access_control.php');

// Require authenticated staff
if (empty($_SESSION['MM_Staff'])) {
	ErrorHandler::redirectWithFlash('../StaffLogin.php', 'Please log in to add voters', 'warning');
}

// Validate required fields
$required = ['FirstName', 'OtherName', 'BirthDate', 'Gender', 'Phone', 'Email', 'State', 'LGA', 'PostCode', 'HomeAddress', 'SenateZone', 'FedConstituency', 'StateConstituency', 'UnitID'];
$missing = RequestInput::validateRequired($required);
if (!empty($missing)) {
	ErrorHandler::redirectWithFlash(
		'../AddVoters.php',
		'Please fill in all required fields: ' . implode(', ', $missing),
		'warning'
	);
}

// Collect and sanitize input
$firstName = RequestInput::post('FirstName', 'trim') ?? '';
$otherName = RequestInput::post('OtherName', 'trim') ?? '';
$birthDate = RequestInput::post('BirthDate', 'trim') ?? '';
$gender = RequestInput::post('Gender', 'trim') ?? '';
$phone = RequestInput::post('Phone', 'trim') ?? '';
$email = RequestInput::post('Email', 'email') ?? '';
$state = RequestInput::post('State', 'trim') ?? '';
$lga = RequestInput::post('LGA', 'trim') ?? '';
$postCode = RequestInput::post('PostCode', 'trim') ?? '';
$homeAddress = RequestInput::post('HomeAddress', 'trim') ?? '';
$senateZone = RequestInput::post('SenateZone', 'trim') ?? '';
$fedConstituency = RequestInput::post('FedConstituency', 'trim') ?? '';
$stateConstituency = RequestInput::post('StateConstituency', 'trim') ?? '';
$unitID = RequestInput::post('UnitID', 'int', 0);

// Additional server-side validation
$maxBirthDate = date('Y-m-d', strtotime('-18 years'));
$minBirthDate = '1900-01-01';
if ($birthDate < $minBirthDate || $birthDate > $maxBirthDate) {
	ErrorHandler::redirectWithFlash('../AddVoters.php', 'Birth date must be between ' . $minBirthDate . ' and ' . $maxBirthDate, 'warning');
}

if (!preg_match('/^[0-9+]{11,16}$/', $phone)) {
	ErrorHandler::redirectWithFlash('../AddVoters.php', 'Phone number must be 11-16 digits (may include +).', 'warning');
}

if (!preg_match('/^[0-9]{6}$/', $postCode)) {
	ErrorHandler::redirectWithFlash('../AddVoters.php', 'Post code must be exactly 6 digits.', 'warning');
}

if (strlen($email) > 64) {
	ErrorHandler::redirectWithFlash('../AddVoters.php', 'Email must be 64 characters or fewer.', 'warning');
}

// Check for duplicate email
try {
	$emailCheck = db_query('SELECT VoterID FROM voter WHERE Email = :email LIMIT 1', [':email' => $email]);
	if (db_fetch_assoc($emailCheck)) {
		ErrorHandler::redirectWithFlash('../AddVoters.php', 'This email address is already registered.', 'error');
	}
} catch (Exception $e) {
	ErrorHandler::handle($e, 'AddVoterScript - Email Check', '../AddVoters.php');
}

// Check for duplicate phone number
try {
	$phoneCheck = db_query('SELECT VoterID FROM voter WHERE Phone = :phone LIMIT 1', [':phone' => $phone]);
	if (db_fetch_assoc($phoneCheck)) {
		ErrorHandler::redirectWithFlash('../AddVoters.php', 'This phone number is already registered.', 'error');
	}
} catch (Exception $e) {
	ErrorHandler::handle($e, 'AddVoterScript - Phone Check', '../AddVoters.php');
}

// Generate username (unique ID)
$userName = uniqid();

// Validate and process image upload
$fileUploader = new FileUploadValidator(
	StaffConstants::UPLOAD_VALID_EXTENSIONS,
	StaffConstants::UPLOAD_MAX_SIZE,
	StaffConstants::UPLOAD_VALID_MIME_TYPES
);

$uploadFile = RequestInput::file('Image');
$validation = $fileUploader->validate($uploadFile);

if (!$validation['valid']) {
	ErrorHandler::redirectWithFlash('../AddVoters.php', $validation['error'], 'error');
}

$filename = $validation['filename'];

// Move the file to the upload directory
$moveResult = $fileUploader->moveFile($uploadFile, StaffConstants::VOTER_UPLOAD_DIR, $filename);
if (!$moveResult['success']) {
	ErrorHandler::redirectWithFlash('../AddVoters.php', $moveResult['error'], 'error');
}

// Insert into database (password is birthdate by default)
$query = "INSERT INTO voter (
	FirstName, OtherName, BirthDate, Gender, Phone, Email, State, LGA, PostCode, HomeAddress, 
	Image, UnitID, UserName, Password, SenateZone, FedConstituency, StateConstituency
) VALUES (
	:first, :other, :birth, :gender, :phone, :email, :state, :lga, :postcode, :address,
	:image, :unitid, :username, :password, :senate, :fed, :stateconst
)";

try {
	db_query($query, [
		':first' => $firstName,
		':other' => $otherName,
		':birth' => $birthDate,
		':gender' => $gender,
		':phone' => $phone,
		':email' => $email,
		':state' => $state,
		':lga' => $lga,
		':postcode' => $postCode,
		':address' => $homeAddress,
		':image' => $filename,
		':unitid' => $unitID,
		':username' => $userName,
		':password' => $birthDate, // Default password is birthdate
		':senate' => $senateZone,
		':fed' => $fedConstituency,
		':stateconst' => $stateConstituency,
	]);

	// Send Email notification (if email is provided) using EmailSender helper
	if (!empty($email)) {
		$result = \PollingStaff\Helpers\EmailSender::sendVoterWelcome($email, $firstName, $userName, $birthDate);
		// Optional: add a flash based on result
		if (!$result['success']) {
			$_SESSION['flash'][] = ['type' => 'warning', 'text' => 'Voter registered, but notification email was not sent: ' . $result['message']];
		} else {
			$_SESSION['flash'][] = ['type' => 'success', 'text' => 'Welcome email sent successfully'];
		}
	}

	ErrorHandler::redirectWithFlash('../AddVoters.php', 'New voter registered successfully!', 'success');
} catch (Exception $e) {
	ErrorHandler::handle($e, 'AddVoterScript', '../AddVoters.php');
}
