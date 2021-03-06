<?php
//------------------------------------------------------------------------------
// start session:
if (!isset($_SESSION)) {
    session_start();
}
// bootstrap Fraphe:
require $_SESSION["rootDir"] . "Fraphe/fraphe.php";
//------------------------------------------------------------------------------

use Fraphe\App\FApp;
use Fraphe\App\FAppConsts;
use Fraphe\App\FAppNavbar;
use Fraphe\App\FGuiUtils;
use Fraphe\Lib\FLibUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FModelUtils;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;
use app\models\ModConsts;
use app\models\operations\ModReport;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("report");

$userSession = FGuiUtils::createUserSession();
$report = new ModReport();
$errmsg = "";

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        // read registry:

        if (!empty($_GET[FRegistry::ID])) {
            $report->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
        }
        break;

    case "POST":
        if (!empty($_POST[FRegistry::ID])) {
            $report->read($userSession, intval($_POST[FRegistry::ID]), FRegistry::MODE_WRITE);
        }

        $data = array();

        $data["report_num"] = $_POST["report_num"];
        $data["report_date"] = $_POST["report_date"];
        //$data["process_deviations"] = $_POST["process_deviations"]; by now, it seems that deviations are not required
        $data["process_notes"] = $_POST["process_notes"];
        $data["fk_result_permiss_limit"] = intval($_POST["fk_result_permiss_limit"]);
        $data["fk_user_valid"] = empty(intval($_POST["fk_user_valid"])) ? ModConsts::CC_USER_NA : intval($_POST["fk_user_valid"]);

        try {
            $report->setData($data);

            FModelUtils::save($userSession, $report);

            header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/operations/view_report.php");
        }
        catch (Exception $e) {
            $errmsg = $e->getMessage();
        }
        break;

    default:
}

echo '<div class="container" style="margin-top:50px">';
echo '<div class="page-header">';
echo '<h3>Informe de resultados</h3>';
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

echo '<form class="form-horizontal" method="post" action="' . FLibUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '" onsubmit="return validateForm();">';

// preserve registry ID in post:
echo '<input type="hidden" name="' . FRegistry::ID . '" value="' . $report->getId() . '">';

//------------------------------------------------------------------------------
// main section:
//------------------------------------------------------------------------------
echo '<div class="row">';

//------------------------------------------------------------------------------
// Left panel
//------------------------------------------------------------------------------
echo '<div class="col-sm-6">';

echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos del informe de resultados</div>';
echo '<div class="panel-body">';

$report->getItem("report_num")->setGuiReadOnly(true);
echo $report->getItem("report_num")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

echo $report->getItem("report_date")->composeHtmlInput(FItem::INPUT_DATE, 4, 4);

echo '</div>';  //echo '<div class="panel-body">';
echo '</div>';  //echo '<div class="panel panel-default">';

echo '</div>';  // left panel

//------------------------------------------------------------------------------
// Right panel
//------------------------------------------------------------------------------
echo '<div class="col-sm-6">';

echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Referencias</div>';
echo '<div class="panel-body">';

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_RESULT_PERMISS_LIMIT, $report->getDatum("fk_result_permiss_limit"));
echo $report->getItem("fk_result_permiss_limit")->composeHtmlSelect($options, 4, 8);
//echo $report->getItem("process_deviations")->composeHtmlTextArea(4, 8, 3); // by now, it seems that deviations are not required
echo $report->getItem("process_notes")->composeHtmlTextArea(4, 8, 3);

// sampling notes:
$options = AppUtils::getSelectRawOptions($userSession, AppConsts::OC_TESTING_NOTE);
echo '<div class="form-group">';
echo '<div class="col-sm-4">';
echo '<label class="control-label small" for="frequent_process_notes">Observaciones frecuentes:</label>';
echo '</div>';
echo '<div class="col-sm-8">';
echo '<select class="form-control input-sm" name="frequent_process_notes" id="frequent_process_notes" onchange="changedNotes(this, \'process_notes\');">';
foreach ($options as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';
echo '</div>';


$params = array();
$params["id_user_role"] = ModConsts::CC_USER_ROLE_REPORT;
$options = AppUtils::getSelectOptions($userSession, AppConsts::CC_USER, $report->getDatum("fk_user_valid"), $params);
echo $report->getItem("fk_user_valid")->composeHtmlSelect($options, 4, 8);

echo '</div>';  //echo '<div class="panel-body">';
echo '</div>';  //echo '<div class="panel panel-default">';

echo '</div>';  // right panel

echo '</div>';  // row

//echo '<div class="row">';
echo '<button type="submit" class="btn btn-sm btn-primary">Guardar</button>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_report.php" class="btn btn-sm btn-danger" role="button">Cancelar</a>';
//echo '</div>';  // row

echo '</form>';
echo '</div>';  // echo '<div class="container" style="margin-top:50px">';

echo FApp::composeFooter();
echo <<<SCRIPT
<script>
function changedNotes(element, targetId) {
    if (element.selectedIndex > 0) {
        var value = document.getElementById(targetId).value;
        document.getElementById(targetId).value = value + (value.length == 0 ? "" : " ") + element.value;
    }
    document.getElementById(targetId).focus();
}

function validateForm() {
    return checkBeforeSubmit(); // prevent multiple form submitions
}
</script>
SCRIPT;
echo '</body>';
echo '</html>';
