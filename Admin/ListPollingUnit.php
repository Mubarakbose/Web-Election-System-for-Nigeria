<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

$pollingUnitService = new PollingUnitService();

// Handle delete request
if (isset($_POST['UnitID']) && $_POST['UnitID'] != "") {
  $unitId = RequestInput::post('UnitID', 'int', 0);

  if ($unitId > 0) {
    if ($pollingUnitService->deleteById($unitId, 'UnitID')) {
      ErrorHandler::redirectWithFlash('ListPollingUnit.php', 'Polling unit deleted successfully!', 'success');
    } else {
      ErrorHandler::redirectWithFlash('ListPollingUnit.php', 'Failed to delete polling unit', 'error');
    }
  } else {
    ErrorHandler::redirectWithFlash('ListPollingUnit.php', 'Invalid Unit ID', 'error');
  }
}

// Setup pagination
$page = RequestInput::get('page', 'int', 1);
$totalUnits = $pollingUnitService->getCount();
$pagination = new Pagination($totalUnits, AdminConstants::ITEMS_PER_PAGE, $page);

// Get paginated polling units list
$allUnits = $pollingUnitService->getAll($pagination);
$row_ListPUnits = !empty($allUnits) ? $allUnits[0] : null;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Polling Units</title>
  <link href="CSS Files/Indexstyle.css" rel="stylesheet" type="text/css" />
  <!-- jQuery loaded centrally in page_header.php -->
  <style type="text/css">
    a:link {
      color: #CCC;
      font-weight: bold;
      text-decoration: none;
    }

    a:visited {
      text-decoration: none;
      color: #CCC;
    }

    a:hover {
      text-decoration: none;
      color: #FFF;
    }

    a:active {
      text-decoration: none;
    }

    .pagination {
      padding-left: 15px;
    }
  </style>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" background="images/bg-body.jpg">
  <?php
  include('page_header.php');
  ?>
  <div id="Content">
    <div class="MainContent">
      <div id="MainContentHead">
        <div id="RightImageListPU"></div>
        <!--form method="post" name="SearchUnit" id="SearchUnit">
          <table width="482" align="center">
            <tr>
              <td height="75" colspan="2">
              </td>
            </tr>
            <tr>
              <td width="257" height="43"><input type="text" name="Searchbox" id="Searchbox" placeholder="Enter Unit ID" /></td>
              <td width="213"><input type="submit" name="button" id="button" value="Search Polling Unit" class="btn"/></td>
            </tr>
          </table>
        </form-->
      </div>
      <div id="ContentBody">
        <h2>
          <p>Below are the available Polling Units. you can manage the polling units here.</p>
        </h2>
        <form method="post" enctype="multipart/form-data" name="News" id="News">
          <div class="detail-list">
            <?php if (!empty($allUnits)): ?>
              <?php foreach ($allUnits as $row_ListPUnits): ?>
                <article class="detail-item">
                  <div class="meta"><?php echo htmlspecialchars($row_ListPUnits['UnitID']); ?></div>
                  <div class="main">
                    <strong><?php echo htmlspecialchars($row_ListPUnits['PUName']); ?></strong>
                    <div class="detail-body">
                      <div><strong>Location:</strong> <?php echo htmlspecialchars($row_ListPUnits['State'] . ', ' . $row_ListPUnits['LGA']); ?></div>
                    </div>
                  </div>
                  <div class="actions">
                    <a href="UpdatePollingUnit.php?UnitID=<?php echo urlencode($row_ListPUnits['UnitID']); ?>">Update</a>
                    <a href="DeleteUnit.php?UnitID=<?php echo urlencode($row_ListPUnits['UnitID']); ?>">Delete</a>
                  </div>
                </article>
              <?php endforeach; ?>
            <?php else: ?>
              <p>No polling units found.</p>
            <?php endif; ?>
          </div>
        </form>
        <div class="pagination">
          <?php echo $pagination->renderHtml('ListPollingUnit.php?page='); ?>
        </div>
      </div>
    </div>
    <?php
    include('page_footer.php');
    ?>
</body>

</html>