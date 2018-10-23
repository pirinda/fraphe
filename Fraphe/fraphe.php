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
    //$class_name_env = str_replace("\\", DIRECTORY_SEPARATOR, $class_name);
    $class_name_env = str_replace("\\", "/", $class_name);
    if (file_exists($_SESSION[ROOT_DIR] . $class_name_env . ".php")) {
        require_once $_SESSION[ROOT_DIR] . $class_name_env . ".php";
    }
});
