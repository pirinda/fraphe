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
use app\models\operations\ModTest;
use app\models\operations\ModTestProcessEntity;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("catalogs");

$userSession = FGuiUtils::createUserSession();
$test = new ModTest();
$errmsg = "";

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (!empty($_GET[FRegistry::ID])) {
            $test->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_WRITE);
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

        switch ($_POST[ModTestProcessEntity::PREFIX]) {
            case "company":
                $childData["id_entity"] = 1;    // this company itself
                break;
            case "provider":
                $childData["id_entity"] = intval($_POST[ModTestProcessEntity::PREFIX . "id_entity"]);
                break;
            default:
        }
        $childData["process_days_min"] = intval($_POST[ModTestProcessEntity::PREFIX . "process_days_min"]);
        $childData["process_days_max"] = intval($_POST[ModTestProcessEntity::PREFIX . "process_days_max"]);
        $childData["cost"] = floatval($_POST[ModTestProcessEntity::PREFIX . "cost"]);
        $childData["is_default"] = true;

        $defaultProcessEntity = new ModTestProcessEntity();
        $defaultProcessEntity->setData($childData);
        $test->setDefaultChildProcessEntity($defaultProcessEntity);

        try {
            $test->setData($data);
            $test->save($userSession);
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
echo '<h2>Ensayo</h2>';
echo '</div>';

if (!empty($errmsg)) {
    echo '<div class="alert alert-danger alert-dismissible">';
    echo '<a href="#" class="close" data-dismiss="alert" aria-label="cerrar">&times;</a>';
    echo '<strong>¡Error de captura!</strong> ' . $errmsg;
    echo '</div>';
}

// test:

echo '<form class="form-horizontal" method="post" action="' . FUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '" onsubmit="return validateForm()">';

// preserve entity class and nature and registry ID in post:
echo '<input type="hidden" name="' . FRegistry::ID . '" value="' . $test->getId() . '">';

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_PROCESS_AREA, $test->getDatum("fk_process_area"));
echo $test->getItem("fk_process_area")->composeHtmlSelect($options, 4, 8);

echo $test->getItem("code")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $test->getItem("name")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLE_CLASS, $test->getDatum("fk_sample_class"));
echo $test->getItem("fk_sample_class")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_TESTING_METHOD, $test->getDatum("fk_testing_method"));
echo $test->getItem("fk_testing_method")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_TEST_ACREDIT_ATTRIB, $test->getDatum("fk_test_acredit_attrib"));
echo $test->getItem("fk_test_acredit_attrib")->composeHtmlSelect($options, 4, 8);

echo $test->getItem("sample_quantity")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $test->getItem("sample_directs")->composeHtmlTextArea(4, 8, 1);

// child test process options:

$defaultProcessEntity;

if (empty($test->getDefaultChildProcessEntity())) {
    $defaultProcessEntity = new ModTestProcessEntity();
}
else {
    $defaultProcessEntity = $test->getDefaultChildProcessEntity();
}

echo '<div class="form-group">';
echo '<div class="col-sm-4">';
echo '<label class="control-label small" for="' . ModTestProcessEntity::PREFIX . '">' . $defaultProcessEntity->getItem("id_entity")->getName() . ': *</label>';
echo '</div>';
echo '<div class="col-sm-8">';
echo '<label class="radio-inline small"><input type="radio" name="' . ModTestProcessEntity::PREFIX . '" value="company"' . ($defaultProcessEntity->isRegistryNew() || $defaultProcessEntity->getDatum("id_entity") == 1 ? ' checked' : '') . ' onchange="changedEntity();">Empresa</label>';
echo '<label class="radio-inline small"><input type="radio" name="' . ModTestProcessEntity::PREFIX . '" value="provider"' . (!$defaultProcessEntity->isRegistryNew() && $defaultProcessEntity->getDatum("id_entity") != 1 ? ' checked' : '') . ' onchange="changedEntity();">Proveedor</label>';
echo '</div>';
echo '</div>';

$params = array();
$params["fk_entity_class"] = ModConsts::CC_ENTITY_CLASS_PROV;
$params["entity_type"] = ModConsts::CC_ENTITY_TYPE_PROV_LAB;
$options = AppUtils::getSelectOptions($userSession, AppConsts::CC_ENTITY, $defaultProcessEntity->getDatum("id_entity"), $params);
echo $defaultProcessEntity->getItem("id_entity")->composeHtmlSelect($options, 4, 8, ModTestProcessEntity::PREFIX);

echo $defaultProcessEntity->getItem("process_days_min")->composeHtmlInput(FItem::INPUT_NUMBER, 4, 4, ModTestProcessEntity::PREFIX);
echo $defaultProcessEntity->getItem("process_days_max")->composeHtmlInput(FItem::INPUT_NUMBER, 4, 4, ModTestProcessEntity::PREFIX);
echo $defaultProcessEntity->getItem("cost")->composeHtmlInput(FItem::INPUT_NUMBER, 4, 4, ModTestProcessEntity::PREFIX);

echo '<br><button type="submit" class="btn btn-sm btn-primary">Guardar</button>';
echo '&nbsp;<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_test.php" class="btn btn-sm btn-danger" role="button">Cancelar</a>';

echo '</form>';
echo '</div>';

echo FApp::composeFooter();
echo <<<SCRIPT
<script>
(function() {
    changedEntity();
})();
function changedEntity() {
    switch (document.forms[0].elements["process_entity_"].value) {
        case "company":
            document.getElementById("process_entity_id_entity").setAttribute("disabled", "");
            document.getElementById("process_entity_id_entity").value = 0;
            break;
        case "provider":
            document.getElementById("process_entity_id_entity").removeAttribute("disabled");
            break;
        default:
    }
}
function validateForm() {
    return checkBeforeSubmit(); // prevent multiple form submitions
}
</script>
SCRIPT;
echo '</body>';
echo '</html>';
