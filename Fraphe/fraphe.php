<?php
//------------------------------------------------------------------------------
// Fraphé Framework Autoloader
//------------------------------------------------------------------------------

if (!isset($_SESSION)) {
    session_start();
}

// create constants:
if (!defined("ROOT_DIR")) {
    define("ROOT_DIR", "rootDir");
}

// set Fraphe autoloader:
spl_autoload_register(function ($class_name) {
    if (file_exists($_SESSION[ROOT_DIR] . $class_name . ".php")) {
        require_once $_SESSION[ROOT_DIR] . $class_name . ".php";
    }
});
