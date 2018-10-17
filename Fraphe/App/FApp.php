<?php
namespace Fraphe\App;

abstract class FApp
{
    /*
    * Composes application footer. It should be rendered at the and of HTML Body element.
    * Return: string.
    * Throws: nothing.
    */
    public static function composeFooter(): string
    {
        $html = '<footer>';
        $html .= '<div class="container-fluid">';
        $html .= '<hr>';
        $html .= 'Copyright &copy;' . date("Y") . ' ' . $_SESSION[FAppConsts::APP_VENDOR];
        $html .= '</div>';
        $html .= '</footer>';

        return $html;
    }

    /*
    * Composes HTML-element Head.
    * Return: string.
    * Throws: nothing.
    */
    public static function composeHtmlHead(): string
    {
        $html = '<head>';
        $html .= '<title>' . $_SESSION[FAppConsts::APP_NAME] . '</title>';
        $html .= '<meta charset="utf-8">';
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
        /*
        $html .= '<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">';
        $html .= '<meta http-equiv="Pragma" content="no-cache">';
        $html .= '<meta http-equiv="Expires" content="0">';
        */
        $html .= '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
        $html .= '<link rel="stylesheet" href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'css/fraphe.css">';
        $html .= '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>';
        $html .= '<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>';
        $html .= '<script type="text/javascript" src="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'js/main.js"></script>';
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
        $html .= FAppBodyHome::compose();
        $html .= self::composeFooter();
        $html .= '</body>';

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
        return array_key_exists(FAppConsts::APP_NAME, $_SESSION);
    }

    public static function isUserSessionActive(): bool
    {
        return array_key_exists(FAppConsts::USER_ID, $_SESSION);
    }

    public static function hasUserSessionUserRoles(array $roles): bool
    {
        $has = false;

        if (!empty($_SESSION[FAppConsts::USER_TYPE]) && ($_SESSION[FAppConsts::USER_TYPE] == FAppConsts::USER_TYPE_ADMIN || $_SESSION[FAppConsts::USER_TYPE] == FAppConsts::USER_TYPE_SUPER)) {
            $has = true;
        }
        else {
            if (!empty($_SESSION[FAppConsts::USER_ROLES])) {
                foreach ($roles as $role) {
                    if (in_array($role, $_SESSION[FAppConsts::USER_ROLES])) {
                        $has = true;
                        break;
                    }
                }
            }
        }

        return $has;
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
            // read and decode JSON file of application configuration:
            $json = FGuiUtils::decodeJson($_SESSION[FAppConsts::ROOT_DIR] . FAppConsts::CFG_FILE_APP);

            // set application-configuration session variables:
            $_SESSION[FAppConsts::APP_NAME] = $json[FAppConsts::APP_NAME];
            $_SESSION[FAppConsts::APP_VENDOR] = $json[FAppConsts::APP_VENDOR];
            $_SESSION[FAppConsts::APP_MODE] = $json[FAppConsts::APP_MODE];
            $_SESSION[FAppConsts::DB_HOST] = $json[FAppConsts::DB_HOST];
            $_SESSION[FAppConsts::DB_PORT] = $json[FAppConsts::DB_PORT];
            $_SESSION[FAppConsts::DB_NAME] = $json[FAppConsts::DB_NAME];
            $_SESSION[FAppConsts::DB_USER_NAME] = $json[FAppConsts::DB_USER_NAME];
            $_SESSION[FAppConsts::DB_USER_PSWD] = $json[FAppConsts::DB_USER_PSWD];
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
        $rootDirWeb = $_SESSION[FAppConsts::ROOT_DIR_WEB];

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
        $rootDirWeb = $_SESSION[FAppConsts::ROOT_DIR_WEB];

        header("Location: " .  $rootDirWeb . "index.php");
    }

    /**
    *
    */
    public static function getVariable($variable) {
        $value = null;

        if (!empty($_GET[$variable])) {
            $value = $_GET[$variable];
        }
        else if (!empty($_POST[$variable])) {
            $value = $_POST[$variable];
        }
        else if (!empty($_SESSION[$variable])) {
            $value = $_SESSION[$variable];
        }

        return $value;
    }
}
