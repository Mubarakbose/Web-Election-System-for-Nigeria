<?php
require_once('bootstrap.php');
require_once('access_control.php');

// Logout if requested
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";

if (!empty($_SERVER['QUERY_STRING'])) {
  $logoutAction .= '&' . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_GET['doLogout']) && $_GET['doLogout'] == "true") {
  $_SESSION = array(); // Clear all session variables
  session_destroy();

  $logoutGoTo = "../Index.php"; // Update with your desired logout URL
  if (!empty($logoutGoTo)) {
    header("Location: $logoutGoTo");
    exit;
  }
}

$colname_Voter = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Voter = $_SESSION['MM_Username'];
}
try {
  $Voter = db_query('SELECT * FROM voter WHERE UserName = :username', [':username' => $colname_Voter]);
  $row_Voter = db_fetch_assoc($Voter);
  $totalRows_Voter = db_rowcount($Voter);
} catch (Exception $e) {
  die($e->getMessage());
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Index</title>

  <link href="../CSS Style/VoterGuideStyle.css" rel="stylesheet" type="text/css" />
  <link href="style.css?v=20260113h" rel="stylesheet" type="text/css" />
  <style type="text/css">
    a:link {
      color: #F00;
      text-decoration: none;
    }

    a:hover {
      color: #F06;
      text-decoration: none;
    }

    a:visited {
      text-decoration: none;
    }

    a:active {
      text-decoration: none;
    }

    .voterindextabletitle {
      text-align: center;
      font-size: 20px;
      font-weight: bold;
      font-variant: normal;
      text-transform: capitalize;
    }
  </style>

<body topmargin="35" bgcolor="#006666" style="text-transform:capitalize;">
  <div id="Container">
    <div id="flag"></div>
    <div id="Adminlogin">
      <p>
      <h1>Welcome <?php echo htmlspecialchars($row_Voter['FirstName'] ?? ''); ?> <?php echo htmlspecialchars($row_Voter['OtherName'] ?? ''); ?>!</h1>
      </p>
      <!-- styles moved to Voter/style.css -->
      <div class="tiles-grid index-tiles" role="navigation" aria-label="Voter actions">
        <a class="tile" href="vote.php">
          <img src="vote.png" alt="Vote">
          <h4>Vote</h4>
        </a>
        <a class="tile" href="Results.php">
          <img src="results icon.png" alt="Results">
          <h4>Results</h4>
        </a>
        <a class="tile" href="Profile.php?UserName=<?php echo urlencode($row_Voter['UserName'] ?? ''); ?>">
          <img src="ViewProf.png" alt="Profile">
          <h4>My Profile</h4>
        </a>
        <a class="tile" href="ChangeDetails.php?UserName=<?php echo urlencode($row_Voter['UserName'] ?? ''); ?>">
          <img src="ChangePwd.htm.png" alt="Change Login">
          <h4>Update Login</h4>
        </a>
        <a class="tile" href="<?php echo htmlspecialchars($logoutAction); ?>">
          <img src="logout.png" alt="Logout">
          <h4>Logout</h4>
        </a>
      </div>
      <p>&nbsp;</p>
    </div>
  </div>
</body>

</html>