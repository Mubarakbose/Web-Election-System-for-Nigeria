<?php
require_once('../../Connections/db.php');
require_once('../Helpers/autoload.php');

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Collect and validate input
$firstName = RequestInput::post('FirstName', 'trim') ?? '';
$lastName = RequestInput::post('LastName', 'trim') ?? '';
$birthDate = RequestInput::post('BirthDate', 'trim') ?? '';
$gender = RequestInput::post('Gender', 'trim') ?? '';
$phoneNumber = RequestInput::post('PhoneNumber', 'trim') ?? '';
$userName = RequestInput::post('UserName', 'trim') ?? '';
$password = RequestInput::post('Password', 'trim') ?? '';

// Hash password
$passwordHash = Auth::hashPassword($password);

// Validate required fields
$required = ['FirstName', 'LastName', 'BirthDate', 'Gender', 'PhoneNumber', 'UserName', 'Password'];
$missing = RequestInput::validateRequired($required);
if (!empty($missing)) {
	ErrorHandler::redirectWithFlash(
		'../AddPollingStaff.php',
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
	ErrorHandler::redirectWithFlash('../AddPollingStaff.php', $validation['error'], 'error');
}

$filename = $validation['filename'];

// Move the file to the upload directory
$moveResult = $fileUploader->moveFile($uploadFile, AdminConstants::STAFF_UPLOAD_DIR, $filename);
if (!$moveResult['success']) {
	ErrorHandler::redirectWithFlash('../AddPollingStaff.php', $moveResult['error'], 'error');
}

// Insert into database
$query = "INSERT INTO users (FirstName, LastName, BirthDate, Gender, PhoneNumber, Image, UserName, Password)
		  VALUES (:first, :last, :birth, :gender, :phone, :image, :username, :password)";

try {
	db_query($query, array(
		':first' => $firstName,
		':last' => $lastName,
		':birth' => $birthDate,
		':gender' => $gender,
		':phone' => $phoneNumber,
		':image' => $filename,
		':username' => $userName,
		':password' => $passwordHash,
	));
	ErrorHandler::redirectWithFlash('../AddPollingStaff.php', 'Staff added successfully!', 'success');
} catch (Exception $e) {
	ErrorHandler::handle($e, 'AddPollingStaffScript', '../AddPollingStaff.php');
}
