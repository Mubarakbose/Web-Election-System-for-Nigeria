<?php
require_once('bootstrap.php');

if (!isset($_SESSION)) {
  session_start();
}

$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
  $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
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
?>

<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "2";
$MM_donotCheckaccess = "false";

function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
  $isValid = False;

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
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0)
    $MM_referrer .= "?" . htmlentities($_SERVER['QUERY_STRING']);
  $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: " . $MM_restrictGoTo);
  exit;
}
?>

<?php
// Using PDO helper functions from Connections/db.php; prefer prepared statements over manual escaping.

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "UpdateLoginDetails")) {
  $sql = "UPDATE voter SET UserName = :username, Password = :password WHERE VoterID = :voterid";
  $params = [
    ':username' => isset($_POST['Username']) ? $_POST['Username'] : null,
    ':password' => isset($_POST['Password']) ? $_POST['Password'] : null,
    ':voterid' => isset($_POST['VoterID']) ? intval($_POST['VoterID']) : 0,
  ];

  try {
    db_query($sql, $params);
    echo "<script type=\"text/javascript\">alert('Yay!... Details Updated Successfully! We\\'ll log you out to try your new login details'); window.location = '../Index.php'</script>";
    exit;
  } catch (Exception $e) {
    die($e->getMessage());
  }
}

$colname_Voter = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Voter = $_SESSION['MM_Username'];
}
$query_Voter = "SELECT * FROM voter WHERE UserName = :username";
$Voter = db_query($query_Voter, [':username' => $colname_Voter]);
$row_Voter = db_fetch_assoc($Voter);
$totalRows_Voter = db_rowcount($Voter);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Change Details</title>
  <link href="../CSS Style/VoterGuideStyle.css" rel="stylesheet" type="text/css" />
  <link href="style.css?v=20260113h" rel="stylesheet" type="text/css" />
</head>

<body topmargin="35" bgcolor="#006666" style="text-transform:capitalize;">
  <div id="Container">
    <div id="flag"></div>
    <div id="Adminlogin">
      <header id="voterHeader">
        <nav>
          <ul id="MenuBar1" class="MenuBarHorizontal">
            <li><a href="VoterIndex.php">Home</a></li>
            <li><a href="<?php echo $logoutAction ?>">Log Out</a></li>
          </ul>
        </nav>
      </header>

      <main>
        <p>
        <h2>Dear <?php echo htmlspecialchars($row_Voter['FirstName'] ?? ''); ?> <?php echo htmlspecialchars($row_Voter['OtherName'] ?? ''); ?>, you may change your Login details here</h2>
        </p>
        <!-- form styles moved to Voter/style.css -->
        <form id="UpdateLoginDetails" name="UpdateLoginDetails" method="POST" action="<?php echo $editFormAction; ?>" class="form-grid">
          <div class="form-row">
            <label for="Username">Username</label>
            <div class="control"><input name="Username" type="text" id="Username" value="<?php echo htmlspecialchars($row_Voter['UserName'] ?? ''); ?>" minlength="6" maxlength="12" autocomplete="username" required /></div>
          </div>
          <div class="form-row">
            <label for="Password">Password</label>
            <div class="control"><input type="password" name="Password" id="Password" placeholder="Password" minlength="6" maxlength="12" autocomplete="new-password" pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,12}" required /></div>
          </div>
          <div class="form-row">
            <label for="ConfirmPassword">Confirm Password</label>
            <div class="control"><input type="password" name="ConfirmPassword" id="ConfirmPassword" placeholder="Confirm Password" minlength="6" maxlength="12" autocomplete="new-password" required /></div>
          </div>
          <div class="form-actions">
            <input type="hidden" name="VoterID" value="<?php echo intval($row_Voter['VoterID'] ?? 0); ?>">
            <input type="submit" name="Submit" id="Submit" value="Change Login Details" />
            <input type="reset" name="reset" id="reset" value="Clear Form" />
          </div>
          <input type="hidden" name="MM_update" value="UpdateLoginDetails" />
        </form>
      </main>
    </div>
  </div>

  <script type="text/javascript">
    // Keep confirm password in sync with the password value
    (function syncPasswords() {
      const form = document.getElementById('UpdateLoginDetails');
      const password = document.getElementById('Password');
      const confirmPassword = document.getElementById('ConfirmPassword');
      if (!form || !password || !confirmPassword) return;

      const validate = () => {
        if (confirmPassword.value && confirmPassword.value !== password.value) {
          confirmPassword.setCustomValidity('Passwords must match');
        } else {
          confirmPassword.setCustomValidity('');
        }
      };

      password.addEventListener('input', validate);
      confirmPassword.addEventListener('input', validate);
      form.addEventListener('submit', validate);
    })();
  </script>
</body>

</html>

<?php
unset($Voter);
?>