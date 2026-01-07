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
  <link href="CSS Files/Indexstyle.css" rel="stylesheet" type="text/css" />
  <!-- jQuery loaded centrally in page_header.php -->

</head>


<title>Add Contestants</title>

<body topmargin="0" leftmargin="0" rightmargin="0">
  <?php
  include('page_header.php');
  ?>
  <div id="Content">
    <div class="MainContent">
      <div id="MainContentHead">
        <div id="RightImageAddCon">
          <table width="150" align="right">
            <tr>
              <td><img src="images/business_users_add.png" width="112" height="95" alt="image" /></td>
            </tr>
          </table>
        </div>
      </div>

      <div id="ContentBody">
        <p>
        <h1> Hello! </h1>
        </p>
        <p>
        <h2> Welcome to the Add Contestant Page. </h2>
        </p>
        <p>Please fill out the form below to add a new contestant. Note that any contestant you add here will appear on the ballot page.</p>
        <form action="Scripts/AddContestantScript.php" method="POST" enctype="multipart/form-data" name="Add Contestant" id="Add Contestant">
          <div class="responsive-form">

            <div class="form-row">
              <label class="form-label" for="FirstName">First Name</label>
              <div class="form-control">
                <input type="text" name="FirstName" id="FirstName" value="" required data-minlength="3" aria-required="true" />
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="OtherNames">Other Name</label>
              <div class="form-control">
                <input type="text" name="OtherNames" id="OtherNames" value="" required data-minlength="3" aria-required="true" />
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="PartyName">Party</label>
              <div class="form-control">
                <input type="text" name="PartyName" id="PartyName" value="" required data-minlength="2" aria-required="true" placeholder="Enter party name or code" />
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="State">State of Origin</label>
              <div class="form-control">
                <select name="State" id="State" required aria-required="true">
                  <option>Please Select One Option</option>
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
              <label class="form-label" for="Position">Position</label>
              <div class="form-control">
                <select name="Position" id="Position" required aria-required="true">
                  <option value="">Please Select One Option</option>
                  <option value="President">President</option>
                  <option value="Governor">Governor</option>
                  <option value="Senator">Senator</option>
                  <option value="Member">Member</option>
                  <option value="State Member">State Member</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="SenateZone">Senatorial Zone</label>
              <div class="form-control">
                <select name="SenateZone" id="SenateZone" required aria-required="true">
                  <option value="">Please Select One Option</option>
                  <option value="Central">Central</option>
                  <option value="East">East</option>
                  <option value="North">North</option>
                  <option value="South">South</option>
                  <option value="West">West</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="FedConstituency">Federal Constituency</label>
              <div class="form-control">
                <input type="text" name="FedConstituency" id="FedConstituency" value="" required data-minlength="2" aria-required="true" />
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="StateConstituency">State Constituency</label>
              <div class="form-control">
                <input type="text" name="StateConstituency" id="StateConstituency" value="" required data-minlength="2" aria-required="true" />
              </div>
            </div>

            <div class="form-row">
              <label class="form-label" for="Image">Image</label>
              <div class="form-control"><input type="file" name="Image" id="Image" /></div>
            </div>

            <div class="form-row form-actions">
              <div class="form-label"></div>
              <div class="form-control">
                <input type="Submit" name="Submit" id="Submit" value="Add Contestant" class="btn" />
                <input type="reset" name="ClearForm" id="ClearForm" value="Clear Form" class="btn" />
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php
    include('page_footer.php');
    ?>
  </div>
</body>

</html>