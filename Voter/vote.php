<?php
require_once("bootstrap.php");
require_once("access_control.php");

// Logout if requested
$logoutAction = $_SERVER["PHP_SELF"] . "?doLogout=true";
if (!empty($_SERVER["QUERY_STRING"])) {
	$logoutAction .= "&" . htmlentities($_SERVER["QUERY_STRING"]);
}

if (isset($_GET["doLogout"]) && $_GET["doLogout"] == "true") {
	$_SESSION = array();
	session_destroy();
	$logoutGoTo = "../Index.php";
	if (!empty($logoutGoTo)) {
		header("Location: $logoutGoTo");
		exit;
	}
}

$colname_Voter = "-1";
if (isset($_SESSION["MM_Username"])) {
	$colname_Voter = $_SESSION["MM_Username"];
}
try {
	$query_Voter = "SELECT * FROM voter WHERE UserName = :username";
	$Voter = db_query($query_Voter, [":username" => $colname_Voter]);
	$row_Voter = db_fetch_assoc($Voter);
	$totalRows_Voter = db_rowcount($Voter);
} catch (Exception $e) {
	die($e->getMessage());
}

// Load positions
if (!isset($_SESSION["voted_positions"])) {
	$_SESSION["voted_positions"] = [];
}
$flashMessages = isset($_SESSION["vote_flash"]) ? $_SESSION["vote_flash"] : [];
unset($_SESSION["vote_flash"]);

