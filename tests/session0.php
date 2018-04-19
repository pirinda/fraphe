<!DOCTYPE html>
<html>
<head>
  <title>Title</title>
</head>
<body>
    <h1>Session 0</h1>
    <?php
    echo "Session status: " . session_status() . "<br>";
    echo "Session ID: " . session_id() . "<br>";

    echo "<h2>var_dump of \$_SERVER </h2>";
    echo var_dump($_SERVER);
    
    echo "<h2>var_dump of \$_SESSION</h2>";
    echo "<p>\$_SESSION should be null:</p>";
    echo var_dump($_SESSION);
    ?>
    <div class="">
        <p><a href="session1.php">Go to session 1</a></p>
    </div>
</body>
</html>
