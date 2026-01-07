<?php
// Unified login handler for Admin, Polling Staff, and Voters
session_start();
require_once('../Connections/db.php');
require_once('../Admin/Helpers/autoload.php');

$LoginId = trim($_POST['LoginId'] ?? $_POST['UserName'] ?? '');
$Password = $_POST['Password'] ?? '';

if ($LoginId === '' || $Password === '') {
    $_SESSION['login_error'] = 'Please enter username/email and password.';
    header('Location: ../Index.php');
    exit;
}

try {
    // Try to find user in users table (Admin or Polling Staff)
    // Note: users table has PhoneNumber column, not Email
    $stmtUser = db_query(
        'SELECT UserName, Password, UserType FROM users WHERE UserName = :login1 OR PhoneNumber = :login2 LIMIT 1',
        [
            ':login1' => $LoginId,
            ':login2' => $LoginId,
        ]
    );
    $userRow = db_fetch_assoc($stmtUser);

    if ($userRow) {
        // Found in users table - Admin or Polling Staff
        $check = Auth::verifyPassword($Password, $userRow['Password'] ?? '');

        if (!$check['valid']) {
            $_SESSION['login_error'] = 'Invalid username or password.';
            header('Location: ../Index.php');
            exit;
        }

        // Optionally upgrade legacy hashes
        if (!empty($check['rehash'])) {
            try {
                db_query('UPDATE users SET Password = :hash WHERE UserName = :username LIMIT 1', [
                    ':hash' => $check['rehash'],
                    ':username' => $LoginId,
                ]);
            } catch (Exception $e) {
                // Silent fail; login can still proceed
            }
        }

        session_regenerate_id(true);

        if ((int)$userRow['UserType'] === 0) {
            // Admin
            $_SESSION['AdminSes'] = $LoginId;
            $_SESSION['MM_UserGroup'] = 0;
            $_SESSION['UserType'] = 'Admin';
            header('Location: ../Admin/AdminHome.php');
            exit;
        } elseif ((int)$userRow['UserType'] === 1) {
            // Polling Staff
            $_SESSION['MM_Staff'] = $LoginId;
            $_SESSION['MM_UserGroup'] = 1;
            $_SESSION['UserType'] = 'PollingStaff';
            header('Location: ../PollingStaff/Index.php');
            exit;
        }
    }

    // Not found in users table, try voter table
    $stmtVoter = db_query(
        'SELECT UserName, Password, AccessLevel, Email, Phone FROM voter 
         WHERE UserName = :login1 OR Email = :login2 OR Phone = :login3 
         LIMIT 1',
        [':login1' => $LoginId, ':login2' => $LoginId, ':login3' => $LoginId]
    );
    $voterRow = db_fetch_assoc($stmtVoter);

    if ($voterRow && (int)$voterRow['AccessLevel'] === 2) {
        // Found in voter table with access level 2
        // Voters don't use bcrypt, just plain password comparison
        if ($voterRow['Password'] !== $Password) {
            $_SESSION['login_error'] = 'Invalid username or password.';
            header('Location: ../Index.php');
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['MM_Username'] = $voterRow['UserName'];
        $_SESSION['MM_UserGroup'] = 2;
        $_SESSION['UserType'] = 'Voter';
        header('Location: ../Voter/VoterIndex.php');
        exit;
    }

    // Not found or invalid access level
    $_SESSION['login_error'] = 'Invalid username or password.';
    header('Location: ../Index.php');
    exit;
} catch (Exception $e) {
    $_SESSION['login_error'] = 'Login error. Please try again.';
    header('Location: ../Index.php');
    exit;
}
