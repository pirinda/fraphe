<?php
if (!isset($_SESSION)) {
    session_start();
}
require $_SESSION['rootDir'] . "Fraphe" . DIRECTORY_SEPARATOR . "fraphe.php";

use Fraphe\App\FApp;

$_SESSION[FApp::USER_ID] = 1;
$_SESSION[FApp::USER_NAME] = $_POST['username'];
$_SESSION[FApp::USER_LOGIN_TS] = gettimeofday(true);

FApp::goHome();
