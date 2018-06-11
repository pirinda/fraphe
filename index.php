<?php
//------------------------------------------------------------------------------
// Fraphé Framework Entry Point
//------------------------------------------------------------------------------

// start session:
if (!isset($_SESSION)) {
    session_start();
}

// define root directories, local (back-end) and web (front-end):
$_SESSION["rootDir"] = __DIR__ . DIRECTORY_SEPARATOR;
$_SESSION["rootDirWeb"] = dirname($_SERVER['PHP_SELF']) . "/";

// bootstrap Fraphe:
require $_SESSION["rootDir"] . "Fraphe" . DIRECTORY_SEPARATOR . "fraphe.php";

// start application:
Fraphe\App\FApp::start();
