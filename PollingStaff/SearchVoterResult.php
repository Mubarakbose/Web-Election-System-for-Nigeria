<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

// Get staff info
$row_Staff = StaffContext::current();

// Search for voter
$voterId = RequestInput::get('VoterID', 'int', 0);
$row_Voter = null;

if ($voterId > 0) {
  try {
    $query_Voter = "SELECT * FROM voter WHERE VoterID = :id";
    $result_Voter = db_query($query_Voter, [':id' => $voterId]);
    $row_Voter = db_fetch_assoc($result_Voter);
  } catch (Exception $e) {
    ErrorHandler::log($e, 'SearchVoterResult voter lookup', false);
  }
}

// Prepare safe display variables
if (is_array($row_Voter)) {
  $voter_safe = [
    'Image' => $row_Voter['Image'] ?? 'default.png',
    'VoterID' => $row_Voter['VoterID'] ?? '',
    'FirstName' => $row_Voter['FirstName'] ?? '',
    'OtherName' => $row_Voter['OtherName'] ?? '',
    'UnitID' => $row_Voter['UnitID'] ?? '',
    'Email' => $row_Voter['Email'] ?? '',
    'UserName' => $row_Voter['UserName'] ?? '',
    'Password' => $row_Voter['Password'] ?? '',
  ];
} else {
  $voter_safe = [
    'Image' => 'default.png',
    'VoterID' => '',
    'FirstName' => '',
    'OtherName' => '',
    'UnitID' => '',
    'Email' => '',
    'UserName' => '',
    'Password' => '',
  ];
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Authenticate Voter</title>
  <link rel="stylesheet" href="PStaff CSS/unified-responsive.css" type="text/css">
  <link rel="stylesheet" href="PStaff CSS/admin-form-override.css" type="text/css">
  <link rel="stylesheet" href="PStaff CSS/footer-modern.css" type="text/css">
</head>

<body>
  <?php
  $staffFirstName = $row_Staff['FirstName'];
  $currentPage = 'AuthenticateVoter.php';
  include('header.php');
  ?>
  <div class="body">
    <div class="register">
      <div>
        <div>
          <div class="register">
            <h2>Authenticate Voter here!</h2>
            <fieldset>
              <legend>Voter Information</legend>
              <table width="710" align="center" style="font-size:18px; text-align:justify;">
                <tr>
                  <td width="702">
                    <p>Dear Staff, </p>
                    <p>Kindly use the following UserName and Password for this voter and login to the vote casting platform in order to enable him/her to vote. Thank You! </p>
                    <p>Click on the voter ID Number to proced.</p>
                    <p style="color: #F30;"><strong>Note: If zero records found here, that means you entered a wrong value on your search.</strong></p>
                  </td>
                </tr>
              </table>
              <?php if ($row_Voter) { ?>
                <form name="form1" method="post" action="">
                  <table width="709" align="center" style="font-size:18px;">
                    <tr>
                      <td width="212" rowspan="7"><img src="VotersImages/<?php echo htmlspecialchars($voter_safe['Image']); ?>" alt="Voter Image" width="200" height="211" /></td>
                      <td width="97" height="39">VoterID</td>
                      <td width="10">:</td>
                      <td width="370"><?php echo htmlspecialchars($voter_safe['VoterID']); ?></td>
                    </tr>
                    <tr>
                      <td height="46">Name</td>
                      <td>:</td>
                      <td><?php echo htmlspecialchars($voter_safe['FirstName'] . ' ' . $voter_safe['OtherName']); ?></td>
                    </tr>
                    <tr>
                      <td height="46">UnitID</td>
                      <td>:</td>
                      <td><?php echo htmlspecialchars($voter_safe['UnitID']); ?></td>
                    </tr>
                    <tr>
                      <td height="38">Email</td>
                      <td>:</td>
                      <td><?php echo htmlspecialchars($voter_safe['Email']); ?></td>
                    </tr>
                    <tr>
                      <td height="38">Username</td>
                      <td>:</td>
                      <td><?php echo htmlspecialchars($voter_safe['UserName']); ?></td>
                    </tr>
                    <tr>
                      <td height="45">Password</td>
                      <td>:</td>
                      <td><?php echo htmlspecialchars($voter_safe['Password']); ?></td>
                    </tr>
                  </table>
                  <table width="334" align="center">
                    <tr>
                      <td width="103"><a href="../Voter/vote.php" target="new">Proceed Vote</a></td>
                      <td width="108"><a href="UpdateVoter.php?VoterID=<?php echo urlencode($voter_safe['VoterID']); ?>">Update Voter</a></td>
                      <td width="107"><a href="javascript:void(0);" onclick="if(confirm('Are you sure you want to delete this voter? This action cannot be undone.')) { window.location.href='Scripts/DeleteVoterScript.php?VoterID='+encodeURIComponent('<?php echo intval($voter_safe['VoterID']); ?>'); }">Delete voter</a></td>
                    </tr>
                  </table>
                </form>
              <?php } else { ?>
                <div style="text-align:center; padding:30px; font-size:18px; color:#f30;">No voter records found for the search criteria.</div>
              <?php } ?>
              <p>&nbsp;</p>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include('footer.php'); ?>

  <div id="deleteModal" class="modal" role="dialog" aria-labelledby="deleteModalTitle" aria-hidden="true">
    <div class="modal-content">
      <div class="modal-header" id="deleteModalTitle">Confirm Delete</div>
      <div class="modal-body">Are you sure you want to delete this voter? This action cannot be undone.</div>
      <div class="modal-buttons">
        <button class="modal-btn btn-confirm" id="confirmDeleteBtn" aria-label="Confirm delete voter">Delete</button>
        <?php include('footer.php'); ?>
</body>

</html>