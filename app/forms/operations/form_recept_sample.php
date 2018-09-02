<?php
//------------------------------------------------------------------------------
// start session:
if (!isset($_SESSION)) {
    session_start();
}
// bootstrap Fraphe:
require $_SESSION["rootDir"] . "Fraphe" . DIRECTORY_SEPARATOR . "fraphe.php";
//------------------------------------------------------------------------------

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
use app\models\catalogs\ModEntity;
use app\models\operations\ModRecept;
use app\models\operations\ModSample;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("recept");

$userSession = FGuiUtils::createUserSession();
$sample = new ModSample();
$recept = new ModRecept();
$customer = new ModEntity();
$errmsg = "";
$copy = false;

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (!empty($_GET[FRegistry::ID])) {
            $sample->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
            $recept->read($userSession, $sample->getDatum("nk_recept"), FRegistry::MODE_READ);
        }
        else if (!empty($_GET["recept"])) {
            $recept->read($userSession, intval($_GET["recept"]), FRegistry::MODE_READ);
            $sample = $recept->createSample($userSession);
        }

        if (!empty(!empty($_GET["copy"])) && $_GET["copy"] == "1") {
            $copy = true;
            $sample->forceRegistryNew();
        }
        break;

    case "POST":
        if (!empty($_POST[FRegistry::ID])) {
            $sample->read($userSession, intval($_POST[FRegistry::ID]), FRegistry::MODE_READ);
            $recept->read($userSession, $sample->getDatum("nk_recept"), FRegistry::MODE_READ);
        }
        else if (!empty($_POST["recept"])) {
            $recept->read($userSession, intval($_POST["recept"]), FRegistry::MODE_READ);
            $sample = $recept->createSample($userSession);
        }

        if (!empty(!empty($_POST["copy"])) && $_POST["copy"] == "1") {
            $copy = true;
            $sample->forceRegistryNew();
        }

        $data = array();

        //$data["sample_num"] = $_POST["sample_num"];
        $data["sample_name"] = $_POST["sample_name"];
        $data["sample_lot"] = $_POST["sample_lot"];
        $data["sample_date_mfg_n"] = empty($_POST["sample_date_mfg_n"]) ? 0 : FUtils::parseStdDate($_POST["sample_date_mfg_n"]);
        $data["sample_date_sell_by_n"] = empty($_POST["sample_date_sell_by_n"]) ? 0 : FUtils::parseStdDate($_POST["sample_date_sell_by_n"]);
        $data["sample_quantity"] = floatval($_POST["sample_quantity"]);
        //$data["sample_quantity_orig"] = $_POST["sample_quantity_orig"];
        //$data["sample_child"] = $_POST["sample_child"];
        //$data["sample_released"] = $_POST["sample_released"];
        $isSamplingCompany =  empty($_POST["is_sampling_company"]) ? false : boolval($_POST["is_sampling_company"]);
        if (!$isSamplingCompany) {
            $data["is_sampling_company"] = false;
            $data["sampling_datetime_n"] = 0;
            $data["sampling_temperat_n"] = 0;
            $data["sampling_area"] = "";
            $data["sampling_guide"] = 0;
            $data["sampling_deviats"] = "";
            $data["sampling_notes"] = "";
            //$data["sampling_imgs"] = $_POST["sampling_imgs"];
        }
        else {
            $data["is_sampling_company"] = true;
            $data["sampling_datetime_n"] = FUtils::parseHtmlDatetime($_POST["sampling_datetime_n"]);
            $data["sampling_temperat_n"] = floatval($_POST["sampling_temperat_n"]);
            $data["sampling_area"] = $_POST["sampling_area"];
            $data["sampling_guide"] = intval($_POST["sampling_guide"]);
            $data["sampling_deviats"] = $_POST["sampling_deviats"];
            $data["sampling_notes"] = $_POST["sampling_notes"];
            //$data["sampling_imgs"] = $_POST["sampling_imgs"];
        }
        //$data["recept_sample"] = $_POST["recept_sample"];
        //$data["recept_datetime_n"] = $_POST["recept_datetime_n"];
        $data["recept_temperat_n"] = floatval($_POST["recept_temperat_n"]);
        $data["recept_deviats"] = $_POST["recept_deviats"];
        $data["recept_notes"] = $_POST["recept_notes"];
        $data["service_type"] = $_POST["service_type"];
        //$data["process_days"] = $_POST["process_days"];
        //$data["process_start_date"] = $_POST["process_start_date"];
        //$data["process_deadline"] = $_POST["process_deadline"];
        $isCustomerCustom = empty($_POST["is_customer_custom"]) ? false : boolval($_POST["is_customer_custom"]);
        if (!$isCustomerCustom) {
            $data["is_customer_custom"] = false;
            $data["customer_name"] = "";
            $data["customer_street"] = "";
            $data["customer_district"] = "";
            $data["customer_postcode"] = "";
            $data["customer_reference"] = "";
            $data["customer_city"] = "";
            $data["customer_county"] = "";
            $data["customer_state_region"] = "";
            $data["customer_country"] = "";
            $data["customer_contact"] = "";
            $data["fk_sampling_method"] = ModConsts::OC_SAMPLING_METHOD_CUSTOMER;
            $data["nk_sampling_equipt_1"] = 0;
            $data["nk_sampling_equipt_2"] = 0;
            $data["nk_sampling_equipt_3"] = 0;
            $data["fk_user_sampler"] = ModConsts::CC_USER_NA;
        }
        else {
            $data["is_customer_custom"] = true;
            $data["customer_name"] = $_POST["customer_name"];
            $data["customer_street"] = $_POST["customer_street"];
            $data["customer_district"] = $_POST["customer_district"];
            $data["customer_postcode"] = $_POST["customer_postcode"];
            $data["customer_reference"] = $_POST["customer_reference"];
            $data["customer_city"] = $_POST["customer_city"];
            $data["customer_county"] = $_POST["customer_county"];
            $data["customer_state_region"] = $_POST["customer_state_region"];
            $data["customer_country"] = $_POST["customer_country"];
            $data["customer_contact"] = $_POST["customer_contact"];
            $data["fk_sampling_method"] = intval($_POST["fk_sampling_method"]);
            $data["nk_sampling_equipt_1"] = intval($_POST["nk_sampling_equipt_1"]);
            $data["nk_sampling_equipt_2"] = intval($_POST["nk_sampling_equipt_2"]);
            $data["nk_sampling_equipt_3"] = intval($_POST["nk_sampling_equipt_3"]);
            $data["fk_user_sampler"] = intval($_POST["fk_user_sampler"]);
        }
        $data["is_def_sampling_img"] = empty($_POST["is_def_sampling_img"]) ? false : boolval($_POST["is_def_sampling_img"]);
        $data["ref_chain_custody"] = $_POST["ref_chain_custody"];
        $data["ref_request"] = $_POST["ref_request"];
        $data["ref_agreet"] = $_POST["ref_agreet"];
        //$data["is_system"] = $_POST["is_system"];
        //$data["is_deleted"] = $_POST["is_deleted"];
        $data["fk_company_branch"] = $userSession->getCompanyBranch();
        $data["fk_customer"] = $recept->getDatum("fk_customer");
        $data["nk_customer_sample"] = intval($_POST["nk_customer_sample"]);
        $data["nk_customer_billing"] = intval($_POST["nk_customer_billing"]);
        $data["fk_report_contact"] = intval($_POST["fk_report_contact"]);
        $data["fk_report_delivery_type"] = intval($_POST["fk_report_delivery_type"]);
        //$data["fk_sample_class"] = $_POST["fk_sample_class"];
        $data["fk_sample_type"] = intval($_POST["fk_sample_type"]);
        $data["fk_sample_status"] = ModConsts::OC_SAMPLE_STATUS_RECEPT;
        //$data["nk_sample_parent"] = $_POST["nk_sample_parent"];
        $data["fk_container_type"] = intval($_POST["fk_container_type"]);
        $data["fk_container_unit"] = intval($_POST["fk_container_unit"]);
        $data["nk_recept"] = $recept->getId();
        $data["fk_user_receiver"] = empty(intval($_POST["fk_user_receiver"])) ? ModConsts::CC_USER_NA : intval($_POST["fk_user_receiver"]);

        try {
            $sample->setParentRecept($recept);
            $sample->setData($data);
            $sample->save($userSession);
            header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/forms/operations/form_recept_samples.php?id=" . $recept->getId());
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
// Input Form for Samples
////////////////////////////////////////////////////////////////////////////////

echo '<form class="form-horizontal" method="post" action="' . FUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '" onsubmit="return validateForm()" enctype="multipart/form-data">';

// preserve registry ID in post:
echo '<input type="hidden" name="' . FRegistry::ID . '" id="' . FRegistry::ID . '" value="' . $sample->getId() . '">';
echo '<input type="hidden" name="recept" value="' . $recept->getId() . '">';
echo '<input type="hidden" name="customer" id="customer" value="' . $recept->getDatum("fk_customer") . '">'; // make customer ID available to JavaScript through DOM
if ($copy) {
    echo '<input type="hidden" name="copy" value="1">';
}

$customer->read($userSession, $recept->getDatum("fk_customer"), FRegistry::MODE_READ);

//------------------------------------------------------------------------------
// Reception:
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Recepción</div>';
echo '<div class="panel-body small">';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $recept->getItem("recept_num")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><span class="bg-info lead">' . $recept->getDatum("recept_num") . '</span></div>';
echo '<div class="col-sm-2"><b>' . $recept->getItem("recept_datetime")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><span class="bg-info">' . FUtils::formatStdDatetime($recept->getDatum("recept_datetime")) . '</span></div>';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $recept->getItem("fk_customer")->getName() . ':</b></div>';
echo '<div class="col-sm-10">' . $customer->getDatum("name") . '</div>';
echo '</div>';

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Main section
//------------------------------------------------------------------------------

echo '<div class="row">';

//------------------------------------------------------------------------------
// Left panel
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
echo '<div class="col-sm-6">';

//------------------------------------------------------------------------------
echo '<div class="panel-group">';

//------------------------------------------------------------------------------
// Sample:
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos de la muestra</div>';
echo '<div class="panel-body">';

$sample->getItem("sample_num")->setGuiReadOnly(true);
echo $sample->getItem("sample_num")->composeHtmlInput(FItem::INPUT_TEXT, 4, 6);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLE_TYPE, $sample->getDatum("fk_sample_type"));
echo $sample->getItem("fk_sample_type")->composeHtmlSelect($options, 4, 8);

