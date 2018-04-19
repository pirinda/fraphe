<?php
//------------------------------------------------------------------------------
// Fraphé Framework Autoloader
//------------------------------------------------------------------------------

if (!isset($_SESSION)) {
    session_start();
}

spl_autoload_register(function ($class_name) {
    if (file_exists($_SESSION['rootDir'] . $class_name . ".php")) {
        require_once $_SESSION['rootDir'] . $class_name . ".php";
    }
});
