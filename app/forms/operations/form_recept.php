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
echo FAppNavbar::compose("recept");

$userSession = FGuiUtils::createUserSession();
$recept = new ModRecept();
$errmsg = "";

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (!empty($_GET[FRegistry::ID])) {
            $recept->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_WRITE);
        }
        else {
            $data = array();
            $data["service_type"] = ModRecept::SERVICE_ORDINARY;
            $recept->setData($data);
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

        $childProcessEntity = new ModTestProcessEntity();
        $childProcessEntity->setData($childData);
        $registry->getChildProcessEntitys()[] = $childProcessEntity;

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

echo '<form class="form-horizontal" method="post" action="' . FUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '" onsubmit="return validateForm()" enctype="multipart/form-data">';

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
echo $recept->getItem("recept_datetime")->composeHtmlInput(FItem::INPUT_DATETIME, 4, 6);

$params = array();
$params["fk_entity_class"] = ModConsts::CC_ENTITY_CLASS_CUST;
$options = AppUtils::getSelectOptions($userSession, AppConsts::CC_ENTITY, $recept->getDatum("fk_customer"), $params);
$recept->getItem("fk_customer")->setGuiEvents('onchange="changedCustomer(this);"');
echo $recept->getItem("fk_customer")->composeHtmlSelect($options, 4, 8);

$options = array();
$options[] = AppUtils::composeSelectOption();
echo $recept->getItem("nk_customer_sample")->composeHtmlSelect($options, 4, 8);

$options = array();
$options[] = AppUtils::composeSelectOption();
echo $recept->getItem("fk_report_contact")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_REPORT_DELIVERY_OPT, $recept->getDatum("fk_report_delivery_type"), $params);
echo $recept->getItem("fk_report_delivery_type")->composeHtmlSelect($options, 4, 8);

echo $recept->getItem("is_def_sampling_img")->composeHtmlInput(FItem::INPUT_CHECKBOX, 4, 8);

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
$recept->getItem("is_customer_custom")->setGuiEvents('onchange="changedCustomerCustom(this)";');
echo $recept->getItem("is_customer_custom")->composeHtmlInput(FItem::INPUT_CHECKBOX, 0, 12);
echo $recept->getItem("customer_name")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $recept->getItem("customer_street")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $recept->getItem("customer_district")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $recept->getItem("customer_postcode")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $recept->getItem("customer_reference")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $recept->getItem("customer_city")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $recept->getItem("customer_county")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $recept->getItem("customer_state_region")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $recept->getItem("customer_country")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $recept->getItem("customer_contact")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);

echo '</div>';  //echo '<div class="panel-body">';
echo '</div>';  //echo '<div class="panel panel-default">';

echo '</div>';  // right panel

echo '</div>';  // row


//echo '<div class="row">';
echo '<button type="submit" class="btn btn-sm btn-primary">Guardar</button>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_recept.php" class="btn btn-sm btn-danger" role="button">Cancelar</a>';
//echo '</div>';  // row

//echo '<div class="page-header">';
echo '<h3>Muestras</h3>';
//echo '</div>';

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

$options = array();
$options[] = AppUtils::composeSelectOption();
echo $recept->getItem("nk_customer_sample")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLE_CLASS, $sample->getDatum("fk_sample_class"), $params);
echo $sample->getItem("fk_sample_class")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLE_TYPE, $sample->getDatum("fk_sample_type"), $params);
echo $sample->getItem("fk_sample_type")->composeHtmlSelect($options, 4, 8);

echo $sample->getItem("name")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);

echo $sample->getItem("quantity")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_CONTAINER_UNIT, $sample->getDatum("fk_container_unit"), $params);
echo $sample->getItem("fk_container_unit")->composeHtmlSelect($options, 4, 4);

echo $sample->getItem("lot")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $sample->getItem("date_manuf_n")->composeHtmlInput(FItem::INPUT_DATE, 4, 4);
echo $sample->getItem("date_sell_by_n")->composeHtmlInput(FItem::INPUT_DATE, 4, 4);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_CONTAINER_TYPE, $sample->getDatum("fk_container_type"), $params);
echo $sample->getItem("fk_container_type")->composeHtmlSelect($options, 4, 8);

