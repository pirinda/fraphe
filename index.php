<?php
//------------------------------------------------------------------------------
// Fraphé Framework Entry Point
//------------------------------------------------------------------------------

// start session:
if (!isset($_SESSION)) {
    session_start();
}

// create constants:
define("ROOT_DIR", "rootDir");
define("ROOT_DIR_WEB", "rootDirWeb");

// define root directories, local (back-end) and web (front-end):
$_SESSION[ROOT_DIR] = __DIR__ . DIRECTORY_SEPARATOR;
$_SESSION[ROOT_DIR_WEB] = dirname($_SERVER['PHP_SELF']) . "/";

// bootstrap Fraphe:
require $_SESSION[ROOT_DIR] . "Fraphe" . DIRECTORY_SEPARATOR . "fraphe.php";

// start application:
Fraphe\App\FApp::start();
