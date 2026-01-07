<?php
require_once('../Connections/db.php');
require_once('access_control.php');


// Check if UserID is set in POST data
if (isset($_POST['UserID'])) {
	$UserID = $_POST['UserID'];

	// Use PDO helper to delete the record
	$query = "DELETE FROM users WHERE UserID = :id";
	try {
		db_query($query, array(':id' => $UserID));
		if (session_status() === PHP_SESSION_NONE) {
			@session_start();
		}
		$_SESSION['flash'][] = array('type' => 'success', 'text' => 'Staff Deleted Successfully!');
		header('Location: ListPollingStaffs.php');
		exit;
	} catch (Exception $e) {
		echo 'Error: ' . htmlspecialchars($e->getMessage());
	}
} else {
	echo "UserID not set in POST data.";
}

// No explicit connection close needed for PDO helper
