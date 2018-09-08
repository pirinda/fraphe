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
use Fraphe\Model\FModel;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;
use app\models\ModConsts;
use app\models\operations\ModSample;
use app\models\operations\ModSampleTest;
use app\models\operations\ModTest;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("recept");

$userSession = FGuiUtils::createUserSession();
$sample = new ModSample();
$sampleTest = new ModSampleTest();
$errmsg = "";

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (!empty($_GET[FRegistry::ID])) {
            $sampleTest->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
            $sample->read($userSession, $sampleTest->getDatum("fk_sample"), FRegistry::MODE_READ);
        }
        else if (!empty($_GET["sample"])) {
            $sample->read($userSession, intval($_GET["sample"]), FRegistry::MODE_READ);
        }
        break;

    case "POST":
        if (!empty($_POST[FRegistry::ID])) {
            $sampleTest->read($userSession, intval($_POST[FRegistry::ID]), FRegistry::MODE_READ);
            $sample->read($userSession, $sampleTest->getDatum("fk_sample"), FRegistry::MODE_READ);
        }
        else if (!empty($_POST["sample"])) {
            $sample->read($userSession, intval($_POST["sample"]), FRegistry::MODE_READ);
        }

        $data = array();

        //$data["sample_test"] = $_POST["sample_test"];
        $data["process_days_min"] = intval($_POST["process_days_min"]);
        $data["process_days_max"] = intval($_POST["process_days_max"]);
        //$data["process_days"] = $_POST["process_days"];
        $data["process_start_date"] = $sample->getDatum("process_start_date");
        //$data["process_deadline"] = $_POST["process_deadline"];
        $data["cost"] = floatval($_POST["cost"]);
        //$data["is_system"] = $_POST["is_system"];
        //$data["is_deleted"] = $_POST["is_deleted"];
        $data["fk_sample"] = $sample->getId();
        $data["fk_test"] = intval($_POST["fk_test"]);
        $data["fk_entity"] = empty($_POST["fk_entity"]) ? 1 : intval($_POST["fk_entity"]); // 1 = this company itself
        //$data["fk_process_area"] = $_POST["fk_process_area"];

        try {
            $sampleTest->setData($data);

            FModel::save($userSession, $sampleTest);

            header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/operations/view_recept_sample_tests.php?id=" . $sample->getId());
        }
        catch (Exception $e) {
            $errmsg = $e->getMessage();
        }
        break;

    default:
}

echo '<div class="container" style="margin-top:50px">';
echo '<div class="page-header">';
echo '<h3>Ensayo de la muestra</h3>';
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

echo '<form class="form-horizontal" method="post" action="' . FUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '" onsubmit="return validateForm()">';

// preserve registry ID in post:
echo '<input type="hidden" name="' . FRegistry::ID . '" value="' . $sampleTest->getId() . '">';
echo '<input type="hidden" name="sample" value="' . $sample->getId() . '">';

//------------------------------------------------------------------------------
// Sample:
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos de la muestra</div>';
echo '<div class="panel-body small">';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_num")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><span class="bg-info lead">' . $sample->getDatum("sample_num") . '</span></div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("recept_datetime_n")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><span class="bg-info">' . FUtils::formatStdDatetime($sample->getDatum("recept_datetime_n")) . '</span></div>';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_name")->getName() . ':</b></div>';
echo '<div class="col-sm-3">' . $sample->getDatum("sample_name") . '</div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_quantity")->getName() . ':</b></div>';
echo '<div class="col-sm-2">' . $sample->getDatum("sample_quantity") . ' ' . $sample->getDbmsContainerUnitCode() . '</div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_lot")->getName() . ':</b></div>';
echo '<div class="col-sm-1">' . $sample->getDatum("sample_lot") . '</div>';
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
// Sample:
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Configuración del ensayo</div>';
echo '<div class="panel-body">';

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_TEST, $sampleTest->getDatum("fk_test"));
$sampleTest->getItem("fk_test")->setGuiEvents('onchange="changedTest(false);"');
echo $sampleTest->getItem("fk_test")->composeHtmlSelect($options, 4, 8);

echo '<br>';

echo '<div class="row small form-group">';
echo '<div class="col-sm-4"><b>' . $sampleTest->getItem("fk_entity")->getName() . ':</b></div>';
echo '<div class="col-sm-8"><span id="' . ModTest::PREFIX . 'entity"></span></div>';
echo '</div>';

echo '<div class="row small form-group">';
echo '<div class="col-sm-4"><b>' . $sampleTest->getItem("process_days_min")->getName() . ':</b></div>';
echo '<div class="col-sm-8"><span id="' . ModTest::PREFIX . 'process_days_min"></span></div>';
echo '</div>';

echo '<div class="row small form-group">';
echo '<div class="col-sm-4"><b>' . $sampleTest->getItem("process_days_max")->getName() . ':</b></div>';
echo '<div class="col-sm-8"><span id="' . ModTest::PREFIX . 'process_days_max"></span></div>';
echo '</div>';

