<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

$contestantService = new ContestantService();

$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ResultMode")) {
  $resultMode = RequestInput::post('ResultMode', 'trim') ?? 'Private';

  // Update all contestants with the result mode
  $result = db_query("UPDATE contestant SET ResultMode = :mode", [':mode' => $resultMode]);

  if ($result) {
    ErrorHandler::redirectWithFlash('ViewResult.php', 'Result Mode changed successfully!', 'success');
  } else {
    ErrorHandler::redirectWithFlash($editFormAction, 'Failed to update result mode', 'error');
  }
}

// Setup pagination for public results
$page = RequestInput::get('pageNum_Recordset1', 'int', 1);

// Get ALL contestants (both public and private) and sort by Position and Votes
$sql = "SELECT * FROM contestant ORDER BY 
  CASE Position 
    WHEN 'President' THEN 1 
    WHEN 'Governor' THEN 2 
    WHEN 'Senator' THEN 3 
    WHEN 'Member' THEN 4 
    ELSE 5 
  END, Votes DESC";
$allResult = db_query($sql);
$allContestants = db_fetch_all($allResult) ?? [];
$totalRows = count($allContestants);

$pagination = new Pagination($totalRows, 5, $page);
// Get paginated slice
$offset = $pagination->getOffset();
$row_Recordset1 = !empty($allContestants) ? $allContestants[0] : null;
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Result</title>
  <link href="CSS Files/Indexstyle.css" rel="stylesheet" type="text/css" />
  <!-- Replaced Spry validation with modern HTML5 + JS validator -->
  <!-- jQuery loaded centrally in page_header.php -->
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" background="images/bg-body.jpg">
  <?php
  include('page_header.php');
  ?>
  <div id="Content">
    <div class="MainContent">
      <div id="MainContentHead">

      </div>
      <div id="ContentBody">
        <h1>
          <p>election result!</p>
        </h1>
        <fieldset>
          <legend>Make Election Results Public</legend>
          <form action="<?php echo $editFormAction; ?>" method="POST" name="ResultMode">
            <div class="form-row">
              <label class="form-label" for="ResultMode">Results Mode</label>
              <div class="form-control">
                <select name="ResultMode" id="ResultMode" required>
                  <option value="" disabled selected>Please Choose One</option>
                  <option value="Public">Public</option>
                  <option value="Private">Private</option>
                </select>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn">Change Mode</button>
                <input name="ContestantID" type="hidden" id="ContestantID" value="<?php echo $row_UpdateResult['ContestantID']; ?>" />
              </div>
            </div>
            <input type="hidden" name="MM_update" value="ResultMode" />
          </form>
        </fieldset>
        <fieldset>
          <legend>Results</legend>

          <?php if (!empty($allContestants)): ?>
            <div class="detail-list">
              <?php foreach (array_slice($allContestants, $offset, 5) as $row_Recordset1):
                $isPublic = ($row_Recordset1['ResultMode'] ?? 'Private') === 'Public';
                $statusClass = $isPublic ? 'public' : 'private';
                $statusLabel = $isPublic ? 'PUBLIC' : 'PRIVATE';
              ?>
                <article class="detail-item" style="border-left: 4px solid <?php echo $isPublic ? '#4CAF50' : '#f44336'; ?>;">
                  <div class="meta"><img src="ContestantsImages/<?php echo htmlspecialchars($row_Recordset1['Image']); ?>" alt="" class="contestant-thumb" /></div>
                  <div class="main">
                    <strong><?php echo htmlspecialchars($row_Recordset1['FirstName'] . ' ' . $row_Recordset1['OtherNames']); ?></strong>
                    <span style="background: <?php echo $isPublic ? '#4CAF50' : '#f44336'; ?>; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; margin-left: 10px;"><?php echo $statusLabel; ?></span>
                    <div class="detail-body">
                      <div><strong>Party:</strong> <?php echo htmlspecialchars($row_Recordset1['PartyName']); ?></div>
                      <div><strong>State:</strong> <?php echo htmlspecialchars($row_Recordset1['State']); ?></div>
                      <div><strong>Position:</strong> <?php echo htmlspecialchars($row_Recordset1['Position']); ?></div>
                      <div><strong>Votes:</strong> <?php echo htmlspecialchars($row_Recordset1['Votes']); ?></div>
                    </div>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="text-center">No contestants available.</p>
          <?php endif; ?>
        </fieldset>
        <p style="color: #666; font-size: 12px; margin: 10px 0;">
          Showing <?php echo min($offset + 1, $totalRows); ?> - <?php echo min($offset + 5, $totalRows); ?> of <?php echo $totalRows; ?> contestants
          (Page <?php echo $pagination->getCurrentPage(); ?> of <?php echo $pagination->getTotalPages(); ?>)
        </p>
        <nav class="pagination" aria-label="Result pagination">
          <?php echo $pagination->renderHtml($currentPage . '?pageNum_Recordset1='); ?>
        </nav>
      </div>
      <?php
      include('page_footer.php');
      ?>
    </div>
  </div>
  <!-- Spry initializers removed; client-side validation handled by Scripts/form-validate.js and HTML5 attributes -->
</body>

</html>