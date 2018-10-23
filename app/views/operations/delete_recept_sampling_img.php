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
use app\models\operations\ModSample;
use app\models\operations\ModSamplingImage;

/* ?id={id of reception}&move={next|back}
 */

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // get recept to process:
    $sample;
    $userSession = FGuiUtils::createUserSession();
    if (!empty($_GET[FRegistry::ID])) {
        $samplingImage = new ModSamplingImage();
        $samplingImage->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_WRITE);
    }

    if (isset($samplingImage)) {
        if (!$samplingImage->getDatum("is_deleted")) {
            $sample = new ModSample();
            $sample->read($userSession, $samplingImage->getDatum("fk_sample"), FRegistry::MODE_READ);
            $samplingImage->setParentSample($sample);
            $samplingImage->delete($userSession);
        }
    }
}

// redirect:
header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/operations/view_recept_sampling_imgs.php?id=" . $samplingImage->getDatum("fk_sample"));
// eof
