<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

// Initialize service and file uploader
$contestantService = new ContestantService();
$fileUploader = new FileUploadValidator(
  AdminConstants::UPLOAD_VALID_EXTENSIONS,
  AdminConstants::UPLOAD_MAX_SIZE,
  AdminConstants::UPLOAD_VALID_MIME_TYPES
);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Update Contestant")) {
  $contestantId = RequestInput::post('ContestantID', 'int', 0);

  if (empty($contestantId)) {
    ErrorHandler::redirectWithFlash('ListContestant.php', 'Invalid Contestant ID', 'error');
  }

  // Server-side required validation
  $required = ['FirstName', 'OtherName', 'PartyName', 'Position', 'State'];
  $missing = RequestInput::validateRequired($required);
  if (!empty($missing)) {
    ErrorHandler::redirectWithFlash($editFormAction, 'Please fill in all required fields: ' . implode(', ', $missing), 'warning');
  }

  // Collect contestant data
  $updateData = [
    'FirstName' => RequestInput::post('FirstName', 'trim') ?? '',
    'OtherNames' => RequestInput::post('OtherName', 'trim') ?? '',
    'PartyName' => RequestInput::post('PartyName', 'trim') ?? '',
    'Position' => RequestInput::post('Position', 'trim') ?? '',
    'State' => RequestInput::post('State', 'trim') ?? '',
  ];

  // Get existing image
  $existing = $contestantService->getById($contestantId, 'ContestantID');
  $filename = $existing['Image'] ?? '';

  // Handle file upload if provided
  $uploadFile = RequestInput::file('Image');
  if ($uploadFile && $uploadFile['error'] !== UPLOAD_ERR_NO_FILE) {
    $validation = $fileUploader->validate($uploadFile);
    if (!$validation['valid']) {
      ErrorHandler::redirectWithFlash($editFormAction, $validation['error'], 'error');
    }
    $filename = $validation['filename'];
    $moveResult = $fileUploader->moveFile($uploadFile, AdminConstants::CONTESTANT_UPLOAD_DIR, $filename);
    if (!$moveResult['success']) {
      ErrorHandler::redirectWithFlash($editFormAction, $moveResult['error'], 'error');
    }
  }

  $updateData['Image'] = $filename;

  $result = $contestantService->update($contestantId, $updateData);
  if ($result['success']) {
    ErrorHandler::redirectWithFlash('ListContestant.php', 'Contestant updated successfully!', 'success');
  } else {
    ErrorHandler::redirectWithFlash($editFormAction, $result['error'], 'error');
  }
}

$contestantId = RequestInput::get('ContestantID', 'int', -1);
$row_UpdateCon = null;

if ($contestantId > 0) {
  $row_UpdateCon = $contestantService->getById($contestantId, 'ContestantID');
  if (!$row_UpdateCon) {
    ErrorHandler::redirectWithFlash('ListContestant.php', 'Contestant not found', 'error');
  }
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Update Contestant</title>
  <link href="CSS Files/Indexstyle.css" rel="stylesheet" type="text/css" />
  <!-- Replaced Spry validation with modern HTML5 + JS validator -->
  <!-- jQuery loaded centrally in page_header.php -->
</head>

<body topmargin="0" leftmargin="0" rightmargin="0">
  <?php
  include('page_header.php');
  ?>
  <div id="Content">
    <div class="MainContent">
      <!--div id="MainContentHead">
        <p>Hello! Update Contestant <b><?php echo $row_UpdateCon['ContestantID']; ?></b></p>
        <p>take note that any update made shall appear On the ballot page.</p>
      </div-->
      <div id="Content" class="u-pad-20">

        <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="Update Contestant" id="Add Contestant">

          <p>
          <h1>Update Contestant details <b><?php echo $row_UpdateCon['ContestantID']; ?></b></h1>
          </p>
          <p>
          <h2>take note that any update made shall appear On the ballot page.</h2>
          </p>
          <div class="responsive-form">

            <div class="form-row">
              <label class="form-label" for="ContestantID">Contestant ID</label>
              <div class="form-control"><input name="ContestantID" type="text" id="ContestantID" value="<?php echo htmlspecialchars($row_UpdateCon['ContestantID']); ?>" readonly="readonly" /></div>
            </div>

            <div class="form-row">
              <label class="form-label" for="FirstName">First Name</label>
              <div class="form-control"><input name="FirstName" type="text" id="FirstName" value="<?php echo htmlspecialchars($row_UpdateCon['FirstName']); ?>" /></div>
            </div>

            <div class="form-row">
              <label class="form-label" for="OtherName">Other Name</label>
              <div class="form-control"><input name="OtherName" type="text" id="OtherName" value="<?php echo htmlspecialchars($row_UpdateCon['OtherNames']); ?>" /></div>
            </div>

            <div class="form-row">
              <label class="form-label" for="PartyName">Party</label>
              <div class="form-control">
                <select name="PartyName" id="PartyName">
                  <option selected="selected"><?php echo htmlspecialchars($row_UpdateCon['PartyName']); ?></option>
                  <option value="AA">AA</option>
                  <option value="APC">APC</option>
                  <option value="LP">LP</option>
                  <option value="NNPP">NNPP</option>
                  <option value="PDP">PDP</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="State">State of Origin</label>
              <div class="form-control">
                <select name="State" id="State">
                  <option selected="selected"><?php echo htmlspecialchars($row_UpdateCon['State']); ?></option>
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
                  <option value="Nasarawa">Nasarawa</option>
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
              <label class="form-label" for="Position">Position</label>
              <div class="form-control">
                <select name="Position" id="Position">
                  <option><?php echo htmlspecialchars($row_UpdateCon['Position']); ?></option>
                  <option value="President">President</option>
                  <option value="Governor">Governor</option>
                  <option value="Senator">Senator</option>
                  <option value="Member">Member</option>
                  <option value="Member">State Member</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="Image">Image</label>
              <div class="form-control"><input name="Image" type="file" id="Image" title="" /></div>
            </div>

            <div class="form-row form-actions">
              <div class="form-label"></div>
              <div class="form-control"><button type="submit" class="btn">Update Contestant Info</button></div>
            </div>

          </div>
          <input type="hidden" name="MM_update" value="Update Contestant" />
        </form>
      </div>
    </div>
    <?php
    include('page_footer.php');
    ?>
    <!-- Spry initializers removed; client-side validation handled by Scripts/form-validate.js and HTML5 attributes -->
</body>

</html>