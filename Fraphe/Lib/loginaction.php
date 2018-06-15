<?php
if (!isset($_SESSION)) {
    session_start();
}
require $_SESSION['rootDir'] . "Fraphe" . DIRECTORY_SEPARATOR . "fraphe.php";

use Fraphe\App\FApp;
use Fraphe\App\FAppConsts;

$_SESSION[FAppConsts::USER_ID] = 1;
$_SESSION[FAppConsts::USER_NAME] = $_POST['username'];
$_SESSION[FAppConsts::USER_LOGIN_TS] = new \DateTime();

FApp::goHome();
