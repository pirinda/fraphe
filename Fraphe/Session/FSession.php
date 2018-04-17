<?php
namespace Fraphe\Session;

use Fraphe\FApp;

abstract class FSession
{
    /*
    * Resumes current PHP session, if any, and destroys it.
    * Returns: nothing
    * Throws: nothing
    */
    public static function destroySession()
    {
        session_start();
        session_unset();
        session_destroy();
    }

    /*
    * Starts a new and clean PHP session.
    * Returns: nothing
    * Throws: nothing
    */
    public static function startSession()
    {
        // destroy current session, if any:
        self::destroySession();

        // start a new and clean session:
        session_start();

        // validate application configuration:
        if (!array_key_exists(FApp::APP_NAME, $_SESSION)) {
          // read application configuration:
          $file = fopen(FApp::CFG_FILE, "r") or die("Unable to open file " . FApp::CFG_FILE . "!");
          $json = json_decode(fread($file, filesize(FApp::CFG_FILE)), true);

          // set application-configuration session variables:
          $_SESSION[FApp::APP_NAME] = $json[FApp::APP_NAME];
          $_SESSION[FApp::APP_VENDOR] = $json[FApp::APP_VENDOR];
        }
    }

    /*
    * Redirects to index page.
    * Returns: nothing
    * Throws: nothing
    */
    private static function redirectIndex()
    {
        header('Location: ../../app/index.php');
    }

    /*
    * Destroys current PHP session, and redirects to index.php.
    * Returns: nothing
    * Throws: nothing
    */
    public static function closeSession()
    {
        self::destroySession();
        self::redirectIndex();
    }

    /*
    * Starts or resumes and validates user session.
    * If no user session exists, redirects to index.php.
    * Returns: nothing
    * Throws: nothing
    */
    public static function validateUserSession()
    {
        self::startSession();

        // validate application user:
        if (!array_key_exists(FApp::USER_SESSION, $_SESSION)) {
            self::redirectIndex();
        }
    }
}
