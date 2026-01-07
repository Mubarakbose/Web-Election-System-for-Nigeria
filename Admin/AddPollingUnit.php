<?php
require_once('../Connections/db.php');
require_once('access_control.php');
require_once('logout.php');
require_once('../Connections/db.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Polling Unit</title>
  <link href="CSS Files/Indexstyle.css" rel="stylesheet" type="text/css" />
  <!-- jQuery loaded centrally in page_header.php -->
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" background="images/bg-body.jpg">
  <?php
  include('page_header.php');
  ?>
  <div id="Content">
    <div class="MainContent">
      <div id="MainContentHead">
        <div id="RightImageAddPU"></div>
      </div>
      <div id="ContentBody">
        <h1>Add Polling units Here!</h1>
        <h2>
          <p>Please enter valid information. Thank you!</p>
        </h2>
        <form action="Scripts/AddPollingUnitScript.php" method="POST" enctype="multipart/form-data" name="Add Pilling Unit" id="Add Polling Unit">
          <div class="responsive-form">

            <div class="form-row">
              <label class="form-label" for="State">State</label>
              <div class="form-control">
                <select name="State" id="State" required aria-required="true">
                  <option selected="selected">Please Select One</option>
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
              <label class="form-label" for="LGA">Local Government Area</label>
              <div class="form-control"><input type="text" name="LGA" id="LGA" required data-minlength="2" aria-required="true" />
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="PUName">PU Name</label>
              <div class="form-control"><input name="PUName" type="text" id="PUName" maxlength="200" required data-minlength="5" aria-required="true" />
              </div>
            </div>

            <div class="form-row form-actions">
              <div class="form-label"></div>
              <div class="form-control">
                <input type="submit" name="Submit" id="Submit" value="Add Polling Unit" class="btn" />
                <input type="reset" name="ClearForm" id="ClearForm" value="Clear Form" class="btn" />
              </div>
            </div>

          </div>
          <!-- legacy buttons removed (now in form-actions) -->
        </form>
      </div>
    </div>
    <?php
    include('page_footer.php');
    ?>
  </div>
</body>

</html>