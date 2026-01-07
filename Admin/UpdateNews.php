<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

$newsService = new NewsService();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Update News")) {
  $newsId = RequestInput::post('hiddenField', 'int', 0);

  if (empty($newsId)) {
    ErrorHandler::redirectWithFlash('UpdateNews.php', 'Invalid News ID', 'error');
  }

  $updateData = [
    'NewsTittle' => RequestInput::post('NewsTittle', 'trim') ?? '',
    'NewsBody' => RequestInput::post('NewsBody', 'trim') ?? '',
  ];

  $result = $newsService->update($newsId, $updateData);
  if ($result['success']) {
    ErrorHandler::redirectWithFlash('UpdateNews.php', 'News updated successfully!', 'success');
  } else {
    ErrorHandler::redirectWithFlash($editFormAction, $result['error'], 'error');
  }
}

// Get all news items for the selection dropdown
$allNews = $newsService->getAllNews();
$row_News = !empty($allNews) ? $allNews[0] : [];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Update News</title>
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
        <h2>
          <p>Update today's news Here!</p>
        </h2>
        <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="Update News" id="Update News" class="responsive-form">
          <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo htmlspecialchars($row_News['NewsID']); ?>" />

          <div class="form-row">
            <label class="form-label" for="NewsTittle">News Title</label>
            <div class="form-control">
              <input name="NewsTittle" type="text" id="NewsTittle" value="<?php echo htmlspecialchars($row_News['NewsTittle']); ?>" required minlength="10" />
            </div>
          </div>

          <div class="form-row">
            <label class="form-label" for="NewsBody">News Body</label>
            <div class="form-control">
              <textarea name="NewsBody" id="NewsBody" rows="6" required><?php echo htmlspecialchars($row_News['NewsBody']); ?></textarea>
            </div>
          </div>

          <div class="form-row form-actions">
            <div class="form-label"></div>
            <div class="form-control"><input type="submit" name="Submit" id="Submit" value="Update News" class="btn" /></div>
          </div>

          <input type="hidden" name="MM_update" value="Update News" />
        </form>
      </div>
    </div>
    <?php
    include('page_footer.php');
    ?>
  </div>
  </div>
  <!-- Spry initializers removed; client-side validation handled by Scripts/form-validate.js and HTML5 attributes -->
</body>

</html>
<?php
unset($News);
?>