<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

$staffService = new StaffService();
$pollingUnitService = new PollingUnitService();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Update Polling Staff")) {
  $userId = RequestInput::post('UserID', 'int', 0);

  if (empty($userId)) {
    ErrorHandler::redirectWithFlash('ListPollingStaffs.php', 'Invalid Staff ID', 'error');
  }

  // Server-side required validation for updated fields
  $required = ['FirstName', 'OtherName', 'BirthDate', 'Gender', 'PhoneNumber', 'UserName'];
  $missing = RequestInput::validateRequired($required);
  if (!empty($missing)) {
    ErrorHandler::redirectWithFlash($editFormAction, 'Please fill in all required fields: ' . implode(', ', $missing), 'warning');
  }

  $updateData = [
    'FirstName' => RequestInput::post('FirstName', 'trim') ?? '',
    'LastName' => RequestInput::post('OtherName', 'trim') ?? '',
    'BirthDate' => RequestInput::post('BirthDate', 'trim') ?? '',
    'Gender' => RequestInput::post('Gender', 'trim') ?? '',
    'PhoneNumber' => RequestInput::post('PhoneNumber', 'trim') ?? '',
    'UserName' => RequestInput::post('UserName', 'trim') ?? '',
  ];

  // UnitID: only update when a non-empty value is provided
  $unitRaw = RequestInput::post('UnitID', 'trim', null);
  if ($unitRaw === null || $unitRaw === '') {
    $updateData['UnitID'] = null; // skip updating UnitID
  } else {
    $updateData['UnitID'] = intval($unitRaw);
  }

  // Optional password update
  $newPassword = RequestInput::post('Password2', 'trim');
  $confirmPassword = RequestInput::post('ConfirmPassword', 'trim');
  if (!empty($newPassword) || !empty($confirmPassword)) {
    if ($newPassword !== $confirmPassword) {
      ErrorHandler::redirectWithFlash($editFormAction, 'Password and Confirm Password do not match.', 'error');
    }
    $updateData['Password'] = Auth::hashPassword($newPassword);
  }

  $result = $staffService->update($userId, $updateData);
  if ($result['success']) {
    ErrorHandler::redirectWithFlash('ListPollingStaffs.php', 'Staff updated successfully!', 'success');
  } else {
    ErrorHandler::redirectWithFlash($editFormAction, $result['error'], 'error');
  }
}

$userId = RequestInput::get('UserID', 'int', -1);
$row_UpdateStaff = null;
$PU_rows = [];

if ($userId > 0) {
  $row_UpdateStaff = $staffService->getById($userId, 'UserID');
  if (!$row_UpdateStaff) {
    ErrorHandler::redirectWithFlash('ListPollingStaffs.php', 'Staff not found', 'error');
  }
}

