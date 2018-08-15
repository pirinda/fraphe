<?php
// start session:
if (!isset($_SESSION)) {
    session_start();
}

// bootstrap Fraphe:
require $_SESSION["rootDir"] . "Fraphe" . DIRECTORY_SEPARATOR . "fraphe.php";

use Fraphe\App\FApp;
use Fraphe\App\FAppConsts;
use Fraphe\App\FAppNavbar;
use Fraphe\App\FGuiUtils;
use Fraphe\Lib\FUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;
use app\models\ModConsts;
use app\models\operations\ModRecept;
use app\models\operations\ModSample;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("catalogs");

$userSession = FGuiUtils::createUserSession();
$recept = new ModRecept();
$errmsg = "";

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (!empty($_GET[FRegistry::ID])) {
            $registry->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_WRITE);
        }
        break;

    case "POST":
        $data = array();
        $childData = array();

        if (!empty($_POST[FRegistry::ID])) {
            $data["id_test"] = intval($_POST[FRegistry::ID]);
            $childData["id_test"] = $data["id_test"];
        }

        $data["name"] = $_POST["name"];
        $data["code"] = $_POST["code"];
        $data["sample_quantity"] = $_POST["sample_quantity"];
        $data["sample_directs"] = $_POST["sample_directs"];
        //$data["is_system"] = $_POST["is_system"];
        //$data["is_deleted"] = $_POST["is_deleted"];
        $data["fk_process_area"] = intval($_POST["fk_process_area"]);
        $data["fk_sample_class"] = intval($_POST["fk_sample_class"]);
        $data["fk_testing_method"] = intval($_POST["fk_testing_method"]);
        $data["fk_test_acredit_attrib"] = intval($_POST["fk_test_acredit_attrib"]);

        $childData["id_entity"] = 1;    // current company
        $childData["process_days_min"] = intval($_POST["po_process_days_min"]);
        $childData["process_days_max"] = intval($_POST["po_process_days_max"]);
        $childData["cost"] = floatval($_POST["po_cost"]);
        $childData["is_default"] = boolval($_POST["po_is_default"]);

        $childProcessOpt = new ModTestProcessOpt();
        $childProcessOpt->setData($childData);
        $registry->getChildProcessOpt()[] = $childProcessOpt;

        try {
            $registry->setData($data);
            $registry->save($userSession);
            header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/operations/view_test.php");
        }
        catch (Exception $e) {
            $errmsg = $e->getMessage();
        }
        break;

    default:
}

echo '<div class="container" style="margin-top:50px">';
echo '<div class="page-header">';
echo '<h2>Recepción de muestras</h2>';
echo '</div>';

if (!empty($errmsg)) {
    echo '<div class="alert alert-danger alert-dismissible">';
    echo '<a href="#" class="close" data-dismiss="alert" aria-label="cerrar">&times;</a>';
    echo '<strong>¡Error de captura!</strong> ' . $errmsg;
    echo '</div>';
}

////////////////////////////////////////////////////////////////////////////////
// Input Form for Reception
////////////////////////////////////////////////////////////////////////////////

echo '<form class="form-horizontal" method="post" action="' . FUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '" onsubmit="return validateForm()">';

// preserve registry ID in post:
echo '<input type="hidden" name="' . FRegistry::ID . '" value="' . $recept->getId() . '">';

//------------------------------------------------------------------------------
// main section:
//------------------------------------------------------------------------------
echo '<div class="row">';

//------------------------------------------------------------------------------
// Left panel
//------------------------------------------------------------------------------
echo '<div class="col-sm-6">';

echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos generales</div>';
echo '<div class="panel-body">';

$recept->getItem("number")->setGuiReadOnly(true);
echo $recept->getItem("number")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

$recept->getItem("recept_datetime")->setGuiReadOnly(true);
echo $recept->getItem("recept_datetime")->composeHtmlInput(FItem::INPUT_TEXT, 4, 6);

$params = array();
$params["fk_entity_class"] = ModConsts::CC_ENTITY_CLASS_CUST;
$options = AppUtils::getSelectOptions($userSession, AppConsts::CC_ENTITY, $recept->getDatum("fk_customer"), $params);
echo $recept->getItem("fk_customer")->composeHtmlSelect($options, 4, 8);

echo '<div class="form-group">';
echo '<div class="col-sm-4">';
echo '<label class="control-label small" for="service_type">' . $recept->getItem("service_type")->getName() . ': *</label>';
echo '</div>';
echo '<div class="col-sm-8">';
echo '<label class="radio-inline small"><input type="radio" name="service_type" value="O"' . ($recept->getDatum("service_type") == 'O' ? ' checked' : '') . '>Ordinario</label>';
echo '<label class="radio-inline small"><input type="radio" name="service_type" value="U"' . ($recept->getDatum("service_type") == 'U' ? ' checked' : '') . '>Urgente</label>';
echo '</div>';
echo '</div>';