echo $sample->getItem("sample_name")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);

echo $sample->getItem("sample_quantity")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_CONTAINER_UNIT, $sample->getDatum("fk_container_unit"));
echo $sample->getItem("fk_container_unit")->composeHtmlSelect($options, 4, 4);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_CONTAINER_TYPE, $sample->getDatum("fk_container_type"));
echo $sample->getItem("fk_container_type")->composeHtmlSelect($options, 4, 8);

echo $sample->getItem("sample_lot")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $sample->getItem("sample_date_mfg_n")->composeHtmlInput(FItem::INPUT_DATE, 4, 4);
echo $sample->getItem("sample_date_sell_by_n")->composeHtmlInput(FItem::INPUT_DATE, 4, 4);

$params = array();
$params["fk_entity_class"] = ModConsts::CC_ENTITY_CLASS_CUST;
$params[ModEntity::PARAM_CORP_MEMBERS] = $customer->getId();
$options = AppUtils::getSelectOptions($userSession, AppConsts::CC_ENTITY, $sample->getDatum("nk_customer_sample"), $params);
echo $sample->getItem("nk_customer_sample")->composeHtmlSelect($options, 4, 8);

$params = array();
$params["fk_entity_class"] = ModConsts::CC_ENTITY_CLASS_CUST;
$options = AppUtils::getSelectOptions($userSession, AppConsts::CC_ENTITY, $sample->getDatum("nk_customer_billing"), $params);
echo $sample->getItem("nk_customer_billing")->composeHtmlSelect($options, 4, 8);

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Sampling:
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos del muestreo</div>';
echo '<div class="panel-body">';

