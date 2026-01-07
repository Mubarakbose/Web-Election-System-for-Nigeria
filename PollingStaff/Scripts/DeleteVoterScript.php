<?php
require_once(__DIR__ . '/../bootstrap.php');
require_once(__DIR__ . '/../access_control.php');

// Basic access control - verify staff is logged in
if (empty($_SESSION['MM_Staff'])) {
    $_SESSION['flash'][] = ['type' => 'warning', 'text' => 'Please log in to continue'];
    header('Location: ../StaffLogin.php');
    exit;
}

try {
    // Validate voter ID
    $voter_id = RequestInput::get('VoterID', 'int', 0);

    if ($voter_id <= 0) {
        throw new Exception('Invalid voter ID provided.');
    }

    // Fetch voter info before deletion (for email notification)
    $fetch_sql = "SELECT FirstName, Email FROM voter WHERE VoterID = :voter_id";
    $result = db_query($fetch_sql, [':voter_id' => $voter_id]);
    $voter = db_fetch_assoc($result);

    if (!$voter) {
        throw new Exception('Voter not found.');
    }

    // Delete voter
    $delete_sql = "DELETE FROM voter WHERE VoterID = :voter_id";
    db_query($delete_sql, [':voter_id' => $voter_id]);

    // Send deletion notification email if email exists
    if (!empty($voter['Email'])) {
        $staffName = $_SESSION['MM_Staff'] ?? 'INEC Admin';
        $result = \PollingStaff\Helpers\EmailSender::sendVoterDeletionNotice($voter['Email'], $voter['FirstName'], $staffName);
        if (!$result['success']) {
            ErrorHandler::log(new Exception($result['message']), 'DeleteVoterScript email');
        }
    }

    $_SESSION['flash'][] = ['type' => 'success', 'text' => 'Voter deleted successfully!'];
    header('Location: ../AuthenticateVoter.php');
    exit;
} catch (Exception $e) {
    ErrorHandler::log($e, 'DeleteVoterScript');
    $_SESSION['flash'][] = ['type' => 'error', 'text' => 'Error deleting voter: ' . $e->getMessage()];
    header('Location: ../AuthenticateVoter.php');
    exit;
}
