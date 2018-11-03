<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Title</title>
</head>
<body>
    <div class="">
        <?php
        echo "\$_SERVER['PHP_SELF']: " . $_SERVER['PHP_SELF'] . "<br>";
        echo "__DIR__: " . __DIR__ . "<br>";
        echo "__FILE__: " . __FILE__ . "<br>";

        $_SESSION['RootDirServer'] = __DIR__ . "/";
        $_SESSION['RootDirClient'] = dirname($_SERVER['PHP_SELF']) . "/";

        echo "RootDirServer: " . $_SESSION['RootDirServer'] . "<br>";
        echo "RootDirClient: " . $_SESSION['RootDirClient'] . "<br>";

        echo "<h1>var_dump(\$_SERVER)</h1>";
        echo var_dump($_SERVER);
        ?>
    </div>
</body>
</html>
