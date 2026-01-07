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
  $currentPage = 'AuthenticateVoter.php';
  include('header.php');
  ?>
  <div class="body">
    <div class="register">
      <div>
        <div>
          <div class="register">
            <h2>Authenticate Voter here!</h2>
            <form method="get" action="SearchVoterResult.php" name="searchform" class="form-container">
              <fieldset>
                <legend>Search Voter Information</legend>

                <?php echo FlashRenderer::renderAll(); ?>

                <div class="form-row">
                  <label for="VoterID" class="form-label">Voter ID Number <span class="required">*</span></label>
                  <div class="form-control">
                    <input type="number" name="VoterID" id="VoterID" required placeholder="Enter voter ID to search" aria-required="true" min="1" step="1" inputmode="numeric" pattern="[0-9]+">
                  </div>
                </div>

                <div class="form-actions">
                  <label class="form-label"></label>
                  <div class="form-control">
                    <button type="submit" name="submit" class="btn btn-primary">Search Voter</button>
                    <button type="reset" name="reset" class="btn btn-secondary">Clear Form</button>
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