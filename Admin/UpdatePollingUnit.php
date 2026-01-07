<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

$pollingUnitService = new PollingUnitService();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Add Pilling Unit")) {
  $unitId = RequestInput::post('UnitID', 'int', 0);

  if (empty($unitId)) {
    ErrorHandler::redirectWithFlash('ListPollingUnit.php', 'Invalid Unit ID', 'error');
  }

  // Server-side required validation
  $required = ['State', 'LGA', 'PUName'];
  $missing = RequestInput::validateRequired($required);
  if (!empty($missing)) {
    ErrorHandler::redirectWithFlash($editFormAction, 'Please fill in all required fields: ' . implode(', ', $missing), 'warning');
  }

  $updateData = [
    'State' => RequestInput::post('State', 'trim') ?? '',
    'LGA' => RequestInput::post('LGA', 'trim') ?? '',
    'PUName' => RequestInput::post('PUName', 'trim') ?? '',
  ];

  $result = $pollingUnitService->update($unitId, $updateData);
  if ($result['success']) {
    ErrorHandler::redirectWithFlash('ListPollingUnit.php', 'Polling Unit updated successfully!', 'success');
  } else {
    ErrorHandler::redirectWithFlash($editFormAction, $result['error'], 'error');
  }
}

$unitId = RequestInput::get('UnitID', 'int', -1);
$row_UpdatePU = null;

if ($unitId > 0) {
  $row_UpdatePU = $pollingUnitService->getById($unitId, 'UnitID');
  if (!$row_UpdatePU) {
    ErrorHandler::redirectWithFlash('ListPollingUnit.php', 'Polling Unit not found', 'error');
  }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Update Polling Unit</title>
  <link href="CSS Files/Indexstyle.css" rel="stylesheet" type="text/css" />
  <!-- Replaced Spry validation with modern HTML5 + JS validator -->


</head>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" background="images/bg-body.jpg">
  <?php
  include('page_header.php');
  ?>
  <div id="Content" class="u-pad-20">
    <div class="MainContent">
      <div id="MainContentHead">
        <h2><span class="muted-text">Update Polling Unit <?php echo $row_UpdatePU['UnitID']; ?>!</span></h2>
      </div>
      <div id="ContentBody">
        <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="Add Pilling Unit" id="Add Polling Unit">
          <div class="responsive-form">

            <div class="form-row">
              <label class="form-label" for="UnitID">Polling Unit ID</label>
              <div class="form-control"><input type="text" name="UnitID" id="UnitID" readonly="readonly" value="<?php echo htmlspecialchars($row_UpdatePU['UnitID']); ?>" /></div>
            </div>

            <div class="form-row">
              <label class="form-label" for="State">State</label>
              <div class="form-control">
                <select name="State" id="State">
                  <option selected="selected"><?php echo htmlspecialchars($row_UpdatePU['State']); ?></option>
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
              <label class="form-label" for="LGA">Local Government Area</label>
              <div class="form-control"><input type="text" name="LGA" id="LGA" value="<?php echo htmlspecialchars($row_UpdatePU['LGA']); ?>" /></div>
            </div>

            <div class="form-row">
              <label class="form-label" for="PUName">PU Name</label>
              <div class="form-control"><input type="text" name="PUName" id="PUName" value="<?php echo htmlspecialchars($row_UpdatePU['PUName']); ?>" /></div>
            </div>

            <div class="form-row form-actions">
              <div class="form-label"></div>
              <div class="form-control"><button type="submit" class="btn">Update Polling Unit</button></div>
            </div>

          </div>
          <input type="hidden" name="MM_update" value="Add Pilling Unit" />
        </form>
      </div>
      <?php
      include('page_footer.php');
      ?>
    </div>
  </div>
  <script type="text/javascript">
    <!-- Spry initializers removed; client-side validation handled by Scripts/form-validate.js and HTML5 attributes 
    -->
  </script>
</body>

</html>