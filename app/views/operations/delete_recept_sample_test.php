<?php
//------------------------------------------------------------------------------
// start session:
if (!isset($_SESSION)) {
    session_start();
}
// bootstrap Fraphe:
require $_SESSION["rootDir"] . "Fraphe" . DIRECTORY_SEPARATOR . "fraphe.php";
//------------------------------------------------------------------------------

use Fraphe\App\FAppConsts;
use Fraphe\App\FGuiUtils;
use Fraphe\Model\FRegistry;
use app\models\operations\ModSampleTest;

/* ?id={id of reception}&move={next|back}
 */

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // get recept to process:
    $sample;
    $userSession = FGuiUtils::createUserSession();
    if (!empty($_GET[FRegistry::ID])) {
        $sampleTest = new ModSampleTest();
        $sampleTest->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_WRITE);
    }

    if (isset($sampleTest)) {
        if (!$sampleTest->getDatum("is_deleted")) {
            $sampleTest->delete($userSession);
        }
    }
}

// redirect:
header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/operations/view_recept_sample_tests.php?id=" . $sampleTest->getDatum("fk_sample"));
// eof