echo $recept->getItem("ref_chain_custody")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $recept->getItem("ref_request")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $recept->getItem("ref_agreet")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

echo $recept->getItem("recept_notes")->composeHtmlTextArea(4, 8, 1);
echo $recept->getItem("recept_deviats")->composeHtmlTextArea(4, 8, 1);



echo '</div>';  //echo '<div class="panel-body">';
echo '</div>';  //echo '<div class="panel panel-default">';

echo '</div>';  // left panel

//------------------------------------------------------------------------------
// Right panel
//------------------------------------------------------------------------------
echo '<div class="col-sm-6">';

echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Cliente</div>';
echo '<div class="panel-body">';

// Address:
echo $recept->getItem("is_customer_custom")->composeHtmlInput(FItem::INPUT_CHECKBOX, 4, 4);
echo $recept->getItem("customer_name")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $recept->getItem("customer_street")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $recept->getItem("customer_district")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $recept->getItem("customer_postcode")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $recept->getItem("customer_reference")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $recept->getItem("customer_city")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $recept->getItem("customer_county")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $recept->getItem("customer_state_region")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $recept->getItem("customer_country")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_REPORT_DELIVERY_OPT, $recept->getDatum("fk_report_delivery_opt"), $params);
echo $recept->getItem("fk_report_delivery_opt")->composeHtmlSelect($options, 4, 8);

echo '</div>';  //echo '<div class="panel-body">';
echo '</div>';  //echo '<div class="panel panel-default">';

echo '</div>';  // right panel

echo '</div>';  // row


//------------------------------------------------------------------------------
// main section:
//------------------------------------------------------------------------------
echo '<div class="row">';

//------------------------------------------------------------------------------
// Left panel
//------------------------------------------------------------------------------
echo '<div class="col-sm-6">';

echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Muestras</div>';
echo '<div class="panel-body">';

$sample = new ModSample();

$sample->getItem("number")->setGuiReadOnly(true);
echo $sample->getItem("number")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLE_CLASS, $sample->getDatum("fk_sample_class"), $params);
echo $sample->getItem("fk_sample_class")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLE_TYPE, $sample->getDatum("fk_sample_type"), $params);
echo $sample->getItem("fk_sample_type")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_CONTAINER_TYPE, $sample->getDatum("fk_container_type"), $params);
echo $sample->getItem("fk_container_type")->composeHtmlSelect($options, 4, 8);

echo $sample->getItem("name")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("lot")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $sample->getItem("date_manuf_n")->composeHtmlInput(FItem::INPUT_DATE, 4, 4);
echo $sample->getItem("date_sell_by_n")->composeHtmlInput(FItem::INPUT_DATE, 4, 4);

echo $sample->getItem("quantity")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_CONTAINER_UNIT, $sample->getDatum("fk_container_unit"), $params);
echo $sample->getItem("fk_container_unit")->composeHtmlSelect($options, 4, 8);

echo $sample->getItem("is_sampling_company")->composeHtmlInput(FItem::INPUT_CHECKBOX, 4, 4);
echo $sample->getItem("sampling_guide")->composeHtmlInput(FItem::INPUT_NUMBER, 4, 4);
echo $sample->getItem("sampling_area")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("sampling_datetime_n")->composeHtmlInput(FItem::INPUT_DATETIME, 4, 6);
echo $sample->getItem("sampling_temperat_n")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLING_METHOD, $sample->getDatum("fk_sampling_method"), $params);
echo $sample->getItem("fk_sampling_method")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLING_EQUIPT, $sample->getDatum("nk_sampling_equipt"), $params);
echo $sample->getItem("nk_sampling_equipt")->composeHtmlSelect($options, 4, 8);

echo $sample->getItem("sampling_notes")->composeHtmlTextArea(4, 8, 1);
echo $sample->getItem("sampling_deviats")->composeHtmlTextArea(4, 8, 1);

echo $sample->getItem("recept_datetime")->composeHtmlInput(FItem::INPUT_DATETIME, 4, 6);
echo $sample->getItem("recept_temperat")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

echo '<div class="form-group">';
echo '<div class="col-sm-4">';
echo '<label class="control-label small" for="service_type">' . $sample->getItem("service_type")->getName() . ': *</label>';
echo '</div>';
echo '<div class="col-sm-8">';
echo '<label class="radio-inline small"><input type="radio" name="service_type" value="O"' . ($sample->getDatum("service_type") == 'O' ? ' checked' : '') . '>Ordinario</label>';
echo '<label class="radio-inline small"><input type="radio" name="service_type" value="U"' . ($sample->getDatum("service_type") == 'U' ? ' checked' : '') . '>Urgente</label>';
echo '</div>';
echo '</div>';

echo $sample->getItem("ref_chain_custody")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $sample->getItem("ref_request")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $sample->getItem("ref_agreet")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

echo $sample->getItem("recept_notes")->composeHtmlTextArea(4, 8, 1);
echo $sample->getItem("recept_deviats")->composeHtmlTextArea(4, 8, 1);

