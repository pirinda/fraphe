<?php
if (!isset($_SESSION)) {
    session_start();
}

// bootstrap Fraphe:
require $_SESSION["rootDir"] . "Fraphe" . DIRECTORY_SEPARATOR . "fraphe.php";

use Fraphe\Lib\FLibUtils;

//$nf = new \NumberFormatter(Locale::getDefault(), \NumberFormatter::PATTERN_DECIMAL);
/*
$nf = new \NumberFormatter("", \NumberFormatter::PATTERN_DECIMAL);
$nf->setAttribute(\NumberFormatter::MIN_INTEGER_DIGITS, 6);
echo $nf->format(1) . '<br>';
echo date("Y-m-d H:i:s") . '<br>';
echo date(DateTime::W3C) . '<br>';

date_default_timezone_set("UTC");
echo "UTC:".time();
echo "<br>";

date_default_timezone_set("Europe/Helsinki");
echo "Europe/Helsinki:".time();
echo "<br>";

date_default_timezone_set("America/Mexico_City");
echo "America/Mexico_City:".time();
echo "<br>";

echo "Current local datetime from date(): " . date("Y-m-d H:i:s");
echo "<br>";

echo "Current local datetime from FLibUtils::getLocalDatetime(): (" . FLibUtils::getLocalDatetime() . ") " . date("Y-m-d H:i:s", FLibUtils::getLocalDatetime());
echo "<br>";

echo "date_default_timezone_get(): " . date_default_timezone_get();
echo "<br>";
*/
echo "date_default_timezone_get(): " . date_default_timezone_get();
echo "<br>";
echo "time(): " . time();
echo "<br>";
echo "FLibUtils::getLocalDatetime(): " . FLibUtils::getLocalDatetime();
echo "<br>";
echo "date('Y-m-d H:i:s'): " . date('Y-m-d H:i:s');
echo "<br>";
echo "date('Y-m-d H:i:s Z'): " . date('Y-m-d H:i:s Z');
echo "<br>";
echo "date('Y-m-d H:i:s', FLibUtils::getLocalDatetime()): " . date('Y-m-d H:i:s', FLibUtils::getLocalDatetime());
echo "<br>";

echo "<hr>";
date_default_timezone_get("UTC");

echo "date_default_timezone_get(): " . date_default_timezone_get();
echo "<br>";
echo "time(): " . time();
echo "<br>";
echo "FLibUtils::getLocalDatetime(): " . FLibUtils::getLocalDatetime();
echo "<br>";
echo "date('Y-m-d H:i:s'): " . date('Y-m-d H:i:s');
echo "<br>";
echo "date('Y-m-d H:i:s Z'): " . date('Y-m-d H:i:s Z');
echo "<br>";
echo "date('Y-m-d H:i:s', FLibUtils::getLocalDatetime()): " . date('Y-m-d H:i:s', FLibUtils::getLocalDatetime());
echo "<br>";
