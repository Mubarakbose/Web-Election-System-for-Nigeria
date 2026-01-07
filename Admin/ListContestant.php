<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

// Initialize service
$contestantService = new ContestantService();

// Handle delete request
if (RequestInput::hasPost('ContestantID')) {
  $contestantId = RequestInput::post('ContestantID', 'int');
  if ($contestantId > 0) {
    if ($contestantService->deleteById($contestantId, 'ContestantID')) {
      ErrorHandler::redirectWithFlash('ListContestant.php', 'Contestant deleted successfully!', 'success');
    } else {
      ErrorHandler::redirectWithFlash('ListContestant.php', 'Error deleting contestant.', 'error');
    }
  }
}

// Get total count and set up pagination
$totalContestants = $contestantService->getCount();
$currentPage = RequestInput::get('page', 'int', 1);
$pagination = new Pagination($totalContestants, AdminConstants::ITEMS_PER_PAGE, $currentPage);

// Fetch contestants for current page
$contestants = $contestantService->getAll($pagination);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contestants List</title>
  <link href="CSS Files/Indexstyle.css" rel="stylesheet" type="text/css" />
  <!-- jQuery loaded centrally in page_header.php -->
  <style type="text/css">
    a:link {
      color: #FFF;
      text-decoration: none;
      font-weight: bold;
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
      font-weight: bold;
      font-size: 16px;
    }

    .pagination {
      padding-left: 20px;
      font-style: none;
    }
  </style>
</head>

<body background="images/bg-body.jpg" leftmargin="0" topmargin="0" rightmargin="0">
  <?php
  include('page_header.php');
  ?>
  <div id="Content">
    <div class="MainContent">
      <div id="MainContentHead">
        <div id="RightImageListCon">
          <table width="110" align="right">
            <tr>
              <td><img src="images/business_users_add.png" width="120" height="104" alt="image" /></td>
            </tr>
          </table>
        </div>
        <!--form action="ContestantSearchResult.php" method="get" name="Search" id="Search">
          <td height="75" colspan="2">
          </td>
          </tr>
          <tr>
            <td width="257" height="15"><input type="text" name="ContestantID" id="ContestantID" placeholder="Enter Contestant First Name" /></td>
            <td width="213"><input type="submit" name="Button" id="search" value="Search Contestant" /></td>
          </tr>
          </table>
        </form-->
      </div>
      <div id="ContentBody">
        <div id="SearchResult"></div>
        <h2>
          <p>
            Here are the available Contestant in our database. You can update or delete a contestant here!
          </p>
        </h2>
        <form method="POST" enctype="multipart/form-data" name="News" id="News">
          <div class="detail-list">
            <?php if (!empty($contestants)) : ?>
              <?php foreach ($contestants as $row_Contestant) : ?>
                <article class="detail-item">
                  <div class="meta"><?php echo htmlspecialchars($row_Contestant['ContestantID']); ?></div>
                  <div class="main">
                    <strong><?php echo htmlspecialchars($row_Contestant['FirstName'] . ' ' . $row_Contestant['OtherNames']); ?></strong>
                    <div class="detail-body">
                      <div><strong>Position:</strong> <?php echo htmlspecialchars($row_Contestant['Position']); ?></div>
                      <div><strong>State:</strong> <?php echo htmlspecialchars($row_Contestant['State']); ?></div>
                    </div>
                  </div>
                  <div class="actions">
                    <a href="UpdateContestant.php?ContestantID=<?php echo urlencode($row_Contestant['ContestantID']); ?>">Update</a>
                    <a href="DeleteConts.php?ContestantID=<?php echo urlencode($row_Contestant['ContestantID']); ?>">Delete</a>
                  </div>
                </article>
              <?php endforeach; ?>
            <?php else : ?>
              <p class="text-center">No contestants found.</p>
            <?php endif; ?>
          </div>

          <?php echo $pagination->renderHtml('ListContestant.php?page='); ?>
        </form>
      </div>

    </div>
    <?php
    include('page_footer.php');
    ?>
  </div>
  </div>
</body>

</html>