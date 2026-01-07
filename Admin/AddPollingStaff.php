<?php
require_once('../Connections/db.php');
require_once('access_control.php');
require_once('logout.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Add Polling Staff</title>
  <link href="CSS Files/Indexstyle.css" rel="stylesheet" type="text/css" />
  <!-- jQuery loaded centrally in page_header.php -->
</head>


<body background="images/bg-body.jpg" leftmargin="0" topmargin="0" rightmargin="0">
  <?php
  include('page_header.php');
  ?>
  <div id="Content">
    <div class="MainContent">
      <div id="MainContentHead">
        <div id="RightImageAddCon">
          <table width="150" align="right">
            <tr>
              <td><img src="images/Add_users_plus_group_people_friends.png" width="146" height="110" alt="image" /></td>
            </tr>
          </table>
        </div>

      </div>
      <div id="ContentBody">
        <p>
        <h1>Hello!</h1>
        </p>
        <p>
        <h2>Make sure that you enter accurate staff information. Thank you!</h2>
        </p>
        <form method="POST" enctype="multipart/form-data" name="Add Pilling Staff" id="Add Polling Staff" action="Scripts/AddPollingStaffScript.php">

          <div class="responsive-form">

            <div class="form-row">
              <label class="form-label" for="FirstName">First Name</label>
              <div class="form-control">
                <input type="text" name="FirstName" id="FirstName" required data-minlength="3" aria-required="true" />
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="LastName">Other Name</label>
              <div class="form-control">
                <input type="text" name="LastName" id="LastName" required data-minlength="3" aria-required="true" />
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="BirthDate">Birth Date</label>
              <div class="form-control">
                <input name="BirthDate" type="date" required id="BirthDate" max="2003-12-31" min="1965-12-31" aria-required="true">
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="Gender">Gender</label>
              <div class="form-control">
                <select name="Gender" id="Gender" required aria-required="true">
                  <option value="">Please Select One</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="PhoneNumber">Phone Number</label>
              <div class="form-control">
                <input name="PhoneNumber" type="tel" id="PhoneNumber" maxlength="15" required data-minlength="10" aria-required="true" />
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="Image">Image</label>
              <div class="form-control">
                <input type="file" name="Image" id="Image" />
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="UserName">Username</label>
              <div class="form-control">
                <input type="text" name="UserName" id="UserName" required data-minlength="6" aria-required="true" />
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="Password">Password</label>
              <div class="form-control">
                <input type="password" name="Password" id="Password" required data-minlength="6" aria-required="true" />
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="ConfirmPassword">Confirm Password</label>
              <div class="form-control">
                <input type="password" name="ConfirmPassword" id="ConfirmPassword" required data-minlength="6" data-match="#Password" aria-required="true" />
              </div>
            </div>

            <div class="form-row form-actions">
              <div class="form-label"></div>
              <div class="form-control">
                <input type="submit" name="Submit" id="Submit" value="Add Staff" class="btn" />
                <input type="reset" name="ClearForm" id="ClearForm" value="Clear Form" class="btn" />
              </div>
            </div>

          </div>
          <input type="hidden" name="MM_insert" value="Add Pilling Staff" />
        </form>
      </div>
    </div>
    <?php
    include('page_footer.php');
    ?>
  </div>
</body>

</html>
<?php
?>