try {
	$query_positions_list = db_query("SELECT DISTINCT Position FROM contestant ORDER BY Position");
	$positions = [];
	while ($rp = db_fetch_assoc($query_positions_list)) {
		$positions[] = $rp["Position"];
	}
} catch (Exception $e) {
	die($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link href="../CSS Style/VoterGuideStyle.css" rel="stylesheet" type="text/css" />
	<link href="style.css?v=20251227" rel="stylesheet" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<title>INEC | Vote President</title>
</head>

<body topmargin="" bgcolor="#006666" style="text-transform:capitalize;">
	<div id="Container">
		<div id="flag"></div>
		<div id="Adminlogin">
			<header id="voterHeader">
				<div class="welcome">Welcome <?php echo htmlspecialchars($row_Voter["FirstName"]); ?> <?php echo htmlspecialchars($row_Voter["OtherName"]); ?></div>
				<nav>
					<ul id="MenuBar1" class="MenuBarHorizontal">
						<li><a href="VoterIndex.php">Home</a></li>
						<li><a href="<?php echo $logoutAction ?>">Logout</a></li>
					</ul>
				</nav>
			</header>
			<div id="vote">
				<h1>Select Position to Vote</h1>
				<div id="flash_container">
					<?php foreach ($flashMessages as $msg):
						$msgText = is_array($msg) ? $msg['message'] : $msg;
						$msgType = is_array($msg) ? $msg['type'] : 'success';
						$flashClass = $msgType === 'warning' ? 'vote-flash warning' : 'vote-flash success';
					?>
						<div class="<?php echo $flashClass; ?>">
							<?php echo htmlspecialchars($msgText); ?>
							<button class="flash-close" aria-label="Close">&times;</button>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="tiles-grid" role="navigation" aria-label="Select Position">
					<?php foreach ($positions as $pos):
						$isVoted = in_array($pos, $_SESSION["voted_positions"]);
						$cardClass = $isVoted ? "tile position-card voted" : "tile position-card";
					?>
						<div class="<?php echo $cardClass; ?>" data-position="<?php echo htmlspecialchars($pos); ?>">
							<h4><?php echo htmlspecialchars($pos); ?></h4>
							<?php if ($isVoted): ?>
								<span class="voted-badge"> Voted</span>
							<?php else: ?>
								<button class="select-position-btn">View Candidates</button>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
				<div id="candidates-section" style="display:none; margin-top:30px; padding:20px; background:#f9f9f9; border-radius:8px;">
					<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
						<h2 id="candidates-title">Contestants</h2>
						<button id="candidates-close-btn" style="background:#d32f2f; color:white; border:none; padding:8px 16px; border-radius:4px; cursor:pointer;">Close</button>
					</div>
					<div id="candidates-grid" class="tiles-grid"></div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function() {
			$(".flash-close").on("click", function() {
				$(this).parent().fadeOut(300, function() {
					$(this).remove();
				});
			});
			setTimeout(function() {
				$(".vote-flash").fadeOut(300, function() {
					$(this).remove();
				});
			}, 8000);
			$(".tile.position-card:not(.voted)").on("click", function(e) {
				e.preventDefault();
				var position = $(this).data("position");
				$.ajax({
					url: "getCandidates.php",
					type: "post",
					data: {
						position: position
					},
					success: function(data) {
						$("#candidates-title").text("Contestants for " + position);
						$("#candidates-grid").html(data);
						$("#candidates-section").slideDown(300);
						$("html, body").animate({
							scrollTop: $("#candidates-section").offset().top - 100
						}, 500);
					}
				});
			});
			$("#candidates-close-btn").on("click", function(e) {
				e.preventDefault();
				$("#candidates-section").slideUp(300);
			});

			// Click on candidate card to show vote button
			$(document).on("click", ".candidate-card", function(e) {
				e.preventDefault();
				// Ignore if clicking on button elements
				if ($(e.target).is('button')) return;

				var $card = $(this);
				var state = $card.attr('data-state');

				if (state === 'normal') {
					// Show vote button, hide others
					$('.candidate-card').each(function() {
						$(this).attr('data-state', 'normal');
						$(this).find('.card-actions').hide();
						$(this).find('.card-confirm').hide();
						$(this).find('.card-result').hide();
					});

					$card.attr('data-state', 'voting');
					$card.find('.card-actions').slideDown(200);
				}
			});

			// Click Vote button to show confirmation
			$(document).on("click", ".btn-vote", function(e) {
				e.preventDefault();
				e.stopPropagation();
				var $card = $(this).closest('.candidate-card');
				$card.attr('data-state', 'confirming');
				$card.find('.card-actions').hide();
				$card.find('.card-confirm').slideDown(200);
			});

			// Click No to cancel
			$(document).on("click", ".btn-no", function(e) {
				e.preventDefault();
				e.stopPropagation();
				var $card = $(this).closest('.candidate-card');
				$card.attr('data-state', 'normal');
				$card.find('.card-confirm').slideUp(200);
			});

			// Click Yes to confirm vote
			$(document).on("click", ".btn-yes", function(e) {
				e.preventDefault();
				e.stopPropagation();
				var $card = $(this).closest('.candidate-card');
				var candidateId = $card.data('candidate-id');

				$.ajax({
					url: 'castVote.php',
					type: 'post',
					data: {
						id: candidateId
					},
					success: function(response) {
						$card.attr('data-state', 'completed');
						$card.find('.card-confirm').hide();
						var $result = $card.find('.card-result');
						$result.html('<p style="margin:0; font-weight:600; color:#155724;">' + response + '</p>');
						$result.css('background', '#d4edda').slideDown(200);

						// Reload page after 2 seconds to update voted status
						setTimeout(function() {
							window.location.href = 'vote.php';
						}, 2000);
					},
					error: function() {
						$card.attr('data-state', 'error');
						$card.find('.card-confirm').hide();
						var $result = $card.find('.card-result');
						$result.html('<p style="margin:0; font-weight:600; color:#721c24;">Error occurred. Please try again.</p>');
						$result.css('background', '#f8d7da').slideDown(200);
					}
				});
			});

			// Mask click-to-close disabled (mask does not intercept clicks).
			// Use Close button inside modal (detail.php) to close.
		});
	</script>
</body>

</html>
<?php unset($Voter); ?>