$sample->getItem("is_sampling_company")->setGuiEvents('onchange="changedSamplingCompany(this);"');
echo $sample->getItem("is_sampling_company")->composeHtmlInput(FItem::INPUT_CHECKBOX, 0, 12);
echo $sample->getItem("sampling_datetime_n")->composeHtmlInput(FItem::INPUT_DATETIME, 4, 6);
echo $sample->getItem("sampling_area")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("sampling_temperat_n")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $sample->getItem("sampling_guide")->composeHtmlInput(FItem::INPUT_NUMBER, 4, 4);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLING_METHOD, $sample->getDatum("fk_sampling_method"));
echo $sample->getItem("fk_sampling_method")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLING_EQUIPT, $sample->getDatum("nk_sampling_equipt_1"));
echo $sample->getItem("nk_sampling_equipt_1")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLING_EQUIPT, $sample->getDatum("nk_sampling_equipt_2"));
echo $sample->getItem("nk_sampling_equipt_2")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLING_EQUIPT, $sample->getDatum("nk_sampling_equipt_3"));
echo $sample->getItem("nk_sampling_equipt_3")->composeHtmlSelect($options, 4, 8);

echo $sample->getItem("sampling_notes")->composeHtmlTextArea(4, 8, 1);
echo $sample->getItem("sampling_deviats")->composeHtmlTextArea(4, 8, 1);

