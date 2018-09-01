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
        $json .= '"is_def_sampling_img":' . ($registry->getDatum("is_def_sampling_img") ? 'true' : 'false') . ', ';
        $json .= '"nk_report_delivery_type":' . $registry->getDatum("nk_report_delivery_type") . ', ';

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
        $json .= '"customer_contact":"", ';

        // get customer's contacts for reporting:
        $contacts = AppUtils::composeSelectOption();
        foreach ($registry->getChildContacts() as $contact) {
            if ($contact->getDatum("is_report")) {
                $contacts .= '<option value="' . $contact->getId() . '">' . $contact->getDatum("name") . '</option>';
            }
        }
        $json .= '"contacts":"' . addslashes($contacts) . '", ';

        // get customers's corporate members:
        $corpMembers = '';
        if ($entity->isChildEntityType(ModConsts::CC_ENTITY_TYPE_CUST_CORP)) {
            $params = array();
            $params["fk_entity_class"] = ModConsts::CC_ENTITY_CLASS_CUST;
            $params[ModEntity::PARAM_CORP_MEMBERS] = $entity->getId();
            $options = AppUtils::getSelectOptions($userSession, AppConsts::CC_ENTITY, 0, $params);
            foreach ($options as $option) {
                $corpMembers .= $option;
            }
        }
        if (empty($corpMembers)) {
            $corpMembers = AppUtils::composeSelectOption();
        }
        $json .= '"' . ModEntity::PARAM_CORP_MEMBERS . '":"' . addslashes($corpMembers) . '"';
    }
    echo '{' . $json . '}';
}
