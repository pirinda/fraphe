<?php
require_once "../app/session/FUser.php";
require_once "../app/session/FSession.php";
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
          <h1>Page 1</h1>
          <p>This is some text.</p>
    </div>
    <div class="container-fluid">
          <h1>Page 1</h1>
          <p>This is some text.</p>
          <?php
          use Fraphe\Session\FUser;
          use Fraphe\Session\FSession;
          $user = new FUser(1, "John Travolta");
          echo "<p>User name: [", $user->getName(), "]</p>";
          $_SESSION['user'] = $user;

          $session = new FSession();
          $date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
          $session->setCurSettings($date, $user);
          $_SESSION['session'] = $session;
          ?>
          <p>
          <?php
          echo "Now: " . date("Y/m/d h:i:s a") . "<br>";
          ?>
          </p>
          <a href="page2.php">Go to page 2</a>
    </div>
</body>
</html>
