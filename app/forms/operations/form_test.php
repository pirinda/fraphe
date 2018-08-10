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
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;
use app\models\operations\ModTest;
use app\models\operations\ModTestProcessOpt;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("catalogs");

$userSession = FGuiUtils::createUserSession();
$registry = new ModTest();
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
        $registry->addChildProcessOpt($childProcessOpt);

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
echo '<h3>Ensayo</h3>';

if (!empty($errmsg)) {
    echo '<div class="alert alert-danger alert-dismissible">';
    echo '<a href="#" class="close" data-dismiss="alert" aria-label="cerrar">&times;</a>';
    echo '<strong>¡Error de captura!</strong> ' . $errmsg;
    echo '</div>';
}

// test:

echo '<form method="post" action="' . FUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '">';

echo '<div class="form-group">';
echo '<label for="fk_process_area">' . $registry->getItem("fk_process_area")->getName() . ': *</label>';
echo '<select class="form-control" name="fk_process_area">';
foreach (AppUtils::getSelectOptions($userSession, AppConsts::OC_PROCESS_AREA, $registry->getDatum("fk_process_area")) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="code">' . $registry->getItem("code")->getName() . ': *</label>';
echo '<input type="text" class="form-control" name="code" value="' . $registry->getDatum("code") . '">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="name">' . $registry->getItem("name")->getName() . ': *</label>';
echo '<input type="text" class="form-control" name="name" value="' . $registry->getDatum("name") . '">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="fk_sample_class">' . $registry->getItem("fk_sample_class")->getName() . ': *</label>';
echo '<select class="form-control" name="fk_sample_class">';
foreach (AppUtils::getSelectOptions($userSession, AppConsts::OC_SAMPLE_CLASS, $registry->getDatum("fk_sample_class")) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="fk_testing_method">' . $registry->getItem("fk_testing_method")->getName() . ': *</label>';
echo '<select class="form-control" name="fk_testing_method">';
foreach (AppUtils::getSelectOptions($userSession, AppConsts::OC_TESTING_METHOD, $registry->getDatum("fk_testing_method")) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="fk_test_acredit_attrib">' . $registry->getItem("fk_test_acredit_attrib")->getName() . ': *</label>';
echo '<select class="form-control" name="fk_test_acredit_attrib">';
foreach (AppUtils::getSelectOptions($userSession, AppConsts::OC_TEST_ACREDIT_ATTRIB, $registry->getDatum("fk_test_acredit_attrib")) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="sample_quantity">' . $registry->getItem("sample_quantity")->getName() . ': *</label>';
echo '<input type="text" class="form-control" name="sample_quantity" value="' . $registry->getDatum("sample_quantity") . '">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="sample_directs">' . $registry->getItem("sample_directs")->getName() . ': *</label>';
echo '<textarea class="form-control" name="sample_directs" rows="3">' . $registry->getDatum("sample_directs") . '</textarea>';
echo '</div>';

// child test process options:

$childProcessOpt;

if (empty($registry->getChildProcessOpts())) {
    $childProcessOpt = new ModTestProcessOpt();
    $data = array();
    $data["is_default"] = true;
    $childProcessOpt->setData($data);
}
else {
    $childProcessOpt = $registry->getChildProcessOpts()[0];
}

echo '<div class="form-group">';
echo '<label for="po_process_days_min">' . $childProcessOpt->getItem("process_days_min")->getName() . ': *</label>';
echo '<input type="text" class="form-control" name="po_process_days_min" value="' . $childProcessOpt->getDatum("process_days_min") . '">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="po_process_days_max">' . $childProcessOpt->getItem("process_days_max")->getName() . ': *</label>';
echo '<input type="text" class="form-control" name="po_process_days_max" value="' . $childProcessOpt->getDatum("process_days_max") . '">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="po_cost">' . $childProcessOpt->getItem("cost")->getName() . ':</label>';
echo '<input type="text" class="form-control" name="po_cost" value="' . $childProcessOpt->getDatum("cost") . '">';
echo '</div>';

echo '<div class="checkbox">';
echo '<label><input type="checkbox" name="po_is_default" value="1"' . ($childProcessOpt->getDatum("is_default") ? " checked" : "") . '>' . $childProcessOpt->getItem("is_default")->getName() . '</label>';
echo '</div>';

// registry ID:
if (!$registry->isRegistryNew()) {
    echo '<div class="form-group">';
    echo '<label for="' . FRegistry::ID . '">' . $registry->getItem("id_test")->getName() . ':</label>';
    echo '<input type="text" class="form-control" name="' . FRegistry::ID . '" value="' . $registry->getId() . '" readonly>';
    echo '</div>';
}

echo '<br><button type="submit" class="btn btn-primary">Guardar</button>';
echo '&nbsp;<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_test.php" class="btn btn-danger" role="button">Cancelar</a>';

echo '</form>';
echo '</div>';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
