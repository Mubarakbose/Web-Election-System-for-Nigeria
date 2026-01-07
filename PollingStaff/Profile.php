<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

// Single, consistent staff lookup
$row_Staff = StaffContext::current();
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
  $currentPage = 'Profile.php';
  include('header.php');
  ?>
  <div class="body">
    <div class="register">
      <div>
        <div>
          <div class="register">
            <h2>My profile!</h2>
            <fieldset>
              <legend>My Profile</legend>
              <table width="728" align="center" cellpadding="5" style="font-size:18px; text-align:justify;">
                <tr>
                  <td width="720">
                    <p>Dear Staff, </p>
                    <p>This is your profile. You can only view your profile but you are not given the privilage to update anything from your profil. If you want to make any changes to your profile kindly contact INEC by sending us a message so that we can verify you. Thank You for you cooperation!</p>
                  </td>
                </tr>
              </table>
              <hr>
              <table width="727" align="center" style="font-size:18px;">
                <tr>
                  <td width="125">Staff Number</td>
                  <td width="16">:</td>
                  <td width="396"><?php echo $row_Staff['UserID']; ?></td>
                  <td width="170">Image</td>
                </tr>
                <tr>
                  <td height="21">Name</td>
                  <td>:</td>
                  <td><?php echo $row_Staff['FirstName']; ?> <?php echo $row_Staff['LastName']; ?></td>
                  <td rowspan="6"><img src="../Admin/StaffsImages/<?php echo $row_Staff['Image']; ?>" alt="Staff Image" width="157" height="158" /></td>
                </tr>
                <tr>
                  <td height="26">Date of Birth</td>
                  <td>:</td>
                  <td><?php echo $row_Staff['BirthDate']; ?></td>
                </tr>
                <tr>
                  <td>Gender</td>
                  <td>:</td>
                  <td><?php echo $row_Staff['Gender']; ?></td>
                </tr>
                <tr>
                  <td>Phone Number</td>
                  <td>:</td>
                  <td><?php echo $row_Staff['PhoneNumber']; ?></td>
                </tr>
                <tr>
                  <td>Username</td>
                  <td>:</td>
                  <td><?php echo $row_Staff['UserName']; ?></td>
                </tr>
                <tr>
                  <td>UnitID</td>
                  <td>:</td>
                  <td><?php echo $row_Staff['UnitID']; ?></td>
                </tr>
              </table>
              <hr>
              <p>&nbsp;</p>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include('footer.php'); ?>
</body>

</html>