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

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $json = "";
    if (!empty($_GET["id"])) {
        $userSession = FGuiUtils::createUserSession();

        $entity = new ModEntity();
        $entity->read($userSession, intval($_GET["id"]), FRegistry::MODE_READ);

        // get customer data:
        $json .= '"customer_name":"' . $entity->getDatum("name") . '", ';

        // get customer's address data:
        $address = $entity->getChildEntityAddresses()[0];
        $json .= '"customer_street":"' . $address->getDatum("street") . '", ';
        $json .= '"customer_district":"' . $address->getDatum("district") . '", ';
        $json .= '"customer_postcode":"' . $address->getDatum("postcode") . '", ';
        $json .= '"customer_reference":"' . $address->getDatum("reference") . '", ';
        $json .= '"customer_city":"' . $address->getDatum("city") . '", ';
        $json .= '"customer_county":"' . $address->getDatum("county") . '", ';
        $json .= '"customer_state_region":"' . $address->getDatum("state_region") . '", ';
        $json .= '"customer_country":"' . $address->getDatum("country") . '", ';
        $json .= '"customer_contact":""';
    }
    echo '{' . $json . '}';
}
