<?php
if (!isset($_SESSION)) {
    session_start();
}
require $_SESSION['rootDir'] . "Fraphe" . DIRECTORY_SEPARATOR . "fraphe.php";

// close application:
Fraphe\App\FApp::close();
