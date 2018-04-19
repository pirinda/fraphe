<?php
if (!isset($_SESSION)) {
    session_start();
}
require $_SESSION['rootDir'] . "Fraphe" . DIRECTORY_SEPARATOR . "fraphe.php";

use Fraphe\App\FApp;
use Fraphe\App\FUser;
use Fraphe\App\FUserSession;

$user = new FUser(1, $_POST['username']);

$date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
$session = new FUserSession($user, $date);

$_SESSION[FApp::ATT_USER_SESSION] = serialize($session);

FApp::goHome();
