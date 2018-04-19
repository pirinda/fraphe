<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Title</title>
</head>
<body>
    <h1>Session 3</h1>
    <?php
    echo "Session status: " . session_status() . "<br>";
    echo "Session ID: " . session_id() . "<br>";

    echo "<h2>var_dump of \$_SERVER </h2>";
    echo var_dump($_SERVER);
    
    echo "<h2>var_dump of \$_SESSION</h2>";
    echo "<p>\$_SESSION before destroying session:</p>";
    echo var_dump($_SESSION);

    session_unset();
    session_destroy();
    echo "Session status: " . session_status() . "<br>";
    echo "Session ID: " . session_id() . "<br>";
    echo "<h2>var_dump of \$_SESSION</h2>";
    echo "<p>\$_SESSION after destroying session:</p>";
    echo var_dump($_SESSION);
    ?>
    <div class="">
        <p><a href="session0.php">Back to session 0</a></p>
    </div>
</body>
</html>
