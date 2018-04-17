<?php
// start/resume new session:
require "utils/sessionvalidate.php";

$_SESSION[SESSION_USER_ID] = 1;
$_SESSION[SESSION_USER] = $_POST['username'];

header('Location: home.php');
