<?php
require_once('../Connections/db.php');
require_once('access_control.php');
require_once('logout.php');


// Legacy getSQLValueString removed. Use parameterized queries via db_query.

$colname_Voters = "-1";
if (isset($_GET['VoterID'])) {
  $colname_Voters = (int) $_GET['VoterID'];
}

try {
  $query_Voters = "SELECT * FROM voter WHERE VoterID = :id";
  $Voters = db_query($query_Voters, array(':id' => $colname_Voters));
  $row_Voters = db_fetch_assoc($Voters);
  $totalRows_Voters = db_rowcount($Voters);
} catch (Exception $e) {
  die($e->getMessage());
}

// If requested via AJAX, return only the profile fragment (no header/footer)
$isAjax = isset($_GET['ajax']) && $_GET['ajax'] == '1';
if ($isAjax) {
  if (!$row_Voters) {
    echo '<div class="vprofile">Voter not found.</div>';
    exit;
  }
  // Compact profile fragment for modal
?>
  <div class="vprofile">
    <div class="row-flex-gap">
      <div class="vphoto"><img src="../PollingStaff/VotersImages/<?php echo htmlspecialchars($row_Voters['Image']); ?>" alt="Voter Photo" class="vphoto-img" /></div>
      <div class="vinfo">
        <h3><?php echo htmlspecialchars($row_Voters['FirstName'] . ' ' . $row_Voters['OtherName']); ?></h3>
        <div><strong>VoterID:</strong> <?php echo htmlspecialchars($row_Voters['VoterID']); ?></div>
        <div><strong>Gender:</strong> <?php echo htmlspecialchars($row_Voters['Gender']); ?></div>
        <div><strong>Phone:</strong> <?php echo htmlspecialchars($row_Voters['Phone']); ?></div>
        <div><strong>State:</strong> <?php echo htmlspecialchars($row_Voters['State']); ?></div>
      </div>
    </div>
  </div>
<?php
  exit;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>INEC | Voter Info</title>
  <link href="CSS Files/Indexstyle.css" rel="stylesheet" type="text/css" />
  <!-- jQuery is loaded centrally in page_header.php -->
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" background="images/bg-body.jpg">
  <?php
  include('page_header.php');
  ?>
  <div id="Content">

    <div class="MainContent">
      <div id="MainContentHead">
        <center>
          <h2><span class="muted-text"><?php echo $row_Voters['FirstName']; ?></span> </h2>
        </center>
      </div>

      <div id="ContentBody">
        <form id="VID" name="form1" method="post" action="">
          <table width="494" border="2" align="center" cellpadding="4" class="table-no-border">
            <tr>
              <td> Photo
                <input name="VoterID" type="hidden" id="VoterID" value="<?php echo $row_Voters['VoterID']; ?>" />
              </td>
              <td>&nbsp;</td>
              <td>Info</td>
            </tr>
            <tr>
              <td width="164" height="188" rowspan="5" bgcolor="#FFFFFF">
                <center>
                  <img src="../PollingStaff/VotersImages/<?php echo $row_Voters['Image']; ?>" width="152" height="163" alt="image" />
                </center>
              </td>
              <td width="3" bgcolor="#FFFFFF">&nbsp;</td>
              <td width="285" bgcolor="#FFFFFF"><?php echo $row_Voters['VoterID']; ?></td>
            </tr>
            <tr>
              <td width="3" bgcolor="#FFFFFF">&nbsp;</td>
              <td bgcolor="#FFFFFF"><?php echo $row_Voters['FirstName']; ?><?php echo $row_Voters['OtherName']; ?></td>
            </tr>
            <tr>
              <td width="3" bgcolor="#FFFFFF">&nbsp;</td>
              <td bgcolor="#FFFFFF"><?php echo $row_Voters['Gender']; ?></td>
            </tr>
            <tr>
              <td width="3" bgcolor="#FFFFFF">&nbsp;</td>
              <td bgcolor="#FFFFFF"><?php echo $row_Voters['Phone']; ?></td>
            </tr>
            <tr>
              <td width="3" bgcolor="#FFFFFF">&nbsp;</td>
              <td bgcolor="#FFFFFF"><?php echo $row_Voters['State']; ?></td>
            </tr>
            <tr>

            </tr>
          </table>
        </form>
      </div>
    </div>
    <?php
    include('page_footer.php');
    ?>
</body>

</html>