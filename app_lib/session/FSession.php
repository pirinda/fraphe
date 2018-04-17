<?php
namespace Fraphe\Session;

require_once "../FApp.php";

use Fraphe\FApp;

abstract class FSession
{
    /*
    * Starts or resumes PHP session.
    * Returns: nothing
    * Throws: nothing
    */
    public function startSession()
    {
        // start/resume new session:
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
    */
    private function redirectIndex()
    {
        // redirect to index page:
        header('Location: ../../app/index.php');
    }

    /*
    * Closes PHP session.
    * Redirects to index.php.
    * Returns: nothing
    * Throws: nothing
    */
    public function closeSession()
    {
        session_start();
        session_unset();
        session_destroy();

        redirectIndex();
    }

    /*
    * Starts or resumes and validates user session.
    * If no user session exists, redirects to index.php.
    * Returns: nothing
    * Throws: nothing
    */
    public function validateUserSession()
    {
        startSession();

        // validate application user:
        if (!array_key_exists(FApp::USER_SESSION, $_SESSION)) {
            redirectIndex();
        }
    }
}