// Get all polling units for dropdown
$allUnits = $pollingUnitService->getAll();
$PU_rows = is_array($allUnits) ? $allUnits : [];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Update Polling Staff</title>
  <link href="CSS Files/Indexstyle.css" rel="stylesheet" type="text/css" />
  <!-- Replaced Spry validation with modern HTML5 + JS validator -->
  <!-- jQuery loaded centrally in page_header.php -->
  <style>
    /* Narrower inputs for this page so fields don't stretch too wide */
    .responsive-form input[type="text"],
    .responsive-form input[type="password"],
    .responsive-form input[type="date"],
    .responsive-form select {
      max-width: 360px;
      width: 100%;
      box-sizing: border-box;
    }

    #RightImagePSS img {
      display: block;
      max-width: 120px;
      height: auto;
      border-radius: 4px;
      border: 1px solid #ddd;
    }

    @media (max-width:600px) {

      .responsive-form input[type="text"],
      .responsive-form select {
        max-width: 100%;
      }

      #RightImagePSS {
        float: none;
        margin: 0 auto 12px;
        text-align: center;
      }
    }
  </style>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" background="images/bg-body.jpg">
  <?php
  include('page_header.php');
  ?>
  <div id="Content" class="u-pad-12">
    <div class="MainContent">
      <div id="MainContentHead">
        <div id="RightImagePSS" class="float-right ml-16">
          <?php
          // Attempt to show staff photo (common column names tried: Photo, Image)
          $staffPhoto = '';
          if (!empty($row_UpdateStaff['Photo'])) {
            $staffPhoto = 'StaffsImages/' . $row_UpdateStaff['Photo'];
          } elseif (!empty($row_UpdateStaff['Image'])) {
            $staffPhoto = 'StaffsImages/' . $row_UpdateStaff['Image'];
          } else {
            // fallback image (if not present in repo, it will show broken image — replace with your placeholder)
            $staffPhoto = 'images/default-staff.png';
          }
          ?>
          <img src="<?php echo htmlspecialchars($staffPhoto); ?>" alt="Staff Photo" />
        </div>
        <h4><span class="muted-text">Update Polling Staff <b><?php echo $row_UpdateStaff['UserID']; ?></b>!</span></h4>
      </div>
      <div id="ContentBody">
        <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="Update Polling Staff" id="Add Polling Staff">

          <div class="responsive-form">

            <div class="form-row">
              <label class="form-label" for="UserID">User ID</label>
              <div class="form-control"><input name="UserID" type="text" id="UserID" value="<?php echo htmlspecialchars($row_UpdateStaff['UserID']); ?>" readonly="readonly" /></div>
            </div>

            <div class="form-row">
              <label class="form-label" for="FirstName">First Name</label>
              <div class="form-control"><input name="FirstName" type="text" id="FirstName" value="<?php echo htmlspecialchars($row_UpdateStaff['FirstName']); ?>" /></div>
            </div>

            <div class="form-row">
              <label class="form-label" for="OtherName">Other Name(s)</label>
              <div class="form-control"><input name="OtherName" type="text" id="OtherName" value="<?php echo htmlspecialchars($row_UpdateStaff['LastName']); ?>" /></div>
            </div>

            <div class="form-row">
              <label class="form-label" for="BirthDate">Birth Date</label>
              <div class="form-control"><input name="BirthDate" type="date" id="BirthDate" value="<?php echo htmlspecialchars($row_UpdateStaff['BirthDate']); ?>" min="1965-12-31" max="2005-12-31" /></div>
            </div>

            <div class="form-row">
              <label class="form-label" for="Gender">Gender</label>
              <div class="form-control">
                <?php $currentGender = isset($row_UpdateStaff['Gender']) ? $row_UpdateStaff['Gender'] : ''; ?>
                <select name="Gender" id="Gender">
                  <option value="" disabled <?php echo empty($currentGender) ? 'selected' : ''; ?>>Select Gender</option>
                  <option value="Male" <?php echo ($currentGender === 'Male') ? 'selected' : ''; ?>>Male</option>
                  <option value="Female" <?php echo ($currentGender === 'Female') ? 'selected' : ''; ?>>Female</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="PhoneNumber">Phone Number</label>
              <div class="form-control"><input type="text" name="PhoneNumber" id="PhoneNumber" value="<?php echo htmlspecialchars($row_UpdateStaff['PhoneNumber']); ?>" maxlength="15" /></div>
            </div>

            <div class="form-row">
              <label class="form-label" for="UserName">Username</label>
              <div class="form-control"><input type="text" name="UserName" id="UserName" value="<?php echo htmlspecialchars($row_UpdateStaff['UserName']); ?>" /></div>
            </div>

            <div class="form-row">
              <label class="form-label" for="Password">Password</label>
              <div class="form-control"><input type="password" name="Password2" id="Password" placeholder="Leave blank to keep current password" /></div>
            </div>

            <div class="form-row">
              <label class="form-label" for="ConfirmPassword">Confirm Password</label>
              <div class="form-control"><input type="password" name="ConfirmPassword" id="ConfirmPassword" placeholder="Re-enter new password" /></div>
            </div>

            <div class="form-row">
              <label class="form-label" for="UnitID">Polling Unit</label>
              <div class="form-control">
                <?php $currentUnitID = isset($row_UpdateStaff['UnitID']) ? (string)$row_UpdateStaff['UnitID'] : ''; ?>
                <select name="UnitID" id="UnitID">
                  <option value="" disabled <?php echo $currentUnitID === '' ? 'selected' : ''; ?>>Select Polling Unit</option>
                  <?php foreach ($PU_rows as $row_PU) {
                    $val = (string)$row_PU['UnitID'];
                    $sel = ($val === $currentUnitID) ? 'selected' : '';
                  ?>
                    <option value="<?php echo htmlspecialchars($val); ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($val); ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="form-row form-actions">
              <div class="form-label"></div>
              <div class="form-control"><button type="submit" class="btn">Update Staff</button></div>
            </div>

          </div>
          <input type="hidden" name="MM_update" value="Update Polling Staff" />
        </form>

      </div>
    </div>
    <?php
    include('page_footer.php');
    ?>
  </div>
  <!-- Spry initializers removed; client-side validation handled by Scripts/form-validate.js and HTML5 attributes -->
</body>

</html>