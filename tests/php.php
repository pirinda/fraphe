<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">WebSiteName</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#">Home</a></li>
      <li><a href="#">Page 1</a></li>
      <li><a href="#">Page 2</a></li>
      <li><a href="#">Page 3</a></li>
    </ul>
  </div>
</nav>

<div class="container">
  <h3>Basic Navbar Example</h3>
  <p>A navigation bar is a navigation header that is placed at the top of the page.</p>
  <?php
  /*
  $a;
  echo "var \$a is " . (isset($a) ? "set" : "not set") . ".<br>";
  echo "var \$b is " . (isset($b) ? "set" : "not set") . ".<br>";
  */

  $name = "../app/config/menu.json";
  $file = fopen($name, "r") or die("Unable to open file " . $name . "!");
  echo "<h1>\$file</h1>";
  var_dump($file);
  $json = json_decode(fread($file, filesize($name)), true);
  echo "<h1>\$json</h1>";
  var_dump($json);
  echo "<h2>Loop through \$json</h2>";
  foreach ($json as $f => $f_val) {
      echo "<hr>";
      echo "<h3>key=" . $f . "</h3>";
      var_dump($f);
      var_dump($f_val);
      foreach ($f_val as $m => $m_val) {
          echo "<h4>-key=" . $m . "</h4>";
          var_dump($m);
          var_dump($m_val);
          if (is_array($m_val)) {
              foreach ($m_val as $s => $s_val) {
                  echo "<h4>--key=" . $s . "</h4>";
                  var_dump($s);
                  var_dump($s_val);
                  if (is_array($s_val)) {
                      foreach ($s_val as $ss => $ss_val) {
                          echo "<h5>---key=" . $ss . "</h5>";
                          var_dump($ss);
                          var_dump($ss_val);
                          if (is_array($ss_val)) {
                              foreach ($ss_val as $sss => $sss_val) {
                                  echo "<h5>----key=" . $sss . "</h5>";
                                  var_dump($sss);
                                  var_dump($sss_val);
                                  if (is_array($sss_val)) {
                                      foreach ($sss_val as $ssss => $ssss_val) {
                                          echo "<h6>-----key=" . $ssss . "</h6>";
                                          var_dump($ssss);
                                          var_dump($ssss_val);
                                      }
                                  }
                              }
                          }
                      }
                  }
              }
          }
      }
  }
  ?>
</div>

</body>
</html>