echo '<div class="row small form-group">';
echo '<div class="col-sm-4"><b>' . $sampleTest->getItem("cost")->getName() . ':</b></div>';
echo '<div class="col-sm-8"><span id="' . ModTest::PREFIX . 'cost"></span></div>';
echo '</div>';

echo '<div class="row small form-group">';
echo '<div class="col-sm-4"><b>' . $sampleTest->getItem("fk_process_area")->getName() . ':</b></div>';
echo '<div class="col-sm-8"><span id="' . ModTest::PREFIX . 'process_area"></span></div>';
echo '</div>';

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------

echo '</div>'; // left panel
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Right panel
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
echo '<div class="col-sm-6">';

//------------------------------------------------------------------------------
// Sample Reception:
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos del ensayo</div>';
echo '<div class="panel-body">';

$params = array();
$params["fk_entity_class"] = ModConsts::CC_ENTITY_CLASS_PROV;
$params["entity_type"] = ModConsts::CC_ENTITY_TYPE_PROV_LAB;
$options = AppUtils::getSelectOptions($userSession, AppConsts::CC_ENTITY, $sampleTest->getDatum("fk_entity"), $params);
$sampleTest->getItem("fk_entity")->setGuiEvents('onchange="changedTestEntity();"');
echo $sampleTest->getItem("fk_entity")->composeHtmlSelect($options, 4, 8);

echo $sampleTest->getItem("process_days_min")->composeHtmlInput(FItem::INPUT_NUMBER, 4, 4);
echo $sampleTest->getItem("process_days_max")->composeHtmlInput(FItem::INPUT_NUMBER, 4, 4);
echo $sampleTest->getItem("cost")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------

echo '</div>';  // right panel
//------------------------------------------------------------------------------

echo '</div>';  // row
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
echo '<button type="submit" class="btn btn-sm btn-primary">Guardar</button>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_recept_sample_tests.php?id=' . $sample->getId() . '" class="btn btn-sm btn-danger" role="button">Cancelar</a>';

echo '</form>';
echo '</div>';  // echo '<div class="container" style="margin-top:50px">';

echo FApp::composeFooter();
echo <<<SCRIPT
<script>
(function() {
    changedTest(true);
})();

function loadTest(loading) {
    if (document.getElementById("fk_test").value == 0) {
        document.getElementById("test_entity").innerHTML = "ND";
        document.getElementById("test_process_days_min").innerHTML = "ND";
        document.getElementById("test_process_days_max").innerHTML = "ND";
        document.getElementById("test_cost").innerHTML = "ND";
        document.getElementById("test_process_area").innerHTML = "ND";

        document.getElementById("fk_entity").value = 0;
        document.getElementById("process_days_min").value = 0;
        document.getElementById("process_days_max").value = 0;
        document.getElementById("cost").value = 0;
    }
    else {
        var request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var json = this.responseText;
                console.log("json: " + json);
                var test = JSON.parse(json); // load in memory test's data

                document.getElementById("test_entity").innerHTML = test["entity"];
                document.getElementById("test_process_days_min").innerHTML = test["process_days_min"];
                document.getElementById("test_process_days_max").innerHTML = test["process_days_max"];
                document.getElementById("test_cost").innerHTML = test["cost"];
                document.getElementById("test_process_area").innerHTML = test["process_area"];

                if (!loading) {
                    document.getElementById("fk_entity").value = test["fk_entity"] == 1 ? 0 : test["fk_entity"];
                    document.getElementById("process_days_min").value = test["process_days_min"];
                    document.getElementById("process_days_max").value = test["process_days_max"];
                    document.getElementById("cost").value = test["cost"];
                }
            }
        };
        var url = "ajax_get_test_entity.php?test=" + document.getElementById("fk_test").value;
        request.open("GET", url);
        request.send();
    }
}

function loadTestEntity() {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var json = this.responseText;
            console.log("json: " + json);
            var test = JSON.parse(json); // load in memory test's data

            document.getElementById("process_days_min").value = test["process_days_min"];
            document.getElementById("process_days_max").value = test["process_days_max"];
            document.getElementById("cost").value = test["cost"];
        }
    };
    var url = "ajax_get_test_entity.php?test=" + document.getElementById("fk_test").value + "&entity=" + (document.getElementById("fk_entity").value == 0 ? 1 : document.getElementById("fk_entity").value);
    request.open("GET", url);
    request.send();
}

function changedTest(loading) {
    if (document.getElementById("fk_test").value == 0) {
        document.getElementById("fk_entity").setAttribute("disabled", "");
    }
    else {
        document.getElementById("fk_entity").removeAttribute("disabled");
    }
    loadTest(loading);
}

function changedTestEntity() {
    loadTestEntity();
}

function validateForm() {
    return checkBeforeSubmit(); // prevent multiple form submitions
}
</script>
SCRIPT;
echo '</body>';
echo '</html>';
