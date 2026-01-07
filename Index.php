<?php
session_start();
$loginMessage = $_SESSION['login_error'] ?? '';
if (isset($_SESSION['login_error'])) {
  unset($_SESSION['login_error']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>INEC | Index</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="bootstrap.css">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  <style>
    .login-panels {
      margin-top: 20px;
    }

    .login-panel {
      display: none;
      background: #f9f9f9;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .login-panel h3 {
      margin-top: 0;
      margin-bottom: 12px;
    }

    .login-panel .form-group label {
      font-weight: 600;
    }

    .login-panel .btn-close-panel {
      margin-left: 8px;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-default navbar-inverse">
    <!--img src="images/logo.png" width="269" height="75" alt="logo" style="padding-left:15px;"-->
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand" href="Index.php">Home</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="HowToVote.html">How To Vote</a> </li>
          <li><a href="Results-AllInOne.php">Election Results</a> </li>
        </ul>
      </div>
      <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
  </nav>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="jumbotron">
          <h1 class="text-center">Welcom to the INEC election system</h1>
          <div class="row"></div>
          <p class="text-center">Disclaimer: Any content found in this project that's attributed to any person or organization came here by accident or by consent. Feel free to contact me if you want yours removed. Thank You!</p>
        </div>
        <?php if (!empty($loginMessage)) : ?>
          <div class="alert alert-warning" role="alert"><?php echo htmlspecialchars($loginMessage); ?></div>
        <?php endif; ?>
      </div>
    </div>
    <div class="row login-panels">
      <div class="col-lg-6 col-md-6 col-sm-12 col-lg-offset-3 col-md-offset-3">
        <div class="login-panel" id="unified-panel" style="display: block;">
          <h3 style="text-align: center; margin-bottom: 20px;">Login to INEC System</h3>
          <form action="Login Scripts/UnifiedLogin.php" method="post">
            <div class="form-group">
              <label for="login-id">Username / Email / Phone</label>
              <input type="text" class="form-control" id="login-id" name="LoginId" placeholder="Enter username, email, or phone" required>
              <small style="color: #666; margin-top: 5px; display: block;">For Admin/Staff: enter username or phone<br>For Voters: enter username, email, or phone</small>
            </div>
            <div class="form-group">
              <label for="login-password">Password</label>
              <input type="password" class="form-control" id="login-password" name="Password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" style="margin-bottom: 10px;">Login</button>
            <p style="text-align: center; font-size: 12px; color: #999; margin-top: 10px;">
              This portal supports login for Voters, Admin, and Polling Staff
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
  <hr>
  <hr>
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-6 col-sm-6">
        <h2>Contact Us</h2>
        <address>
          <strong>INEC HQ.</strong><BR>
          Plot 436 Zambezi Crescent<BR>
          Maitama District<BR>
          FCT, Abuja<BR>
          NIGERIA
        </address>
        <h4>Social</h4>
        <div class="row">
          <div class="col-xs-2"><a href="https://facebook.com" target="new"><img src="images/Election Images/thWZ0FP69U.jpg" alt="" width="43" height="41" class="img-circle"></a></div>
          <div class="col-xs-2"><a href="https://twitter.com" target="new"><img src="images/Election Images/th0BZEVOTS.jpg" alt="" width="43" height="41" class="img-circle"></a></div>
          <div class="col-xs-2"><a href="https://instagram.com" target="new"><img src="images/Election Images/instagramRoundBlack.png" alt="" width="43" height="41" class="img-circle"></a></div>
          <div class="col-xs-2"><a href="https://plus.google.com" target="new"><img src="images/Election Images/thXW0BAPQ1.jpg" alt="" width="43" height="41" class="img-circle"></a></div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-sm-6">
        <h2>Mission &amp; Vission</h2>
        <div class="media">
          <div class="media-left"> <a href="#"> <!--img class="media-object" src="images/Election Images/th.jpg" alt="..."--> </a> </div>
          <div class="media-body">
            <h4 class="media-heading">Mission Statement</h4>
            The mission of INEC is to serve as an independent and effective EMB committed to the conduct of free, fair and credible elections for sustainable democracy in Nigeria.
          </div>
        </div>
        <div class="media">
          <div class="media-left"> <a href="#"> <!--img class="media-object" src="images/Election Images/th.jpg" alt="..."--> </a> </div>
          <div class="media-body">
            <h4 class="media-heading">Vision Statement</h4>
            The vision of INEC is to be one of the best Election Management Bodies (EMB) in the world that meets the aspirations of the Nigerian people.
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-sm-12">
        <h2>About Us</h2>
        <p>The Independent National Electoral Commission (INEC) was established by the 1999 Constitution of the Federal Republic of Nigeria to among other things organize elections into various political offices in the country.</p>
      </div>
    </div>
  </div>
  <hr>
  <footer class="text-center">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <p>Copyright © INEC Nigeria. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="jquery-1.11.3.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="bootstrap.js"></script>
  <script>
    (function() {
      function showPanel(selector) {
        var panel = document.querySelector(selector);
        if (panel) {
          panel.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      }

      var adminLink = document.getElementById('nav-admin');
      var voterLink = document.getElementById('nav-voter');

      if (adminLink) {
        adminLink.addEventListener('click', function(e) {
          e.preventDefault();
          showPanel('#unified-panel');
          document.getElementById('login-id').placeholder = 'Enter your username';
          document.getElementById('login-id').focus();
        });
      }

      if (voterLink) {
        voterLink.addEventListener('click', function(e) {
          e.preventDefault();
          showPanel('#unified-panel');
          document.getElementById('login-id').placeholder = 'Enter username, email, or phone';
          document.getElementById('login-id').focus();
        });
      }
    })();
  </script>
</body>

</html>