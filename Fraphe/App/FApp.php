<?php
namespace Fraphe\App;

class FApp
{
    const ATT_APP_NAME = "appName";
    const ATT_APP_VENDOR = "appVendor";
    const ATT_DB_HOST = "dbHost";
    const ATT_DB_PORT = "dbPort";
    const ATT_DB_NAME = "dbName";
    const APP_CFG_FILE = "app/config/app.json";

    public function __construct()
    {

    }

    private function render()
    {
        echo '<!DOCTYPE html>';
        echo '<html>';
        echo '<head>';
        echo '<title>App 1.0</title>';
        echo '</head>';
        echo '<body>';
        echo '<h1>App 1.0</h1>';
        echo '<div>App. root: ' . APP_ROOT . '</div>';
        echo '</body>';
        echo '</html>';
    }

    public function start()
    {
        // start session:
        session_start();

        // validate application configuration:
        if (!array_key_exists(self::ATT_APP_NAME, $_SESSION)) {
          // read application configuration:
          $file = fopen(self::APP_CFG_FILE, "r") or die("Unable to open file " . self::APP_CFG_FILE . "!");
          $json = json_decode(fread($file, filesize(self::APP_CFG_FILE)), true);

          // set application-configuration session variables:
          $_SESSION[self::ATT_APP_NAME] = $json[self::ATT_APP_NAME];
          $_SESSION[self::ATT_APP_VENDOR] = $json[self::ATT_APP_VENDOR];
        }
    }
}
