<?php
require "../app_lib/session/FSession.php";
Fraphe\Session\FSession::startSession();
?>
<!DOCTYPE html>
<html>
<?php require "../app_lib/html/apphead.php"; ?>
<body>
  <?php require "indexnav.php"; ?>
  <div class="container" style="margin-top:50px">
    <h1>This is Index Page!</h1>
    <hr>
    <?php
    echo "<h2>_SESSION</h2>";
    print_r($_SESSION);
    ?>
  </div>
  <?php require "../app_lib/html/appfooter.php"; ?>
</body>
</html>
