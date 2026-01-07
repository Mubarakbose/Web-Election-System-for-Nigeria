    <div id="HeadContainer">
        <div id="Header">
            <div id="flag" aria-hidden="true"></div>
            <div id="flag" aria-hidden="true"></div>
        </div>
        <div id="Menu">
            <div id="cssmenu">
                <div id="menu-toggle" aria-label="Toggle menu" title="Menu" role="button" tabindex="0" aria-expanded="false" aria-controls="cssmenu-list">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
                <ul id="cssmenu-list">
                    <li><a href="AdminHome.php">Home</a></li>
                    <li class='active has-sub'><a href="ListContestant.php">Contestants</a>
                        <ul>
                            <li class="last"><a href='AddContestant.php'><span>Add Contestant</span></a></li>
                            <li class="last"><a href='ListContestant.php'><span>View Contestants</span></a></li>
                        </ul>
                    </li>
                    <li class='active has-sub'><a href="ListPollingUnit.php">Polling Units</a>
                        <ul>
                            <li class="last"><a href='AddPollingUnit.php'><span>Add Polling Unit</span></a></li>
                            <li class="last"><a href='ListPollingUnit.php'><span>View Polling Units</span></a></li>
                        </ul>
                    </li>
                    <li class='active has-sub'><a href="ListPollingStaffs.php">Manage Staff</a>
                        <ul>
                            <li class="last"><a href='AddPollingStaff.php'><span>Add Polling Staff</span></a></li>
                            <li class="last"><a href='ListPollingStaffs.php'><span>View Polling Staffs</span></a></li>
                        </ul>
                    </li>
                    <li><a href="ViewResult.php">View Result</a></li>
                    <li><a href="ViewMessage.php">View Messages</a></li>
                    <li><a href="ListVoters.php">View Voters</a></li>
                    <li><a href="<?php echo $logoutAction ?>">Logout</a></li>
                </ul>
            </div>
            <script src="Scripts/vendor/jquery.js" type="text/javascript"></script>
            <script src="Scripts/flash.js" type="text/javascript"></script>
            <script src="Scripts/form-validate.js" type="text/javascript"></script>
        </div>
        <?php
        // Render any session flash (set by server-side scripts) into the global flash container
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!empty($_SESSION['flash'])) {
            $flashes = $_SESSION['flash'];
            // Add an inline onclick fallback that removes the flash-item without relying on jQuery/event binding
            $inlineClose = 'onclick="(function(b){var p=b; while(p && p.className && p.className.indexOf(\'flash-item\')===-1) p=p.parentNode; if(p && p.parentNode) p.parentNode.removeChild(p); })(this); return false;"';
            echo "<div id=\"global-flash\">";
            // Support both a single associative flash (['type'=>'','text'=>''])
            // and an array of flash entries ( [ ['type'=>'','text'=>''], ... ] )
            if (isset($flashes[0]) && is_array($flashes)) {
                foreach ($flashes as $f) {
                    if (!is_array($f)) continue;
                    $type = isset($f['type']) ? htmlspecialchars($f['type']) : 'info';
                    $msg = isset($f['text']) ? htmlspecialchars($f['text']) : (isset($f['message']) ? htmlspecialchars($f['message']) : '');
                    echo "<div class=\"flash-item flash-{$type}\"><div class=\"flash-text\">{$msg}</div><button type=\"button\" class=\"flash-close\" aria-label=\"Dismiss\" {$inlineClose}>&times;</button></div>";
                }
            } elseif (is_array($flashes)) {
                $f = $flashes;
                $type = isset($f['type']) ? htmlspecialchars($f['type']) : 'info';
                $msg = isset($f['text']) ? htmlspecialchars($f['text']) : (isset($f['message']) ? htmlspecialchars($f['message']) : '');
                echo "<div class=\"flash-item flash-{$type}\"><div class=\"flash-text\">{$msg}</div><button type=\"button\" class=\"flash-close\" aria-label=\"Dismiss\" {$inlineClose}>&times;</button></div>";
            }
            echo "</div>";
            unset($_SESSION['flash']);
        } else {
            // Ensure the container exists for client-side flashes
            echo "<div id=\"global-flash\"></div>";
        }
        ?>
        <script>
            // Plain-JS delegated handler as extra fallback if jQuery handlers fail.
            document.addEventListener('click', function(e) {
                var t = e.target || e.srcElement;
                if (!t) return;
                if (t.classList && t.classList.contains('flash-close')) {
                    e.preventDefault();
                    // find closest .flash-item
                    var p = t;
                    while (p && !(p.classList && p.classList.contains('flash-item'))) p = p.parentNode;
                    if (p && p.parentNode) p.parentNode.removeChild(p);
                }
            }, false);
        </script>
    </div>