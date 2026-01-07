<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

// Resolve current staff context once for this page
$row_Staff_name = StaffContext::current();
?>
<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>

<head>
	<meta charset="UTF-8">
	<title>Home Page</title>
	<link rel="stylesheet" href="PStaff CSS/unified-responsive.css" type="text/css">
	<link rel="stylesheet" href="PStaff CSS/admin-form-override.css" type="text/css">
	<link rel="stylesheet" href="PStaff CSS/footer-modern.css" type="text/css">
</head>

<body topmargin="0">
	<?php
	$staffFirstName = $row_Staff_name['FirstName'];
	$currentPage = 'Index.php';
	include('header.php');
	?>
	<div id="Images">
		<div id="imgholder"></div>
		<div id="textholder">
			<div>
				<div style="font-size:18px;"><strong>OUR MISSION &amp; VISION</strong></div>
				<div>
					<p><STRONG>MISSION STATEMENT</STRONG></p>
				</div>
				<div>
					<p>The mission of INEC is to serve as an independent and effective EMB committed to the conduct of free, fair and credible elections for sustainable democracy in Nigeria.</p>
					<div><STRONG>VISION STATEMENT</STRONG></div>
					<div>The vision of INEC is to be one of the best Election Management Bodies (EMB) in the world that meets the aspirations of the Nigerian people. </div>
				</div>
			</div>
		</div>
	</div>

	<div class="body">
		<div>
			<div class="featured"></div>
			<div>
				<div>
					<div>
						<div class="section">
							<h2>HOW TO SECURE OUR SYSTEM</h2>
							<p>
								This Electronic Voting System is designed to improve our Elections in Nigeria. This system is designed only for INEC polling staff. Please read and understand the following guidelines designed by the Information Security Specialists of INEC Nigeria.<br> Your cooperation is highly appreciated!
							</p>
							<ul>
								<li>
									<p>
										Do not share your login ID and Password with anyone. This includes your family member/friends/spouse, etc.
									</p>
								</li>
								<li>
									<p>
										Do not give INEC properties to anyone. Especially the Computer devices!
									</p>
								</li>
								<li>
									<p>
										Do not allow anyone to know the link of our website.
									</p>
								</li>
								<li>
									<p>
										Do not download anything from the internet using INEC computers.
									</p>
								</li>
							</ul>
							<ul class="last">
								<li>
									<p>
										If you have any problem or difficulty using INEC products, do not hesitate to <a href="SendMessage.php">contact the support team.</a> </p>
								</li>
								<li>
									<p>
										You are not allowed to use INEC hardware products on someone's computer device.
									</p>
								</li>
								<li>
									<p>
										Do not be careless in handling INEC's product.
									</p>
								</li>
								<li>
									<p>
										INEC has prepared serious disciplinary actions for violating the above rules.
									</p>
								</li>
							</ul>
						</div>
						<div>

							<ul>

								<li>
									<div>
										<h3>INSTRUCTIONS</h3>
										<span>To Add Voters</span>
										<p>
											From this page, go to <strong>ADD VOTER</strong>, Fill the form and click <strong>Add Voter</strong>. The system will insert a new voter into the database. . <br>
											<a href="#">Watch the video here!</a>
										</p>
									</div>
								</li>
								<li>
									<div>
										<span>To capture voter's PHOTO</span>
										<p>
											<a href="#">Please watch The video here!</a>
										</p>
									</div>
								</li>
								<li class="last">
									<div>
										<span>User Guide</span>
										<p>
											Are you confused while using this system? Please visit our user guide panel. There are helpful videos that will enable you to perform your tasks as a Polling Staff. <a href="#">Watch them Now!</a> </p>
									</div>
								</li>
							</ul>
							<a href="#">View All</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include('footer.php'); ?>
</body>

</html>
<?php
// unset($result_Staff); // This line is now obsolete and has been removed.
?>