$params = array();
$params["id_user_role"] = ModConsts::CC_USER_ROLE_SAMPLING;
$options = AppUtils::getSelectOptions($userSession, AppConsts::CC_USER, $sample->getDatum("fk_user_sampler"), $params);
echo $sample->getItem("fk_user_sampler")->composeHtmlSelect($options, 4, 8);

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------

echo '</div>'; // panel group
//------------------------------------------------------------------------------

echo '</div>'; // left panel
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Right panel
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
echo '<div class="col-sm-6">';

//------------------------------------------------------------------------------
echo '<div class="panel-group">';

//------------------------------------------------------------------------------
// Sample Reception:
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos de la recepción</div>';
echo '<div class="panel-body">';

echo $sample->getItem("recept_temperat_n")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

echo '<div class="form-group">';
echo '<div class="col-sm-4">';
echo '<label class="control-label small" for="service_type">' . $sample->getItem("service_type")->getName() . ': *</label>';
echo '</div>';
echo '<div class="col-sm-8">';
echo '<label class="radio-inline small"><input type="radio" name="service_type" value="O"' . ($sample->getDatum("service_type") == 'O' ? ' checked' : '') . '>Ordinario</label>';
echo '<label class="radio-inline small"><input type="radio" name="service_type" value="U"' . ($sample->getDatum("service_type") == 'U' ? ' checked' : '') . '>Urgente</label>';
echo '</div>';
echo '</div>';

echo $sample->getItem("recept_notes")->composeHtmlTextArea(4, 8, 1);
echo $sample->getItem("recept_deviats")->composeHtmlTextArea(4, 8, 1);

$params = array();
$params["id_user_role"] = ModConsts::CC_USER_ROLE_RECEPT;
$options = AppUtils::getSelectOptions($userSession, AppConsts::CC_USER, $sample->getDatum("fk_user_receiver"), $params);
echo $sample->getItem("fk_user_receiver")->composeHtmlSelect($options, 4, 8);

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// References:
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Referencias</div>';
echo '<div class="panel-body">';

echo $sample->getItem("ref_chain_custody")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $sample->getItem("ref_request")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $sample->getItem("ref_agreet")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Customer:
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos del cliente</div>';
echo '<div class="panel-body">';

