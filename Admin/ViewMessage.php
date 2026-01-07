<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

$messageService = new MessageService();
$currentPage = $_SERVER['PHP_SELF'];

// Setup pagination
$page = RequestInput::get('pageNum_Messages', 'int', 1);
$totalMessages = $messageService->getCount();
$pagination = new Pagination($totalMessages, AdminConstants::ITEMS_PER_PAGE, $page);

// Get paginated messages
$allMessages = $messageService->getAll($pagination);
$row_Messages = !empty($allMessages) ? $allMessages[0] : null;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title> Messages</title>
  <link href="CSS Files/Indexstyle.css" rel="stylesheet" type="text/css" />
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
          <p>Your Messages!</p>
        </h1>
        <form action="" method="post" enctype="multipart/form-data" name="News" id="News">
          <div class="detail-list">
            <?php if (!empty($allMessages)): ?>
              <?php foreach ($allMessages as $row_Messages): ?>
                <article class="detail-item">
                  <div class="meta">#<?php echo htmlspecialchars($row_Messages['MessageID']); ?></div>
                  <div class="main">
                    <strong><?php echo htmlspecialchars($row_Messages['MessageTittle']); ?></strong>
                    <div class="detail-body">
                      <div><strong>Received:</strong> <?php echo htmlspecialchars($row_Messages['MsgTimeStamp']); ?></div>
                      <div><strong>Sender:</strong> <?php echo htmlspecialchars($row_Messages['StaffName']); ?></div>
                      <div><strong>Staff ID:</strong> <?php echo htmlspecialchars($row_Messages['StaffID']); ?> &nbsp; <strong>Unit ID:</strong> <?php echo htmlspecialchars($row_Messages['UnitID']); ?></div>
                      <div class="u-mt-8"><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($row_Messages['MainMessage'])); ?></div>
                    </div>
                  </div>
                </article>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-center">No messages found.</p>
            <?php endif; ?>
          </div>
        </form>

        <nav class="pagination" aria-label="Messages pagination">
          <?php echo $pagination->renderHtml($currentPage . '?pageNum_Messages='); ?>
        </nav>

      </div>
    </div>
    <?php include('page_footer.php'); ?>
  </div>
</body>

</html>