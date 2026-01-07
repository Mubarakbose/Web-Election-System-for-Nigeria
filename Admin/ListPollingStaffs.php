<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

$staffService = new StaffService();

// Handle delete request
if ((isset($_POST['UserID'])) && ($_POST['UserID'] != "")) {
  $userId = RequestInput::post('UserID', 'int', 0);

  if ($userId > 0) {
    if ($staffService->deleteById($userId, 'UserID')) {
      ErrorHandler::redirectWithFlash('ListPollingStaffs.php', 'Staff deleted successfully!', 'success');
    } else {
      ErrorHandler::redirectWithFlash('ListPollingStaffs.php', 'Failed to delete staff', 'error');
    }
  }
}

// Setup pagination
$page = RequestInput::get('page', 'int', 1);
$totalStaffs = $staffService->getCount();
$pagination = new Pagination($totalStaffs, AdminConstants::ITEMS_PER_PAGE, $page);

// Get paginated staff list
$allStaffs = $staffService->getAll($pagination);
$row_Staffs = !empty($allStaffs) ? $allStaffs[0] : null;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Polling Staffs</title>
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

    a {
      font-size: 16px;
      font-weight: bold;
    }

    td {
      font-size: 16px;
    }

    #Polling Staffs table {
      font-size: 14px;
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
        <div id="RightImagePSS">
          <!--table width="110" align="right">
            <tr>
              <td><img src="images/Add voter.png" width="106" height="100" alt="image" /></td>
            </tr>
          </table>
        </div>
        <form method="GET" name="SearchUnit">
          <table width="482" align="center">
            <tr>
              <td height="67" colspan="2"></td>
            </tr>
            <tr>
              <td width="257" height="32"><input type="text" name="StaffID" id="StaffID" placeholder="Please Enter User ID" /></td>
              <td width="213"><input type="submit" name="search" id="search" value="Search Staff" class="btn"/></td>
            </tr>
          </table>
        </form-->
        </div>
        <div id="ContentBody">
          <h2>
            <p>Here are the available Polling staffs. you can manage our staffs here.</p>
          </h2>

          <form action="" method="post" enctype="multipart/form-data" name="Pilling Staffs" id="Polling Staffs">
            <div class="detail-list">
              <?php if (!empty($allStaffs)): ?>
                <?php foreach ($allStaffs as $row_Staffs): ?>
                  <article class="detail-item">
                    <div class="meta"><?php echo htmlspecialchars($row_Staffs['UserID']); ?></div>
                    <div class="main">
                      <strong><?php echo htmlspecialchars($row_Staffs['FirstName'] . ' ' . $row_Staffs['LastName']); ?></strong>
                      <div class="detail-body">
                        <div><strong>Unit ID:</strong> <?php echo htmlspecialchars($row_Staffs['UnitID']); ?></div>
                        <div><strong>Phone:</strong> <?php echo htmlspecialchars($row_Staffs['PhoneNumber'] ?? 'N/A'); ?></div>
                      </div>
                    </div>
                    <div class="actions">
                      <a href="UpdatePollingStaff.php?UserID=<?php echo urlencode($row_Staffs['UserID']); ?>">Update</a>
                      <a href="DeleteStaff.php?UserID=<?php echo urlencode($row_Staffs['UserID']); ?>">Delete</a>
                    </div>
                  </article>
                <?php endforeach; ?>
              <?php else: ?>
                <p>No polling staff found.</p>
              <?php endif; ?>
            </div>
            <div class="pagination">
              <?php echo $pagination->renderHtml('ListPollingStaffs.php?page='); ?>
            </div>
          </form>
          <?php
          include('page_footer.php');
          ?>
        </div>
      </div>
</body>

</html>