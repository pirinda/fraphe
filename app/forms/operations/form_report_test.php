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
use app\models\catalogs\ModEntity;
use app\models\operations\ModReport;
use app\models\operations\ModReportTest;
use app\models\operations\ModRecept;
use app\models\operations\ModSample;
use app\models\operations\ModTest;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("report");

$userSession = FGuiUtils::createUserSession();
$reportTest = new ModReportTest();
$report = new ModReport();
$errmsg = "";

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (!empty($_GET[FRegistry::ID])) {
            $reportTest->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
            $report->read($userSession, $reportTest->getDatum("fk_report"), FRegistry::MODE_READ);
        }
        break;

    case "POST":
        if (!empty($_POST[FRegistry::ID])) {
            $reportTest->read($userSession, intval($_POST[FRegistry::ID]), FRegistry::MODE_READ);
            $report->read($userSession, $reportTest->getDatum("fk_report"), FRegistry::MODE_READ);
        }

        $data = array();

        $data["result"] = $_POST["result"];
        $data["uncertainty"] = $_POST["uncertainty"];
        $data["permiss_limit"] = $_POST["permiss_limit"];
        $data["nk_result_unit"] = $_POST["nk_result_unit"];

        try {
            $reportTest->setData($data);

            FModelUtils::save($userSession, $reportTest);

            header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/operations/view_report_tests.php?id=" . $report->getId());
        }
        catch (Exception $e) {
            $errmsg = $e->getMessage();
        }
        break;

    default:
}

echo '<div class="container" style="margin-top:50px">';
echo '<div class="page-header">';
echo '<h3>Ensayo del informe de resultados</h3>';
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

echo '<form class="form-horizontal" method="post" action="' . FLibUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '" onsubmit="return validateForm();">';

// preserve registry ID in post:
echo '<input type="hidden" name="' . FRegistry::ID . '" value="' . $reportTest->getId() . '">';

$sample = new ModSample();
$customer = new ModEntity();
$test = new ModTest();

$sample->read($userSession, $report->getDatum("fk_sample"), FRegistry::MODE_READ);
$customer->read($userSession, $report->getDatum("fk_customer"), FRegistry::MODE_READ);
$test->read($userSession, $reportTest->getDatum("fk_test"), FRegistry::MODE_READ);

//------------------------------------------------------------------------------
echo '<div class="panel-group">';
//------------------------------------------------------------------------------
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos del informe de resultados</div>';
echo '<div class="panel-body small">';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $report->getItem("report_num")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><span class="bg-info lead">' . $report->getDatum("report_num") . '</span></div>';
echo '<div class="col-sm-2"><b>' . $report->getItem("report_date")->getName() . ':</b></div>';
echo '<div class="col-sm-2"><span class="bg-info">' . FLibUtils::formatStdDate($report->getDatum("report_date")) . '</span></div>';
echo '<div class="col-sm-2"><b>' . $report->getItem("reissue")->getName() . ':</b></div>';
echo '<div class="col-sm-1"><span class="bg-info">' . $report->getDatum("reissue") . '</span></div>';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $report->getItem("fk_customer")->getName() . ':</b></div>';
echo '<div class="col-sm-10">' . $customer->getDatum("name") . '</div>';
echo '</div>';

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos de la muestra</div>';
echo '<div class="panel-body small">';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_num")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><span class="bg-info">' . $sample->getDatum("sample_num") . '</span></div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("recept_datetime_n")->getName() . ':</b></div>';
echo '<div class="col-sm-5"><span class="bg-info">' . FLibUtils::formatStdDatetime($sample->getDatum("recept_datetime_n")) . '</span></div>';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_name")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><mark>' . $sample->getDatum("sample_name") . '</mark></div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("recept_notes")->getName() . ':</b></div>';
echo '<div class="col-sm-5">' . $sample->getDatum("recept_notes") . '</div>';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_quantity")->getName() . ':</b></div>';
echo '<div class="col-sm-3">' . $sample->getDatum("sample_quantity") . ' ' . AppUtils::readField($userSession, "code", AppConsts::OC_CONTAINER_UNIT, $sample->getDatum("fk_container_unit")) . '</div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("recept_deviations")->getName() . ':</b></div>';
echo '<div class="col-sm-5">' . $sample->getDatum("recept_deviations") . '</div>';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_lot")->getName() . ':</b></div>';
echo '<div class="col-sm-3">' . $sample->getDatum("sample_lot") . '</div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sampling_notes")->getName() . ':</b></div>';
echo '<div class="col-sm-5">' . $sample->getDatum("sampling_notes") . '</div>';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $sample->getItem("service_type")->getName() . ':</b></div>';
echo '<div class="col-sm-3">' . ($sample->getDatum("service_type") == ModRecept::SERVICE_URGENT ? "Urgente" : "Ordinario") . '</div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sampling_deviations")->getName() . ':</b></div>';
echo '<div class="col-sm-5">' . $sample->getDatum("sampling_deviations") . '</div>';
echo '</div>';

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------
echo '</div>';
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Main section
//------------------------------------------------------------------------------

echo '<div class="row">';

//------------------------------------------------------------------------------
// Sample:
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Resultado del ensayo</div>';
echo '<div class="panel-body">';

echo '<div class="row small">';
echo '<div class="col-sm-2"><b>' . $reportTest->getItem("fk_test")->getName() . ':</b></div>';
echo '<div class="col-sm-10">' . $test->getDatum("name") . '</div>';
echo '</div>';
echo '<div class="row small">';
echo '<div class="col-sm-2"><b>' . $test->getItem("fk_testing_method")->getName() . ':</b></div>';
echo '<div class="col-sm-10">' . AppUtils::readField($userSession, "name", AppConsts::OC_TESTING_METHOD, $test->getDatum("fk_testing_method")) . '</div>';
echo '</div>';

echo '<br>';

echo $reportTest->getItem("result")->composeHtmlInput(FItem::INPUT_TEXT, 2, 4);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_RESULT_UNIT, $reportTest->getDatum("nk_result_unit"));
echo $reportTest->getItem("nk_result_unit")->composeHtmlSelect($options, 2, 4);

echo $reportTest->getItem("uncertainty")->composeHtmlInput(FItem::INPUT_TEXT, 2, 2);

echo $reportTest->getItem("permiss_limit")->composeHtmlInput(FItem::INPUT_TEXT, 2, 6);

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------

echo '</div>';  // row
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
echo '<button type="submit" class="btn btn-sm btn-primary">Guardar</button>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_report_tests.php?id=' . $report->getId() . '" class="btn btn-sm btn-danger" role="button">Cancelar</a>';

echo '</form>';
echo '</div>';  // echo '<div class="container" style="margin-top:50px">';

echo FApp::composeFooter();
echo <<<SCRIPT
<script>
function validateForm() {
    return checkBeforeSubmit(); // prevent multiple form submitions
}
</script>
SCRIPT;
echo '</body>';
echo '</html>';
