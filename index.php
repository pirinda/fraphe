<?php
// set application root and register default PHP class autoloader:
if (!defined("APP_ROOT")) {
    define("APP_ROOT", __DIR__ . DIRECTORY_SEPARATOR);
    spl_autoload_register(function ($class_name) {
        if (file_exists(APP_ROOT . $class_name . ".php")) {
            require_once APP_ROOT . $class_name . ".php";
        }
    });
}

// instantiate application:
new Fraphe\App\FApp();
