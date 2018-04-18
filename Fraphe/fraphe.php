<?php
//==============================================================================
// BOOTSTRAP FRAPHE FRAMEWORK
//==============================================================================

//------------------------------------------------------------------------------
//
//------------------------------------------------------------------------------

// set local application-root:
if (!defined("APP_ROOT_LOCAL")) {
    define("APP_ROOT_LOCAL", substr(__DIR__, 0, strpos(__DIR__, "Fraphe")));
}

// set HTTP application-root:
if (!defined("APP_ROOT_HTTP")) {
    define("APP_ROOT_HTTP", substr(__DIR__, 0, strpos(__DIR__, "Fraphe")));
}

spl_autoload_register(function ($class_name) {
    if (file_exists(APP_ROOT_LOCAL . $class_name . ".php")) {
        require_once APP_ROOT_LOCAL . $class_name . ".php";
    }
});

// starts/resumes session:
session_start();
