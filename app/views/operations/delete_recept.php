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

/* ?id={id of reception}&move={next|back}
 */

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // get recept to process:
    $recept;
    $userSession = FGuiUtils::createUserSession();
    if (!empty($_GET[FRegistry::ID])) {
        $recept = new ModRecept();
        $recept->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_WRITE);
    }

    if (isset($recept)) {
        if (!$recept->getDatum("is_deleted")) {
            $recept->delete($userSession);
        }
    }
}

// redirect:
header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/operations/view_recept.php");
// eof
