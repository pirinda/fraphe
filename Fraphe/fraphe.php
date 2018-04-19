<?php
//------------------------------------------------------------------------------
// Fraphé Framework Autoloader
//------------------------------------------------------------------------------

if (!isset($_SESSION)) {
    session_start();
}

require_once $_SESSION['rootDir'] . "Fraphe" . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . "FUser.php";
require_once $_SESSION['rootDir'] . "Fraphe" . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . "FUserSession.php";

spl_autoload_register(function ($class_name) {
    if (file_exists($_SESSION['rootDir'] . $class_name . ".php")) {
        require_once $_SESSION['rootDir'] . $class_name . ".php";
    }
});
