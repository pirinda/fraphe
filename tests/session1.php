<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Title</title>
</head>
<body>
    <h1>Session 1</h1>
    <?php
    echo "Session status: " . session_status() . "<br>";
    echo "Session ID: " . session_id() . "<br>";

    echo "<h2>var_dump of \$_SERVER </h2>";
    echo var_dump($_SERVER);
    
    echo "<h2>var_dump of \$_SESSION</h2>";
    echo "<p>\$_SESSION before processing 'count':</p>";
    echo var_dump($_SESSION);

    if (!isset($_SESSION['count'])) {
        $_SESSION['count'] = 0;
    } else {
        $_SESSION['count']++;
    }

    echo "<h2>var_dump of \$_SESSION</h2>";
    echo "<p>\$_SESSION after processing 'count':</p>";
    echo var_dump($_SESSION);
    ?>
    <div class="">
        <p><a href="session2.php">Go to session 2</a></p>
        <p><a href="session0.php">Back to session 0</a></p>
    </div>
</body>
</html>
