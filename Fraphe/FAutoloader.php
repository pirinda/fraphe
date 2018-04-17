<?php
namespace Fraphe;

abstract class FAutoloader
{
    public static function register()
    {
        spl_autoload_register(function ($className) {
            echo "Loading class <b>\"$className\"</b>...<br>";

            $dirs = array(
                '../',
                '../Session/',
                '../Fraphe/',
                '../Fraphe/Session/'
            );

            foreach ($dirs as $dir) {
                $file = $dir . str_replace("\\", "/", $className) . ".php";
                echo "Trying with $file.<br>";
                if (file_exists($file)) {
                    require_once $file;
                    echo "Found $file!<br>";
                    break;
                }
            }
        });
    }
}
