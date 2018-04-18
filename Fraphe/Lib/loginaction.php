<?php
// bootstrap Fraphe:
require "../fraphe.php";

use Fraphe\App\FApp;
use Fraphe\App\FUser;
use Fraphe\App\FUserSession;

$user = new FUser(1, $_POST['username']);
$date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
$session = new FUserSession($date, $user);

$_SESSION[FApp::ATT_USER_SESSION] = $session;

FApp::show();
