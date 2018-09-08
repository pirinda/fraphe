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
use app\models\catalogs\ModEntity;
use app\models\operations\ModProcessArea;
use app\models\operations\ModTest;
use app\models\operations\ModTestEntity;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $json = '';

    if (!empty($_GET["test"])) {
        $userSession = FGuiUtils::createUserSession();

        $test = new ModTest();
        $test->read($userSession, intval($_GET["test"]), FRegistry::MODE_READ);

        $testEntity;
        if (!empty($_GET["entity"])) {
            $testEntity = ModTestEntity::readTestEntity($userSession, $test->getId(), intval($_GET["entity"]));
        }
        else {
            $testEntity = $test->getDefaultChildTestEntity();
        }

        $json = '"fk_test":' . $test->getId() . ', ';

        if (!isset($testEntity)) {
            $json .= '"fk_entity":0, ';
            $json .= '"entity":"", ';
            $json .= '"process_days_min":0, ';
            $json .= '"process_days_max":0, ';
            $json .= '"cost":0.0, ';
            $json .= '"process_area":""';
        }
        else {
            $entity = new ModEntity();
            $entity->read($userSession, $testEntity->getDatum("fk_entity"), FRegistry::MODE_READ);

            $processArea = new ModProcessArea();
            $processArea->read($userSession, $testEntity->getDbmsFkProcessArea(), FRegistry::MODE_READ);

            $json .= '"fk_entity":' . $testEntity->getDatum("fk_entity") . ', ';
            $json .= '"entity":"' . $entity->getDatum("name") . (empty($entity->getDatum("alias")) ? '' : ', ' . $entity->getDatum("alias")) . '", ';
            $json .= '"process_days_min":' . $testEntity->getDatum("process_days_min") . ', ';
            $json .= '"process_days_max":' . $testEntity->getDatum("process_days_max") . ', ';
            $json .= '"cost":' . $testEntity->getDatum("cost") . ', ';
            $json .= '"process_area":"' . $processArea->getDatum("name") . '"';
        }
    }
    echo '{' . $json . '}';
}
