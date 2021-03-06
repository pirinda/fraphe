<?php
require_once "../app_lib/session/FUser.php";
require_once "../app_lib/session/FUserSession.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap 4 Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
          <h1>Page 2</h1>
          <p>This is some text.</p>
    </div>
    <div class="container-fluid">
          <h1>Page 2</h1>
          <p>This is some text.</p>
          <p>
              <?php
              var_dump($_SESSION);
              ?>
          </p>
          <p>
              <h2>User from $_SESSION Global Variable</h2>
              <?php
              echo "Retrieving \$user from \$_SESSION:<br>";
              if (!isset($_SESSION['user'])) {
                  echo "\$user not set!<br>";
              } else {
                  $user = $_SESSION['user'];
                  echo "<p>User name: [", $user->getName(), "]</p>";

                  echo "<p>Dumping \$user: ", var_dump($_SESSION['user']), "</p>";
              }

              echo "Retrieving \$undefined from \$_SESSION:<br>";
              if (!isset($_SESSION['undefined'])) {
                  echo "\$undefined not set!<br>";
              } else {
                  echo "<p>Dumping \$undefined: ", var_dump($_SESSION['undefined']), "</p>";
              }
              ?>
              <h2>Session from $_SESSION Global Variable</h2>
              <?php
              echo "Retrieving \$userSession from \$_SESSION:<br>";
              if (!isset($_SESSION['session'])) {
                  echo "\$userSession not set!<br>";
              } else {
                  $userSession = $_SESSION['session'];
                  echo "<p>Local language: [", $userSession->getLocLang(), "]</p>";
                  echo "<p>Local country: [", $userSession->getLocCountry(), "]</p>";
                  echo "<p>Local currency: [", $userSession->getLocCurrency(), "]</p>";
                  echo "<p>Local time zone: [", $userSession->getLocTimeZone(), "]</p>";

                  echo "<p>Current user ID: [", $userSession->getCurUser()->getId(), "]</p>";
                  echo "<p>Current user name: [", $userSession->getCurUser()->getName(), "]</p>";

                  echo "<p>Dumping \$userSession: ", var_dump($_SESSION['session']), "</p>";
              }
              ?>
          </p>
          <a href="page1.php">Go to page 1</a>
    </div>
</body>
</html>
