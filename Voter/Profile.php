<?php
require_once('bootstrap.php');

// Initialize the session
if (!isset($_SESSION)) {
  session_start();
}

$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";

if (isset($_GET['doLogout']) && ($_GET['doLogout'] == "true")) {
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
  $logoutGoTo = "../Index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}

$MM_authorizedUsers = "2";
$MM_donotCheckaccess = "false";

function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
  $isValid = false;

  if (!empty($UserName)) {
    $arrUsers = explode(",", $strUsers);
    $arrGroups = explode(",", $strGroups);
    if (in_array($UserName, $arrUsers)) {
      $isValid = true;
    }
    if (in_array($UserGroup, $arrGroups)) {
      $isValid = true;
    }
    if (($strUsers == "") && false) {
      $isValid = true;
    }
  }
  return $isValid;
}

$MM_restrictGoTo = "../Index.php";
if (!(isset($_SESSION['MM_Username']) && isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup']))) {
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) {
    $MM_qsChar = "&";
  }
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) {
    $MM_referrer .= "?" . htmlentities($_SERVER['QUERY_STRING']);
  }
  $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: " . $MM_restrictGoTo);
  exit;
}

// Use db helper for safe queries
$colname_VoterProfile = "-1";
if (isset($_GET['VoterID'])) {
  $colname_VoterProfile = intval($_GET['VoterID']);
}
$VoterProfile = db_query("SELECT * FROM voter WHERE VoterID = ?", [$colname_VoterProfile]);
$row_VoterProfile = db_fetch_assoc($VoterProfile);

if (isset($_SESSION['MM_Username'])) {
  $colname_VoterProfile = $_SESSION['MM_Username'];
}
$VoterProfile = db_query("SELECT * FROM voter WHERE UserName = ?", [$colname_VoterProfile]);
$row_VoterProfile = db_fetch_assoc($VoterProfile);
$totalRows_VoterProfile = db_rowcount($VoterProfile);

unset($VoterProfile);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Profile</title>
  <link href="../CSS Style/VoterGuideStyle.css" rel="stylesheet" type="text/css" />
  <link href="style.css?v=20260113h" rel="stylesheet" type="text/css" />
</head>

<body topmargin="35" bgcolor="#006666" style="text-transform:capitalize;">
  <div id="Container">
    <div id="flag"></div>
    <div id="Adminlogin">
      <header id="voterHeader">
        <button class="menu-toggle" aria-label="Toggle menu" onclick="document.querySelector('header#voterHeader nav').classList.toggle('active')">
          ☰
        </button>
        <nav>
          <ul id="MenuBar1" class="MenuBarHorizontal">
            <li><a href="VoterIndex.php">Home</a></li>
            <li><a href="Results.php">Results</a></li>
            <li><a href="<?php echo $logoutAction ?>">Logout</a></li>
          </ul>
        </nav>
      </header>

      <main>
        <!-- profile styles moved to Voter/style.css -->
        <section class="profile-card" aria-label="Voter profile">
          <div class="profile-details">
            <div class="profile-row">
              <div class="label">Identification Number</div>
              <div class="value"><?php echo htmlspecialchars($row_VoterProfile['VoterID'] ?? ''); ?></div>
            </div>
            <div class="profile-row">
              <div class="label">Name</div>
              <div class="value"><?php echo htmlspecialchars(($row_VoterProfile['FirstName'] ?? '') . ' ' . ($row_VoterProfile['OtherName'] ?? '')); ?></div>
            </div>
            <div class="profile-row">
              <div class="label">Date of Birth</div>
              <div class="value"><?php echo htmlspecialchars($row_VoterProfile['BirthDate'] ?? ''); ?></div>
            </div>
            <div class="profile-row">
              <div class="label">Gender</div>
              <div class="value"><?php echo htmlspecialchars($row_VoterProfile['Gender'] ?? ''); ?></div>
            </div>
            <div class="profile-row">
              <div class="label">Phone Number</div>
              <div class="value"><?php echo htmlspecialchars($row_VoterProfile['Phone'] ?? ''); ?></div>
            </div>
            <div class="profile-row">
              <div class="label">Address</div>
              <div class="value"><?php echo htmlspecialchars($row_VoterProfile['HomeAddress'] ?? ''); ?>, <?php echo htmlspecialchars($row_VoterProfile['LGA'] ?? ''); ?>, <?php echo htmlspecialchars($row_VoterProfile['State'] ?? ''); ?> State</div>
            </div>
            <div class="profile-row">
              <div class="label">Registration Unit</div>
              <div class="value"><?php echo htmlspecialchars($row_VoterProfile['UnitID'] ?? ''); ?></div>
            </div>
            <div class="profile-row">
              <div class="label">Username</div>
              <div class="value"><?php echo htmlspecialchars($row_VoterProfile['UserName'] ?? ''); ?></div>
            </div>
          </div>
          <div class="profile-photo">
            <img src="../PollingStaff/VotersImages/<?php echo htmlspecialchars($row_VoterProfile['Image'] ?? ''); ?>" alt="Voter photo">
            <div style="margin-top:8px;"><a href="ChangeDetails.php?UserName=<?php echo urlencode($row_VoterProfile['UserName'] ?? ''); ?>" class="tile">Change login</a></div>
          </div>
        </section>
      </main>
    </div>
  </div>

</body>

</html>