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

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $json = "";
    if (!empty($_GET["id"])) {
        // read customer:
        $userSession = FGuiUtils::createUserSession();
        $entity = new ModEntity();
        $entity->read($userSession, intval($_GET["id"]), FRegistry::MODE_READ);

        // get customer data:
        $registry = $entity;
        $json .= '"customer_name":"' . $registry->getDatum("name") . '", ';

        // get customer's address data:
        $registry = $entity->getChildAddresses()[0];
        $json .= '"customer_street":"' . $registry->getDatum("street") . '", ';
        $json .= '"customer_district":"' . $registry->getDatum("district") . '", ';
        $json .= '"customer_postcode":"' . $registry->getDatum("postcode") . '", ';
        $json .= '"customer_reference":"' . $registry->getDatum("reference") . '", ';
        $json .= '"customer_city":"' . $registry->getDatum("city") . '", ';
        $json .= '"customer_county":"' . $registry->getDatum("county") . '", ';
        $json .= '"customer_state_region":"' . $registry->getDatum("state_region") . '", ';
        $json .= '"customer_country":"' . $registry->getDatum("country") . '", ';
        $json .= '"customer_contact":""';
    }
    echo '{' . $json . '}';
}
