<?php
namespace Fraphe\Lib;

abstract class FDevUtils
{
    public static function printVar(string $msg, $var)
    {
        echo '<h3>' . $msg . '</h3>';
        print_r($var);
        echo '<hr>';
    }
}