echo '</div>';  //echo '<div class="panel-body">';
echo '</div>';  //echo '<div class="panel panel-default">';

echo '</div>';  // left panel

//------------------------------------------------------------------------------
// Right panel
//------------------------------------------------------------------------------
echo '<div class="col-sm-6">';

echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Cliente</div>';
echo '<div class="panel-body">';

// Address:
echo $sample->getItem("is_customer_custom")->composeHtmlInput(FItem::INPUT_CHECKBOX, 4, 4);
echo $sample->getItem("customer_name")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("customer_street")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("customer_district")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("customer_postcode")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $sample->getItem("customer_reference")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("customer_city")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("customer_county")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("customer_state_region")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("customer_country")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_REPORT_DELIVERY_OPT, $sample->getDatum("fk_report_delivery_opt"), $params);
echo $sample->getItem("fk_report_delivery_opt")->composeHtmlSelect($options, 4, 8);


echo '</div>';  //echo '<div class="panel-body">';
echo '</div>';  //echo '<div class="panel panel-default">';

echo '</div>';  // right panel

echo '</div>';  // row

//------------------------------------------------------------------------------
// contacts section:
//------------------------------------------------------------------------------
/*
echo '<div class="row">';

for ($panel = 1; $panel <= 2; $panel++) {
    $from;
    $to;
    switch ($panel) {
        case 1:
            $from = ModConsts::CC_CONTACT_TYPE_MAIN;    // 1
            $to = ModConsts::CC_CONTACT_TYPE_PROCESS;   // 5
            break;
        case 2:
            $from = ModConsts::CC_CONTACT_TYPE_RESULT;  // 6
            $to = ModConsts::CC_CONTACT_TYPE_COLL;      // 10
            break;
        default:
    }

    echo '<div class="col-sm-6">';
    echo '<div class="panel-group" id="accordion' . $panel . '">';

    for ($contactType = $from; $contactType <= $to; $contactType++) {
        $prefix = ModContact::PREFIX . $contactType . "_";
        $contact = $receptAddress->getChildContact($contactType);

        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading">';
        echo '<div class="panel-title">';
        echo '<a id="' . $prefix . 'panel" class="small" data-toggle="collapse" data-parent="#accordion' . $panel . '" href="#collapse' . $contactType . '">';
        echo 'Contacto ' . strtolower(AppUtils::getName($userSession, AppConsts::CC_CONTACT_TYPE, $contactType)) . '</a>';
        echo '&nbsp;<span id="' . $prefix . 'icon_ok" class="' . (!empty($contact) ? "glyphicon glyphicon-ok-sign small" : "") . '"></span>';
        echo '&nbsp;<span id="' . $prefix . 'icon_file" class="' . (!empty($contact) && $contact->getItem("is_report")->getValue() ? "glyphicon glyphicon-file small" : "") . '"></span>';
        echo '</div>';
        echo '</div>';
        echo '<div id="collapse' . $contactType . '" class="panel-collapse collapse' . ($contactType == $from ? " in" : "") . '">';
        echo '<div class="panel-body">';

        echo '<div class="form-group">';
        echo '<div class="col-sm-offset-0 col-sm-12">';
        echo '<div class="checkbox">';
        echo '<label class="small"><input type="checkbox" name="' . $prefix . 'apply" onclick="showContactIcons(\'' . $prefix . '\')" value="1"' . (empty($contact) ? "" : " checked") . '>Aplica</label>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        if (empty($contact)) {
            $contact = new ModContact();
        }

        echo '<input type="hidden" name="' . $prefix . 'id_contact" value="' . $contact->getId() . '">';
        echo '<input type="hidden" name="' . $prefix . 'fk_contact_type" value="' . $contactType . '">';
        echo $contact->getItem("surname")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, $prefix);
        echo $contact->getItem("forename")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, $prefix);
        echo $contact->getItem("prefix")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4, $prefix);
        echo $contact->getItem("job")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, $prefix);
        echo $contact->getItem("mail")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, $prefix);
        echo $contact->getItem("phone")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, $prefix);
        echo $contact->getItem("mobile")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, $prefix);
        echo $contact->getItem("is_report")->composeHtmlInput(FItem::INPUT_CHECKBOX, 0, 12, $prefix);
        echo '<script>document.forms[0].elements["' . $prefix . 'is_report"].setAttribute("onclick", "showContactIcons(\'' . $prefix . '\')");</script>';

        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';  // accordion
    echo '</div>';  // panel
}

echo '</div>';  // row
*/
//------------------------------------------------------------------------------
// main section at the right:
//------------------------------------------------------------------------------
echo '<button type="submit" class="btn btn-sm btn-primary">Guardar</button>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_recept.php" class="btn btn-sm btn-danger" role="button">Cancelar</a>';

echo '</form>';
echo '</div>';  // echo '<div class="container" style="margin-top:50px">';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