$sample->getItem("recept_datetime")->setGuiReadOnly(true);
echo $sample->getItem("recept_datetime")->composeHtmlInput(FItem::INPUT_DATETIME, 4, 6);
echo $sample->getItem("recept_temperat_n")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

$options = array();
$options[] = AppUtils::composeSelectOption();
echo $recept->getItem("fk_report_contact")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_REPORT_DELIVERY_OPT, $sample->getDatum("fk_report_delivery_type"), $params);
echo $sample->getItem("fk_report_delivery_type")->composeHtmlSelect($options, 4, 8);

echo $recept->getItem("is_def_sampling_img")->composeHtmlInput(FItem::INPUT_CHECKBOX, 4, 8);

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

$sample->getItem("is_sampling_company")->setGuiEvents('onclick="changedSamplingCompany(this);"');
echo $sample->getItem("is_sampling_company")->composeHtmlInput(FItem::INPUT_CHECKBOX, 4, 4);
echo $sample->getItem("sampling_datetime_n")->composeHtmlInput(FItem::INPUT_DATETIME, 4, 6);
echo $sample->getItem("sampling_temperat_n")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $sample->getItem("sampling_area")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $sample->getItem("sampling_guide")->composeHtmlInput(FItem::INPUT_NUMBER, 4, 4);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLING_METHOD, $sample->getDatum("fk_sampling_method"), $params);
echo $sample->getItem("fk_sampling_method")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLING_EQUIPT, $sample->getDatum("nk_sampling_equipt"), $params);
echo $sample->getItem("nk_sampling_equipt")->composeHtmlSelect($options, 4, 8);

echo $sample->getItem("sampling_notes")->composeHtmlTextArea(4, 8, 1);
echo $sample->getItem("sampling_deviats")->composeHtmlTextArea(4, 8, 1);

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
echo $recept->getItem("customer_contact")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);

echo '</div>';  //echo '<div class="panel-body">';
echo '</div>';  //echo '<div class="panel panel-default">';

echo '</div>';  // right panel

echo '</div>';  // row

//------------------------------------------------------------------------------
// main section at the right:?????
//------------------------------------------------------------------------------

echo '</form>';
echo '</div>';  // echo '<div class="container" style="margin-top:50px">';

echo FApp::composeFooter();
echo <<<SCRIPT
<script>
(function() {
    changedCustomer(document.getElementById("fk_customer"));
})();
function changedCustomer(element) {
    var selects = ["nk_customer_sample", "fk_report_contact", "fk_report_delivery_type"];
    var checkboxes = ["is_customer_custom", "is_def_sampling_img"];

    if (element.value == "0") {
        for (var select of selects) {
            document.getElementById(select).setAttribute("disabled", "");
            document.getElementById(select).value = 0;
        }
        for (var checkbox of checkboxes) {
            document.getElementById(checkbox).setAttribute("disabled", "");
            document.getElementById(checkbox).checked = false;
        }
    }
    else {
        for (var select of selects) {
            document.getElementById(select).removeAttribute("disabled");
        }
        for (var checkbox of checkboxes) {
            document.getElementById(checkbox).removeAttribute("disabled");
            document.getElementById(checkbox).checked = false;
        }
        loadCustomer();
    }
    changedCustomerCustom(document.getElementById("is_customer_custom"));
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
var customer;
function loadCustomer() {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var json = this.responseText;
            console.log("json: " + json);
            customer = JSON.parse(json);

            document.getElementById("nk_customer_sample").innerHTML = customer.corp_members;
            document.getElementById("fk_report_contact").innerHTML = customer.contacts;
            document.getElementById("fk_report_delivery_type").value = customer.nk_report_delivery_type;
            document.getElementById("is_def_sampling_img").checked = customer.is_def_sampling_img;
        }
    };
    var url = "form_recept_load_customer.php?id=" + document.getElementById("fk_customer").value;
    request.open("GET", url);
    request.send();
}
function changedSamplingCompany(element) {
    var inputs = ["sampling_datetime_n", "sampling_temperat_n", "sampling_area", "sampling_guide", "sampling_notes", "sampling_deviats"];
    var selects = ["fk_sampling_method", "nk_sampling_equipt"];
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
