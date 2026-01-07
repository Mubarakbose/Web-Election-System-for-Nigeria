<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

// Get staff info
$row_Staff = StaffContext::current();

// Get voter info
$voterId = RequestInput::get('VoterID', 'int', 0);
$row_Voter = null;

if ($voterId > 0) {
  try {
    $query_Voter = "SELECT * FROM voter WHERE VoterID = :id";
    $result_Voter = db_query($query_Voter, [':id' => $voterId]);
    $row_Voter = db_fetch_assoc($result_Voter);
  } catch (Exception $e) {
    ErrorHandler::redirectWithFlash('AuthenticateVoter.php', 'Voter not found', 'error');
  }
}

if (!$row_Voter) {
  ErrorHandler::redirectWithFlash('AuthenticateVoter.php', 'Invalid voter ID', 'error');
}

// Sanitize voter data for safe display
$voter_safe = array_map('htmlspecialchars', array_map('strval', $row_Voter));

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

// Date bounds for age validation (18+)
$maxBirthDate = date('Y-m-d', strtotime('-18 years'));
$minBirthDate = '1900-01-01';

// Handle update submission
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Update Voter")) {
  $voterId = RequestInput::post('VoterID', 'int', 0);

  if (empty($voterId)) {
    ErrorHandler::redirectWithFlash($editFormAction, 'Invalid Voter ID', 'error');
  }

  // Server-side required validation
  $required = ['FirstName', 'OtherName', 'BirthDate', 'Gender', 'Phone', 'Email', 'State', 'LGA', 'PostCode', 'HomeAddress'];
  $missing = RequestInput::validateRequired($required);
  if (!empty($missing)) {
    ErrorHandler::redirectWithFlash($editFormAction, 'Please fill in all required fields: ' . implode(', ', $missing), 'warning');
  }

  $updateData = [
    'FirstName' => RequestInput::post('FirstName', 'trim') ?? '',
    'OtherName' => RequestInput::post('OtherName', 'trim') ?? '',
    'BirthDate' => RequestInput::post('BirthDate', 'trim') ?? '',
    'Gender' => RequestInput::post('Gender', 'trim') ?? '',
    'Phone' => RequestInput::post('Phone', 'trim') ?? '',
    'Email' => RequestInput::post('Email', 'trim') ?? '',
    'State' => RequestInput::post('State', 'trim') ?? '',
    'LGA' => RequestInput::post('LGA', 'trim') ?? '',
    'PostCode' => RequestInput::post('PostCode', 'trim') ?? '',
    'HomeAddress' => RequestInput::post('HomeAddress', 'trim') ?? '',
  ];

  // Additional validation
  if ($updateData['BirthDate'] !== '' && ($updateData['BirthDate'] < $minBirthDate || $updateData['BirthDate'] > $maxBirthDate)) {
    ErrorHandler::redirectWithFlash($editFormAction, 'Birth date must be between ' . $minBirthDate . ' and ' . $maxBirthDate, 'warning');
  }

  if ($updateData['Phone'] !== '' && !preg_match('/^[0-9+]{11,16}$/', $updateData['Phone'])) {
    ErrorHandler::redirectWithFlash($editFormAction, 'Phone number must be 11-16 digits (may include +).', 'warning');
  }

  if ($updateData['PostCode'] !== '' && !preg_match('/^[0-9]{6}$/', $updateData['PostCode'])) {
    ErrorHandler::redirectWithFlash($editFormAction, 'Post code must be exactly 6 digits.', 'warning');
  }

  if ($updateData['Email'] !== '' && strlen($updateData['Email']) > 64) {
    ErrorHandler::redirectWithFlash($editFormAction, 'Email must be 64 characters or fewer.', 'warning');
  }

  // Get existing image
  $filename = $row_Voter['Image'] ?? '';

  // Handle file upload if provided
  $uploadFile = RequestInput::file('Image');
  if ($uploadFile && $uploadFile['error'] !== UPLOAD_ERR_NO_FILE) {
    $fileUploader = new FileUploadValidator(
      StaffConstants::UPLOAD_VALID_EXTENSIONS,
      StaffConstants::UPLOAD_MAX_SIZE,
      StaffConstants::UPLOAD_VALID_MIME_TYPES
    );

    $validation = $fileUploader->validate($uploadFile);
    if (!$validation['valid']) {
      ErrorHandler::redirectWithFlash($editFormAction, $validation['error'], 'error');
    }
    $filename = $validation['filename'];
    $moveResult = $fileUploader->moveFile($uploadFile, StaffConstants::VOTER_UPLOAD_DIR, $filename);
    if (!$moveResult['success']) {
      ErrorHandler::redirectWithFlash($editFormAction, $moveResult['error'], 'error');
    }
  }

  $updateData['Image'] = $filename;

  // Build update query
  $fields = [];
  $params = [':VoterID' => $voterId];
  foreach ($updateData as $key => $value) {
    if ($value !== null && $value !== '') {
      $fields[] = "$key = :$key";
      $params[":$key"] = $value;
    }
  }

  if (empty($fields)) {
    ErrorHandler::redirectWithFlash($editFormAction, 'No fields to update', 'warning');
  }

  $sql = "UPDATE voter SET " . implode(', ', $fields) . " WHERE VoterID = :VoterID";

  try {
    db_query($sql, $params);
    // After successful update, send email notifying of changes
    $toEmail = $updateData['Email'] ?: ($row_Voter['Email'] ?? '');
    if (!empty($toEmail)) {
      $firstName = $updateData['FirstName'] ?: ($row_Voter['FirstName'] ?? '');

      // Build list of changed fields
      $changedFields = [];
      $fieldLabels = [
        'FirstName' => 'First Name',
        'OtherName' => 'Other Names',
        'Phone' => 'Phone Number',
        'Email' => 'Email Address',
        'State' => 'State',
        'LGA' => 'LGA',
        'PostCode' => 'Post Code',
        'HomeAddress' => 'Home Address',
        'BirthDate' => 'Birth Date',
      ];

      foreach ($updateData as $field => $value) {
        if ($value !== null && $value !== '' && $field !== 'Gender' && $field !== 'Image') {
          $label = $fieldLabels[$field] ?? $field;
          $changedFields[$label] = $value;
        }
      }

      if (!empty($changedFields)) {
        $result = \PollingStaff\Helpers\EmailSender::sendVoterUpdateNotice($toEmail, $firstName, $changedFields);
        if (!$result['success']) {
          $_SESSION['flash'][] = ['type' => 'warning', 'text' => 'Voter updated, but notification email was not sent: ' . $result['message']];
        } else {
          $_SESSION['flash'][] = ['type' => 'success', 'text' => 'Update notification email sent successfully'];
        }
      }
    }
    ErrorHandler::redirectWithFlash($editFormAction, 'Voter updated successfully!', 'success');
  } catch (Exception $e) {
    ErrorHandler::handle($e, 'UpdateVoter', $editFormAction);
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Update Voters</title>
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
    <?php
    // Render flash messages inside body
    echo FlashRenderer::renderAll();
    ?>
    <div class="register">
      <div>
        <div>
          <div class="register">
            <h2>register voters here!</h2>
            <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="Update Voter" id="Update Voter" class="form-container">
              <fieldset>
                <legend>Update Voter Information</legend>

                <div class="form-row">
                  <label for="VoterID" class="form-label">Voter ID</label>
                  <div class="form-control">
                    <input type="text" name="VoterID" id="VoterID" value="<?php echo htmlspecialchars($voter_safe['VoterID']); ?>" readonly title="Read Only">
                    <small class="help-text">Read Only</small>
                  </div>
                </div>

                <div class="form-row">
                  <label for="FirstName" class="form-label">First Name <span class="required">*</span></label>
                  <div class="form-control">
                    <input type="text" name="FirstName" id="FirstName" value="<?php echo htmlspecialchars($voter_safe['FirstName']); ?>" required minlength="3" maxlength="20" aria-required="true">
                  </div>
                </div>

                <div class="form-row">
                  <label for="OtherName" class="form-label">Other Name(s) <span class="required">*</span></label>
                  <div class="form-control">
                    <input type="text" name="OtherName" id="OtherName" value="<?php echo htmlspecialchars($voter_safe['OtherName']); ?>" required minlength="3" maxlength="40" aria-required="true">
                  </div>
                </div>

                <div class="form-row">
                  <label for="BirthDate" class="form-label">Birth Date <span class="required">*</span></label>
                  <div class="form-control">
                    <input type="date" name="BirthDate" id="BirthDate" value="<?php echo htmlspecialchars($voter_safe['BirthDate']); ?>" required max="<?php echo $maxBirthDate; ?>" min="<?php echo $minBirthDate; ?>" aria-required="true">
                  </div>
                </div>

                <div class="form-row">
                  <label for="Phone" class="form-label">Phone Number <span class="required">*</span></label>
                  <div class="form-control">
                    <input type="tel" name="Phone" id="Phone" value="<?php echo htmlspecialchars($voter_safe['Phone']); ?>" required minlength="11" maxlength="16" pattern="[0-9+]{11,16}" inputmode="tel" aria-required="true">
                  </div>
                </div>

                <div class="form-row">
                  <label for="Email" class="form-label">Email <span class="required">*</span></label>
                  <div class="form-control">
                    <input type="email" name="Email" id="Email" value="<?php echo htmlspecialchars($voter_safe['Email'] ?? ''); ?>" placeholder="e.g. example@abc.com" required maxlength="64" aria-required="true" autocomplete="email">
                  </div>
                </div>

                <div class="form-row">
                  <label for="Gender" class="form-label">Gender</label>
                  <div class="form-control">
                    <input type="text" name="Gender" id="Gender" value="<?php echo htmlspecialchars($voter_safe['Gender']); ?>" readonly title="Read Only">
                    <small class="help-text">Read Only</small>
                  </div>
                </div>

                <div class="form-row">
                  <label for="State" class="form-label">State <span class="required">*</span></label>
                  <div class="form-control">
                    <select name="State" id="State" required aria-required="true" title="<?php echo htmlspecialchars($voter_safe['State']); ?>">
                      <option value="<?php echo htmlspecialchars($voter_safe['State']); ?>" selected="selected"><?php echo htmlspecialchars($voter_safe['State']); ?></option>
                      <option value="Abia">Abia</option>
                      <option value="Adamawa">Adamawa</option>
                      <option value="Akwa Ibom">Akwa Ibom</option>
                      <option value="Anambra">Anambra</option>
                      <option value="Bauchi">Bauchi</option>
                      <option value="Bayelsa">Bayelsa</option>
                      <option value="Benue">Benue</option>
                      <option value="Borno">Borno</option>
                      <option value="Cross River">Cross River</option>
                      <option value="Delta">Delta</option>
                      <option value="Ebonyi">Ebonyi</option>
                      <option value="Edo">Edo</option>
                      <option value="Ekiti">Ekiti</option>
                      <option value="Enugu">Enugu</option>
                      <option value="Gombe">Gombe</option>
                      <option value="Imo">Imo</option>
                      <option value="Jigawa">Jigawa</option>
                      <option value="Kaduna">Kaduna</option>
                      <option value="Kano">Kano</option>
                      <option value="Katsina">Katsina</option>
                      <option value="Kebbi">Kebbi</option>
                      <option value="Kogi">Kogi</option>
                      <option value="Kwara">Kwara</option>
                      <option value="Lagos">Lagos</option>
                      <option value="Nasarawa">Nasarwa</option>
                      <option value="Niger">Niger</option>
                      <option value="Ogun">Ogun</option>
                      <option value="Osun">Osun</option>
                      <option value="Ondo">Ondo</option>
                      <option value="Oyo">Oyo</option>
                      <option value="Plateau">Plateau</option>
                      <option value="Rivers">Rivers</option>
                      <option value="Sokoto">Sokoto</option>
                      <option value="Taraba">Taraba</option>
                      <option value="Yobe">Yobe</option>
                      <option value="Zamfara">Zamfara</option>
                      <option value="FCT">FCT</option>
                    </select>
                  </div>
                </div>

                <div class="form-row">
                  <label for="LGA" class="form-label">Local Government Area <span class="required">*</span></label>
                  <div class="form-control">
                    <input type="text" name="LGA" id="LGA" value="<?php echo htmlspecialchars($voter_safe['LGA']); ?>" required minlength="3" maxlength="30" aria-required="true">
                  </div>
                </div>

                <div class="form-row">
                  <label for="PostCode" class="form-label">Post Code <span class="required">*</span></label>
                  <div class="form-control">
                    <input type="text" name="PostCode" id="PostCode" value="<?php echo htmlspecialchars($voter_safe['PostCode']); ?>" required minlength="6" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" aria-required="true" title="6-digit postal code" placeholder="123456">
                  </div>
                </div>

                <div class="form-row">
                  <label for="HomeAddress" class="form-label">Home Address <span class="required">*</span></label>
                  <div class="form-control">
                    <textarea name="HomeAddress" id="HomeAddress" required minlength="10" maxlength="255" aria-required="true" rows="4"><?php echo htmlspecialchars($voter_safe['HomeAddress']); ?></textarea>
                  </div>
                </div>

                <div class="form-row">
                  <label for="Image" class="form-label">Voter Image</label>
                  <div class="form-control">
                    <input type="file" name="Image" id="Image" accept="image/*">
                    <small class="help-text">Accepted: PNG, JPG, GIF</small>
                  </div>
                </div>

                <div class="form-row">
                  <label for="UnitID" class="form-label">Registration Unit ID</label>
                  <div class="form-control">
                    <input type="text" name="UnitID" id="UnitID" value="<?php echo $row_Staff['UnitID']; ?>" readonly title="Read Only">
                    <small class="help-text">Read Only</small>
                  </div>
                </div>

                <div class="form-actions">
                  <label class="form-label"></label>
                  <div class="form-control">
                    <button type="submit" name="submit" id="register" class="btn btn-primary">Update Voter</button>
                    <a href="AuthenticateVoter.php" class="btn btn-secondary">Cancel</a>
                  </div>
                </div>

                <input type="hidden" name="VoterID" value="<?php echo htmlspecialchars($voter_safe['VoterID']); ?>">
                <input type="hidden" name="MM_update" value="Update Voter">
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include('footer.php'); ?>
</body>

</html>