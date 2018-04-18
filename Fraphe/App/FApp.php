<?php
namespace Fraphe\App;

abstract class FApp
{
    const ATT_APP_NAME = "appName";
    const ATT_APP_VENDOR = "appVendor";
    const ATT_USER_SESSION = "userSession";
    const ATT_DB_HOST = "dbHost";
    const ATT_DB_PORT = "dbPort";
    const ATT_DB_NAME = "dbName";
    const APP_CFG_FILE = "app/config/app.json";

    /*
    * Composes HTML-element Head.
    * Return: string.
    * Throws: nothing.
    */
    public static function composeHtmlHead(): string
    {
        $html = '<head>';
        $html .= '<title>' . $_SESSION[self::ATT_APP_NAME] . '</title>';
        $html .= '<meta charset="utf-8">';
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
        $html .= '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
        $html .= '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>';
        $html .= '<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>';
        $html .= '</head>';

        return $html;
    }

    /*
    * Composes HTML-element Body.
    * Return: string.
    * Throws: nothing.
    */
    public static function composeHtmlBody(): string
    {
        $html = '<body>';
        $html .= FAppNavbar::composeNav();
        $html .= '<div class="container" style="margin-top:50px">';
        $html .= '<h1>' . $_SESSION[self::ATT_APP_NAME] . '</h1>';
        $html .= '<div>';
        $html .= 'App. root: ' . APP_ROOT_LOCAL;
        $html .= '</div>';
        $html .= '<div>';
        $html .= 'App. root: ' . $_SERVER['PHP_SELF'];
        $html .= '</div>';
        $html .= '</div>';
        $html .= self::composeFooter();
        $html .= '</body>';

        return $html;
    }

    /*
    * Composes application footer.
    * Return: string.
    * Throws: nothing.
    */
    public static function composeFooter(): string
    {
        $html = '<div class="container">';
        $html .= 'Copyright &copy;' . date("Y") . ' ' . $_SESSION[self::ATT_APP_VENDOR];
        $html .= '</div>';

        return $html;
    }

    /*
    * Renders application.
    * Return: nothing.
    * Throws: nothing.
    */
    public static function show()
    {
        echo '<!DOCTYPE html>';
        echo '<html>';
        echo self::composeHtmlHead();
        echo self::composeHtmlBody();
        echo '</html>';
    }

    public static function isSessionActive(): bool
    {
        return array_key_exists(self::ATT_APP_NAME, $_SESSION);
    }

    public static function isUserSessionActive(): bool
    {
        return array_key_exists(self::ATT_USER_SESSION, $_SESSION);
    }

    /*
    * Starts application.
    * Return: nothing.
    * Throws: nothing.
    */
    public static function start()
    {
        // validate application configuration:
        if (!self::isSessionActive()) {
          // read application configuration:
          $name = APP_ROOT_LOCAL . self::APP_CFG_FILE;
          $file = fopen($name, "r") or die("Unable to open file " . $name . "!");
          $json = json_decode(fread($file, filesize($name)), true);

          // set application-configuration session variables:
          $_SESSION[self::ATT_APP_NAME] = $json[self::ATT_APP_NAME];
          $_SESSION[self::ATT_APP_VENDOR] = $json[self::ATT_APP_VENDOR];
        }

        self::show();
    }

    /*
    * Closes application.
    * Returns: nothing
    * Throws: nothing
    */
    public static function close()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: " . APP_ROOT_LOCAL . "index.php");
    }
}