$options = array();
$options[] = AppUtils::composeSelectOption();
foreach ($customer->getChildAddresses()[0]->getChildContacts() as $contact) {
    if ($contact->getDatum("is_report")) {
        $options[] = '<option value="' . $contact->getId() . '"' . ($sample->getDatum("fk_report_contact") == $contact->getId() ? 'selected' : '') . '>' . $contact->getDatum("name") . '</option>';
    }
}
echo $sample->getItem("fk_report_contact")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_REPORT_DELIVERY_TYPE, $sample->getDatum("fk_report_delivery_type"));
echo $sample->getItem("fk_report_delivery_type")->composeHtmlSelect($options, 4, 8);

echo $sample->getItem("is_def_sampling_img")->composeHtmlInput(FItem::INPUT_CHECKBOX, 0, 12);

// Address:
$sample->getItem("is_customer_custom")->setGuiEvents('onchange="changedCustomerCustom(this);"');
echo $sample->getItem("is_customer_custom")->composeHtmlInput(FItem::INPUT_CHECKBOX, 0, 12);
echo $sample->getItem("customer_name")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("customer_street")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("customer_district")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("customer_postcode")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $sample->getItem("customer_reference")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("customer_city")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("customer_county")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("customer_state_region")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("customer_country")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $sample->getItem("customer_contact")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------

echo '</div>'; // panel group
//------------------------------------------------------------------------------

echo '</div>';  // right panel
//------------------------------------------------------------------------------

echo '</div>';  // row
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
echo '<button type="submit" class="btn btn-sm btn-primary">Guardar</button>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept_samples.php?id=' . $recept->getId() . '" class="btn btn-sm btn-danger" role="button">Cancelar</a>';

echo '</form>';
echo '</div>';  // echo '<div class="container" style="margin-top:50px">';

echo FApp::composeFooter();
echo <<<SCRIPT
<script>
(function() {
    loadCustomer();
    changedCustomerCustom(document.getElementById("is_customer_custom"));
    changedSamplingCompany(document.getElementById("is_sampling_company"))
})();
var customer;
function loadCustomer() {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var json = this.responseText;
            console.log("json: " + json);
            customer = JSON.parse(json); // load in memory customers's data
        }
    };
    var url = "form_recept_load_customer.php?id=" + document.getElementById("customer").value; // hidden html input!
    request.open("GET", url);
    request.send();
}
function changedCustomerCustom(element) {
    var inputs = ["customer_name", "customer_street", "customer_district", "customer_postcode", "customer_reference", "customer_city", "customer_county", "customer_state_region", "customer_country", "customer_contact" ];
    if (element.checked) {
        for (var input of inputs) {
            document.getElementById(input).removeAttribute("disabled");
            document.getElementById(input).value = customer[input];
        }
    }
    else {
        for (var input of inputs) {
            document.getElementById(input).setAttribute("disabled", "");
            document.getElementById(input).value = "";
        }
    }
}
function changedSamplingCompany(element) {
    var inputs = ["sampling_datetime_n", "sampling_area", "sampling_temperat_n", "sampling_guide", "sampling_notes", "sampling_deviats"];
    var selects = ["fk_sampling_method", "nk_sampling_equipt_1", "nk_sampling_equipt_2", "nk_sampling_equipt_3", "fk_user_sampler"];
    if (element.checked) {
        for (var input of inputs) {
            document.getElementById(input).removeAttribute("disabled");
        }
        for (var select of selects) {
            document.getElementById(select).removeAttribute("disabled");
        }
    }
    else {
        for (var input of inputs) {
            document.getElementById(input).setAttribute("disabled", "");
            document.getElementById(input).value = "";
        }
        for (var select of selects) {
            document.getElementById(select).setAttribute("disabled", "");
            document.getElementById(select).value = 0;
        }
    }
}
function validateForm() {
    return checkBeforeSubmit(); // prevent multiple form submitions
}
</script>
SCRIPT;
echo '</body>';
echo '</html>';
