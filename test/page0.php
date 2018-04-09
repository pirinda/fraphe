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
          <h1>Page 0</h1>
          <p>This is some text.</p>
          <h2>Variable $_SERVER <small>by var_dump()</small></h2>
          <p>
              <?= var_dump($_SERVER) ?>
          </p>
          <h2>Variable $_FILES <small>by var_dump()</small></h2>
          <p>
              <?= var_dump($_FILES) ?>
          </p>
    </div>
    <div class="container-fluid">
          <h1>Page 0</h1>
          <p>This is some text.</p>
          <p>
              <?php
              echo "Now (UTC): " . date("Y/m/d h:i:s a") . "<br>";
              date_default_timezone_set("America/Mexico_City");
              echo "Now (America/Mexico_City): " . date("Y/m/d h:i:s a") . "<br>";
              $time = 0;
              echo "Unix Epoch: " . date("Y/m/d h:i:s a", $time) . "<br>";
              echo "Unix Epoch: " . date("Y/m/d H:i:s a", $time) . "<br>";
              $time = mktime(18, 30, 0, 1, 21, 1977);
              echo "My birthday: " . date("Y/m/d h:i:s a", $time) . "<br>";
              echo "My birthday: " . date("Y/m/d H:i:s a", $time) . "<br>";
              ?>
          </p>
    </div>
</body>
</html>
