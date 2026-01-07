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
  <title>Admin Panel</title>
</head>

<body>
  <div id="Container">
    <?php include('page_header.php'); ?>
    <div id="Content">
      <div id="Info">
        <section class="admin-hero">
          <div class="hero-text">
            <p>
            <h1 class="AdminHead">Welcome Admin! What would you like to do today?</h1>
            </p>
            <p>
            <h2 class="AdminHead">Use the tiles below to navigate administrative actions.</h2>
            </p>
          </div>
          <div class="hero-image"><img src="images/smiley.png" alt="Welcome" /></div>
        </section>

        <nav class="tiles-grid" aria-label="Admin actions">
          <a class="tile" href="AddContestant.php">
            <img src="images/business_users_add.png" alt="Add Contestant" />
            <div class="tile-title">Add Contestant</div>
          </a>
          <a class="tile" href="AddPollingStaff.php">
            <img src="images/Add_users_plus_group_people_friends.png" alt="Add Polling Staff" />
            <div class="tile-title">Add Polling Staff</div>
          </a>
          <a class="tile" href="ListPollingStaffs.php">
            <img src="images/home2015-04-10-03-28-12pmuser-friendly.png" alt="Manage Staff" />
            <div class="tile-title">Manage Polling Staffs</div>
          </a>
          <a class="tile" href="AddPollingUnit.php">
            <img src="images/unite.png" alt="Add Polling Unit" />
            <div class="tile-title">Add Polling Unit</div>
          </a>
          <a class="tile" href="UpdateNews.php">
            <img src="images/add-notes.png" alt="Update News" />
            <div class="tile-title">Update INEC News</div>
          </a>
        </nav>
      </div>
      <?php include('page_footer.php'); ?>
    </div>
  </div>
</body>

</html>