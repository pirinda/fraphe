<?php
//------------------------------------------------------------------------------
// start session:
if (!isset($_SESSION)) {
    session_start();
}
// bootstrap Fraphe:
require $_SESSION["rootDir"] . "Fraphe/fraphe.php";
//------------------------------------------------------------------------------

use Fraphe\App\FAppConsts;
use Fraphe\App\FGuiUtils;
use Fraphe\Model\FRegistry;
use app\models\operations\ModRecept;
use app\models\operations\ModSample;

/* ?id={id of reception}&move={next|back}
 */

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // get recept to process:
    $sample;
    $userSession = FGuiUtils::createUserSession();
    if (!empty($_GET[FRegistry::ID])) {
        $sample = new ModSample();
        $sample->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_WRITE);
    }

    if (isset($sample)) {
        if (!$sample->getDatum("is_deleted")) {
            $recept = new ModRecept();
            $recept->read($userSession, $sample->getDatum("nk_recept"), FRegistry::MODE_READ);
            $sample->setParentRecept($recept);
            $sample->delete($userSession);
        }
    }
}

// redirect:
header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/operations/view_recept_samples.php?id=" . $sample->getDatum("nk_recept"));
// eof
