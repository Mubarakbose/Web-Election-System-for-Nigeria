<?php
// Header component for Polling Staff Portal
// Expects: $staffFirstName (staff's first name), $currentPage (for active menu item)

// Set default values if not provided
if (!isset($staffFirstName)) {
    $staffFirstName = 'Guest';
}
if (!isset($currentPage)) {
    $currentPage = '';
}
?>
<div class="header">
    <div>
        <div class="welcome-card">
            <p>Welcome <font color="#FF6600"><?php echo htmlspecialchars($staffFirstName); ?></font>
            </p>
        </div>
        <ul>
            <li<?php if ($currentPage === 'Index.php') echo ' class="selected"'; ?>><a href="Index.php"><span>H</span>ome</a></li>
                <li<?php if ($currentPage === 'Updates.php') echo ' class="selected"'; ?>><a href="Updates.php"><span>N</span>ews</a></li>
                    <li<?php if ($currentPage === 'AddVoters.php') echo ' class="selected"'; ?>><a href="AddVoters.php"><span>a</span>dd voter</a></li>
                        <li<?php if ($currentPage === 'AuthenticateVoter.php') echo ' class="selected"'; ?>><a href="AuthenticateVoter.php"><span>S</span>earch <span>v</span>oter</a></li>
                            <li<?php if ($currentPage === 'Profile.php') echo ' class="selected"'; ?>><a href="Profile.php"><span>M</span>y <span>p</span>rofile</a></li>
                                <li<?php if ($currentPage === 'SendMessage.php') echo ' class="selected"'; ?>><a href="SendMessage.php"><span>M</span>essage</a></li>
                                    <li><a href="<?php echo isset($logoutAction) ? $logoutAction : 'logout.php'; ?>"><span>L</span>ogout</a></li>
        </ul>
    </div>
</div>