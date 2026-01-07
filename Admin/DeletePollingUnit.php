<?php

require_once('../Connections/db.php');
require_once('access_control.php');


// Check if UnitID is set in POST data
if (isset($_POST['UnitID'])) {
	$UnitID = $_POST['UnitID'];

	// Use PDO helper to delete the record
	$query = "DELETE FROM users WHERE UnitID = :unit";
	try {
		db_query($query, array(':unit' => $UnitID));
		if (session_status() === PHP_SESSION_NONE) {
			@session_start();
		}
		$_SESSION['flash'][] = array('type' => 'success', 'text' => 'Record deleted successfully.');
		header('Location: ListPollingUnit.php');
		exit;
	} catch (Exception $e) {
		echo 'Error: ' . htmlspecialchars($e->getMessage());
	}
} else {
	echo "UnitID not set in POST data.";
}

// No explicit connection close needed for PDO helper
