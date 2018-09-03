<?php
//------------------------------------------------------------------------------
// start session:
if (!isset($_SESSION)) {
    session_start();
}
// bootstrap Fraphe:
require $_SESSION["rootDir"] . "Fraphe" . DIRECTORY_SEPARATOR . "fraphe.php";
//------------------------------------------------------------------------------

use Fraphe\App\FGuiUtils;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;
use app\models\ModConsts;
use app\models\catalogs\ModEntity;
use app\models\operations\ModProcessArea;
use app\models\operations\ModTest;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $json = "";
    if (!empty($_GET["id"])) {
        $userSession = FGuiUtils::createUserSession();

        $test = new ModTest();
        $test->read($userSession, intval($_GET["id"]), FRegistry::MODE_READ);
        $testProcessEntity = $test->getDefaultChildProcessEntity();

        $entity;
        if ($testProcessEntity->getDatum("id_entity") != 1) {
            $entity = new ModEntity();
            $entity->read($userSession, $testProcessEntity->getDatum("id_entity"), FRegistry::MODE_READ);
        }

        $processArea = new ModProcessArea();
        $processArea->read($userSession, $testProcessEntity->getDbmsFkProcessArea(), FRegistry::MODE_READ);

        if (!isset($testProcessEntity)) {
            $json .= '"id_entity":0, ';
            $json .= '"entity":"", ';
            $json .= '"process_days_min":0, ';
            $json .= '"process_days_max":0, ';
            $json .= '"cost":0.0, ';
            $json .= '"process_area":"" ';
        }
        else {
            $json .= '"id_entity":' . $testProcessEntity->getDatum("id_entity") . ', ';
            $json .= '"entity":"' . (isset($entity) ? $entity->getDatum("name") : "") . '", ';
            $json .= '"process_days_min":' . $testProcessEntity->getDatum("process_days_min") . ', ';
            $json .= '"process_days_max":' . $testProcessEntity->getDatum("process_days_max") . ', ';
            $json .= '"cost":' . $testProcessEntity->getDatum("cost") . ', ';
            $json .= '"process_area":"' . $processArea->getDatum("name") . '" ';
        }
    }
    echo '{' . $json . '}';
}
