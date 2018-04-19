<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Title</title>
</head>
<body>
    <h1>Session 2</h1>
    <?php
    echo "Session status: " . session_status() . "<br>";
    echo "Session ID: " . session_id() . "<br>";

    echo "<h2>var_dump of \$_SERVER </h2>";
    echo var_dump($_SERVER);
    
    echo "<h2>var_dump of \$_SESSION</h2>";
    echo var_dump($_SESSION);

    header ("Location: session3.php");
    exit;

    if (!isset($_SESSION['wtf'])) {
        $_SESSION['wtf'] = "What the fuck! PHP script continues after calling 'header()' if 'exit()' or 'die()' are not called aswell!";
    }
    ?>
    <div class="">
        <p><a href="session3.php">Go to session 3</a></p>
        <p><a href="session0.php">Back to session 0</a></p>
    </div>
</body>
</html>
