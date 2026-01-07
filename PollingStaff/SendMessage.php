<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

// Get staff info
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
  $currentPage = 'SendMessage.php';
  include('header.php');
  ?>
  <div class="body">
    <div class="register">
      <div>
        <div>
          <div class="register">
            <h2>Send emergency message to <br>
              inec it center!</h2>
            <form action="Scripts/SendMessageScript.php" method="post" name="SendMessage" id="SendMessage" class="form-container">
              <fieldset>
                <legend>Send Emergency Message</legend>

                <?php echo FlashRenderer::renderAll(); ?>

                <p style="color: #d32f2f; font-size: 15px; text-align: justify;">INEC will receive your message. Our technical team will respond to your issue ASAP. Thank you!</p>

                <div class="form-row">
                  <label for="StaffID" class="form-label">Your ID Number</label>
                  <div class="form-control">
                    <input type="text" name="StaffID" id="StaffID" value="<?php echo $row_Staff['UserID']; ?>" readonly title="Read Only">
                    <small class="help-text">Read Only</small>
                  </div>
                </div>

                <div class="form-row">
                  <label for="StaffName" class="form-label">Your Full Name</label>
                  <div class="form-control">
                    <input type="text" name="StaffName" id="StaffName" value="<?php echo $row_Staff['FirstName'] . " " . $row_Staff['LastName']; ?>" readonly title="Read Only">
                    <small class="help-text">Read Only</small>
                  </div>
                </div>

                <div class="form-row">
                  <label for="UnitID" class="form-label">Your Polling Unit ID</label>
                  <div class="form-control">
                    <input type="text" name="UnitID" id="UnitID" value="<?php echo $row_Staff['UnitID']; ?>" readonly title="Read Only">
                    <small class="help-text">Read Only</small>
                  </div>
                </div>

                <div class="form-row">
                  <label for="MessageTittle" class="form-label">Message Title <span class="required">*</span></label>
                  <div class="form-control">
                    <input type="text" name="MessageTittle" id="MessageTittle" required minlength="10" maxlength="50" aria-required="true" placeholder="Brief subject of your message">
                  </div>
                </div>

                <div class="form-row">
                  <label for="MainMessage" class="form-label">Message <span class="required">*</span></label>
                  <div class="form-control">
                    <textarea name="MainMessage" id="MainMessage" required minlength="10" maxlength="255" aria-required="true" rows="5" placeholder="Describe your issue in detail"></textarea>
                  </div>
                </div>

                <div class="form-actions">
                  <label class="form-label"></label>
                  <div class="form-control">
                    <button type="submit" name="SendMessage2" id="SendMessage2" class="btn btn-primary">Send Message</button>
                    <button type="reset" name="reset" id="reset" class="btn btn-secondary">Clear Form</button>
                  </div>
                </div>
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