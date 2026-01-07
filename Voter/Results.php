<?php
require_once('bootstrap.php');
require_once('access_control.php');

$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";

if (!empty($_SERVER['QUERY_STRING'])) {
  $logoutAction .= '&' . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_GET['doLogout']) && $_GET['doLogout'] == "true") {
  $_SESSION = array();
  session_destroy();
  header("Location: ../Index.php");
  exit;
}

$colname_Voter = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Voter = $_SESSION['MM_Username'];
}

$query_Voter = "SELECT * FROM voter WHERE UserName = ?";
$Voter = db_query($query_Voter, [$colname_Voter]);
$row_Voter = db_fetch_assoc($Voter);

// Get all positions that have public results
try {
  $positions = db_query("SELECT DISTINCT Position FROM contestant WHERE ResultMode = 'Public' ORDER BY 
    CASE Position 
      WHEN 'President' THEN 1 
      WHEN 'Governor' THEN 2 
      WHEN 'Senator' THEN 3 
      WHEN 'Member' THEN 4 
      ELSE 5 
    END");
} catch (Exception $e) {
  die($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>INEC | Election Results</title>

  <link href="../CSS Style/VoterGuideStyle.css" rel="stylesheet" type="text/css" />
  <link href="style.css?v=20251227" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body topmargin="35" bgcolor="#006666" style="text-transform:capitalize;">
  <div id="Container">
    <div id="flag"></div>
    <div id="Adminlogin">
      <header id="voterHeader">
        <div class="welcome">Welcome <?php echo htmlspecialchars($row_Voter['FirstName']); ?> <?php echo htmlspecialchars($row_Voter['OtherName']); ?></div>
        <nav>
          <ul id="MenuBar1" class="MenuBarHorizontal">
            <li><a href="VoterIndex.php">Home</a></li>
            <li><a href="vote.php">Vote</a></li>
            <li><a href="Results.php">Results</a></li>
            <li><a href="<?php echo $logoutAction ?>">Logout</a></li>
          </ul>
        </nav>
      </header>

      <main>
        <h1 style="margin-top:8px;">Election Results</h1>
        <p style="margin-bottom:20px; font-size:14px;">
        <h2>Select a position to view results</h2>
        </p>

        <div class="tiles-grid">
          <?php while ($pos = db_fetch_assoc($positions)):
            $position = htmlspecialchars($pos['Position']);
          ?>
            <div class="tile position-result-card" data-position="<?php echo $position; ?>">
              <h4><?php echo $position; ?></h4>
              <p style="font-size:12px; margin-top:8px;">View Results</p>
            </div>
          <?php endwhile; ?>
        </div>

        <div id="results-section" style="display:none; margin-top:30px; padding:20px; background:#f9f9f9; border-radius:8px;">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 id="results-title">Results</h2>
            <button id="results-close-btn" style="background:#d32f2f; color:white; border:none; padding:8px 16px; border-radius:4px; cursor:pointer;">Close</button>
          </div>
          <div id="results-grid" class="results-grid"></div>
        </div>

        <p style="margin-top:14px; font-size:13px; color:#ccc;">
        <h3>If you can't see results, they may not be released for public viewing yet.</h3>
        </p>
      </main>
    </div>
  </div>

  <script type="text/javascript">
    $(document).ready(function() {
      $(".position-result-card").on("click", function() {
        var position = $(this).data("position");

        $.ajax({
          url: "getResults.php",
          type: "post",
          data: {
            position: position
          },
          success: function(data) {
            $("#results-title").text(position + " Results");
            $("#results-grid").html(data);
            $("#results-section").slideDown(300);
            $("html, body").animate({
              scrollTop: $("#results-section").offset().top - 100
            }, 500);
          },
          error: function() {
            alert("Error loading results. Please try again.");
          }
        });
      });

      $("#results-close-btn").on("click", function(e) {
        e.preventDefault();
        $("#results-section").slideUp(300);
      });
    });
  </script>
</body>

</html>