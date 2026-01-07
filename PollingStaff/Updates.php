<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

// Current staff
$row_Staff = StaffContext::current();

// Load news entries
try {
  $News = db_query("SELECT * FROM news");
  $row_News = db_fetch_assoc($News);
  $totalRows_News = db_rowcount($News);
} catch (Exception $e) {
  die($e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>News From INEC</title>
  <link rel="stylesheet" href="PStaff CSS/unified-responsive.css" type="text/css">
  <link rel="stylesheet" href="PStaff CSS/admin-form-override.css" type="text/css">
  <link rel="stylesheet" href="PStaff CSS/footer-modern.css" type="text/css">
</head>

<body>
  <?php
  $staffFirstName = $row_Staff['FirstName'];
  $currentPage = 'Updates.php';
  include('header.php');
  ?>
  <div class="body">
    <div class="register">
      <div>
        <div>
          <div class="register">
            <h2>news &amp; Updates from HQ!</h2>
            <fieldset>
              <legend>Here's The Latest News From INEC</legend>
              <form action="" enctype="multipart/form-data">
                <table width="789" align="center" cellpadding="10">
                  <tr style="font-size:24px; font-weight:bold; color:#666;">
                    <td width="781"><?php echo isset($row_News['NewsTittle']) ? $row_News['NewsTittle'] : ''; ?></td>
                  </tr>
                  <tr>
                    <td style="font-size:20px; text-transform:capitalize; text-align:justify; color:#633"><?php echo isset($row_News['NewsBody']) ? $row_News['NewsBody'] : ''; ?></td>
                  </tr>
                  <tr>
                    <td>
                      <font color="red">Last Upadted On: <?php echo isset($row_News['UpdateTimeStamp']) ? $row_News['UpdateTimeStamp'] : ''; ?></font>
                    </td>
                  </tr>
                </table>
              </form>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include('footer.php'); ?>
</body>

</html>