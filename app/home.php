<?php
// start/resume new session:
//session_start();
require "utils/sessionvalidate.php";
?>
<!DOCTYPE html>
<html>
<?php require "apphead.php"; ?>
<body>
  <?php require "homenav.php"; ?>
  <div class="container">
    <h1>This is Home Page!</h1>
    <hr>
    <?php
    echo "<h2>_SESSION</h2>";
    print_r($_SESSION);
    ?>
  </div>
  <?php require "appfooter.php"; ?>
</body>
</html>
