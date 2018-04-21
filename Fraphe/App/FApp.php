<?php
namespace Fraphe\App;

abstract class FApp
{
    const APP_NAME = "appName";
    const APP_VENDOR = "appVendor";
    const USER_ID = "userId";
    const USER_NAME = "userName";
    const USER_LOGIN_TS = "userLoginTs";
    const CFG_FILE_APP = "app/config/app.json";
    const CFG_FILE_DB = "app/config/db.json";
    const CFG_FILE_MENU = "app/config/menu.json";
    const ROOT_DIR = "rootDir";
    const ROOT_DIR_WEB = "rootDirWeb";

    const OPT = "option";
    const OPT_HOME = "home";
    const OPT_FEAT = "feat";
    const OPT_HELP = "help";

    /*
    * Composes HTML-element Head.
    * Return: string.
    * Throws: nothing.
    */
    public static function composeHtmlHead(): string
    {
        $html = '<head>';
        $html .= '<title>' . $_SESSION[self::APP_NAME] . '</title>';
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
        $html .= FAppNavbar::compose();
        $html .= FAppBody::compose();
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
        $html = '<footer>';
        $html .= '<div class="container-fluid">';
        $html .= '<hr>';
        $html .= 'Copyright &copy;' . date("Y") . ' ' . $_SESSION[self::APP_VENDOR];
        $html .= '</div>';
        $html .= '</footer>';

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
        return array_key_exists(self::APP_NAME, $_SESSION);
    }

    public static function isUserSessionActive(): bool
    {
        return array_key_exists(self::USER_ID, $_SESSION);
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
          $name = $_SESSION[self::ROOT_DIR] . self::CFG_FILE_APP;
          $file = fopen($name, "r") or die("Unable to open file " . $name . "!");
          $json = json_decode(fread($file, filesize($name)), true);

          // set application-configuration session variables:
          $_SESSION[self::APP_NAME] = $json[self::APP_NAME];
          $_SESSION[self::APP_VENDOR] = $json[self::APP_VENDOR];
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
        $rootDirWeb = $_SESSION[self::ROOT_DIR_WEB];

        session_start();
        session_unset();
        session_destroy();

        header("Location: " .  $rootDirWeb . "index.php");
    }

    /*
    * Redirects to index.php.
    * Returns: nothing
    * Throws: nothing
    */
    public static function goHome()
    {
        $rootDirWeb = $_SESSION[self::ROOT_DIR_WEB];

        header("Location: " .  $rootDirWeb . "index.php");
    }
}
