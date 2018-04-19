<?php
session_start();
require $_SESSION['rootDir'] . "/Fraphe/fraphe.php";

// close application:
Fraphe\App\FApp::close();
