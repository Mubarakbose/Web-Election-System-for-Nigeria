<?php
require_once('../Connections/db.php');
require_once('access_control.php');
require_once('logout.php');

// Pagination variables
$maxRows = 10;
$pageNum = 1;
if (isset($_GET['pageNum']) && is_numeric($_GET['pageNum'])) {
    $pageNum = $_GET['pageNum'];
}
$startRow = ($pageNum - 1) * $maxRows;

// Modify the database query to include LIMIT
$query_Voters = "SELECT * FROM voter LIMIT $startRow, $maxRows";
try {
    $Voters = db_query($query_Voters);
    $row_Voters = db_fetch_assoc($Voters);
    $totalRows_Voters = db_rowcount($Voters);

    // Get the total number of records
    $query_CountVoters = "SELECT COUNT(*) AS total_records FROM voter";
    $CountVoters = db_query($query_CountVoters);
    $row_CountVoters = db_fetch_assoc($CountVoters);
    $totalRecords = $row_CountVoters['total_records'];

    // Calculate the total number of pages
    $totalPages = ceil($totalRecords / $maxRows);
} catch (Exception $e) {
    die($e->getMessage());
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>INEC | Registered Voters</title>
    <link href="CSS Files/Indexstyle.css" rel="stylesheet" type="text/css" />
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
                <p>
                <h1>Registered Voters</h1>
                </p>
                <p>
                <h2>These are the registered voters. You can manage the voters here.</h2>
                </p>
                <div class="detail-list">
                    <?php do { ?>
                        <article class="detail-item">
                            <div class="meta"><?php echo htmlspecialchars($row_Voters['VoterID']); ?></div>
                            <div class="main">
                                <strong><?php echo htmlspecialchars($row_Voters['FirstName'] . ' ' . $row_Voters['OtherName']); ?></strong>
                                <div class="detail-body">
                                    <div><strong>VoterID:</strong> <?php echo htmlspecialchars($row_Voters['VoterID']); ?></div>
                                </div>
                            </div>
                            <div class="actions">
                                <button type="button" class="open-vprofile btn" data-voterid="<?php echo htmlspecialchars($row_Voters['VoterID']); ?>">View Voter</button>
                                <noscript><a href="VProfile.php?VoterID=<?php echo urlencode($row_Voters['VoterID']); ?>">View Voter</a></noscript>
                            </div>
                        </article>
                    <?php } while ($row_Voters = db_fetch_assoc($Voters)); ?>
                </div>

                <!-- Pagination Links -->
                <div class="pagination">
                    <?php if ($pageNum > 1) { ?>
                        <a href="<?php echo $_SERVER['PHP_SELF'] . '?pageNum=' . ($pageNum - 1); ?>">Previous</a>
                    <?php } ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                        <?php if ($i == $pageNum) { ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php } else { ?>
                            <a href="<?php echo $_SERVER['PHP_SELF'] . '?pageNum=' . $i; ?>"><?php echo $i; ?></a>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($pageNum < $totalPages) { ?>
                        <a href="<?php echo $_SERVER['PHP_SELF'] . '?pageNum=' . ($pageNum + 1); ?>">Next</a>
                    <?php } ?>
                </div>
            </div>

            <!-- Voter profile modal -->
            <div id="vprofile-modal" aria-hidden="true">
                <div class="vp-overlay"></div>
                <div class="vp-panel" role="dialog" aria-modal="true">
                    <button class="vp-close" aria-label="Close">&times;</button>
                    <div class="vp-body">Loading...</div>
                </div>
            </div>
        </div>
        <?php
        include('page_footer.php');
        ?>
        <!-- Modal styles moved to central stylesheet (CSS Files/Indexstyle.css) -->
        <script type="text/javascript">
            (function($) {
                function openModal() {
                    $('#vprofile-modal').show().attr('aria-hidden', 'false');
                    // prevent background scrolling
                    document.documentElement.classList.add('modal-open');
                    // move focus to modal
                    setTimeout(function() {
                        $('#vprofile-modal .vp-panel').attr('tabindex', '-1').focus();
                    }, 40);
                }

                function closeModal() {
                    $('#vprofile-modal').hide().attr('aria-hidden', 'true');
                    $('#vprofile-modal .vp-body').html('');
                    document.documentElement.classList.remove('modal-open');
                }

                $(document).on('click', '.open-vprofile', function(e) {
                    e.preventDefault();
                    var id = $(this).data('voterid') || null;
                    if (!id) {
                        var href = $(this).attr('href') || '';
                        var m = href.match(/VoterID=([^&]+)/);
                        if (m) id = decodeURIComponent(m[1]);
                    }
                    if (!id) return;
                    openModal();
                    var $body = $('#vprofile-modal .vp-body');
                    $body.html('<div class="u-loading">Loading...</div>');
                    $.get('VProfile.php', {
                            VoterID: id,
                            ajax: 1
                        })
                        .done(function(data) {
                            $body.html(data);
                            // Remove restrictive inline sizes from server fragment, then apply modal-friendly sizing
                            $body.find('img').each(function() {
                                $(this).removeAttr('style').removeAttr('width').removeAttr('height').css({
                                    'max-width': '320px',
                                    'height': 'auto'
                                });
                            });
                            // enlarge fonts inside modal body
                            $body.find('.vinfo').css({
                                'font-size': '18px'
                            });
                        }).fail(function() {
                            $body.html('<div class="u-pad-12">Unable to load profile.</div>');
                        });
                });

                // close handlers
                $(document).on('click', '#vprofile-modal .vp-close, #vprofile-modal .vp-overlay', function() {
                    closeModal();
                });
                $(document).on('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeModal();
                    }
                });
            })(jQuery);
        </script>
        <script type="text/javascript">
            // Plain-JS fallback for opening the voter profile modal when jQuery is unavailable or handlers fail.
            (function() {
                function byId(id) {
                    return document.getElementById(id);
                }

                function openModalPlain() {
                    var m = byId('vprofile-modal');
                    if (!m) return;
                    m.style.display = 'block';
                    m.setAttribute('aria-hidden', 'false');
                    document.documentElement.classList.add('modal-open');
                    var panel = m.querySelector('.vp-panel');
                    if (panel) {
                        panel.tabIndex = -1;
                        panel.focus();
                    }
                }

                function closeModalPlain() {
                    var m = byId('vprofile-modal');
                    if (!m) return;
                    m.style.display = 'none';
                    m.setAttribute('aria-hidden', 'true');
                    var body = m.querySelector('.vp-body');
                    if (body) body.innerHTML = '';
                    document.documentElement.classList.remove('modal-open');
                }

                // fetch helper
                function loadProfile(id) {
                    var m = byId('vprofile-modal');
                    if (!m) return;
                    var body = m.querySelector('.vp-body');
                    if (!body) return;
                    body.innerHTML = '<div class="u-loading">Loading...</div>';
                    var url = 'VProfile.php?VoterID=' + encodeURIComponent(id) + '&ajax=1';
                    fetch(url, {
                        credentials: 'same-origin'
                    }).then(function(resp) {
                        if (!resp.ok) throw new Error('Network response was not ok');
                        return resp.text();
                    }).then(function(text) {
                        body.innerHTML = text;
                        // resize images and fonts inside modal
                        var imgs = body.querySelectorAll('img');
                        imgs.forEach(function(img) {
                            img.removeAttribute('width');
                            img.removeAttribute('height');
                            img.style.maxWidth = '320px';
                            img.style.height = 'auto';
                            img.style.display = 'block';
                        });
                        var vinfo = body.querySelector('.vinfo');
                        if (vinfo) vinfo.style.fontSize = '18px';
                    }).catch(function() {
                        body.innerHTML = '<div class="u-pad-12">Unable to load profile.</div>';
                    });
                }

                document.addEventListener('click', function(e) {
                    var t = e.target;
                    if (!t) return;
                    var btn = t.closest && t.closest('.open-vprofile');
                    if (btn) {
                        try {
                            e.preventDefault();
                        } catch (err) {}
                        var id = btn.getAttribute('data-voterid') || null;
                        if (!id) {
                            var href = btn.getAttribute('href') || '';
                            var m = href.match(/VoterID=([^&]+)/);
                            if (m) id = decodeURIComponent(m[1]);
                        }
                        if (!id) return;
                        openModalPlain();
                        loadProfile(id);
                        return;
                    }

                    if (t.closest && (t.closest('.vp-close') || t.closest('.vp-overlay'))) {
                        try {
                            e.preventDefault();
                        } catch (err) {}
                        closeModalPlain();
                        return;
                    }
                }, false);

                // keyboard close
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeModalPlain();
                    }
                });
            })();
        </script>
    </div>
</body>

</html>
<?php
unset($Voters);
?>