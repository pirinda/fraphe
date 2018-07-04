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
use app\models\ModUtils;
use app\models\catalogs\ModEntity;
use app\models\catalogs\ModEntityEntityType;
use app\models\catalogs\ModEntityAddress;
use app\models\catalogs\ModContact;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("catalogs");

$userSession = FGuiUtils::createUserSession();
$connection = FGuiUtils::createConnection();
$registry = new ModEntity($connection);
$errmsg = "";
$class = 1;
$nature = 1;

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (!empty($_GET["id"])) {
            $registry->read($userSession, $_GET["id"], FRegistry::MODE_WRITE);
        }
        if (!empty($_GET["class"])) {
            $class = intval($_GET["class"]);
        }
        if (!empty($_GET["nature"])) {
            $nature = intval($_GET["nature"]);
        }
        break;

    case "POST":
        $data = array();
        $data["name"] = $_POST["name"];
        $data["code"] = $_POST["code"];
        $data["sample_quantity"] = $_POST["sample_quantity"];
        $data["sample_directs"] = $_POST["sample_directs"];
        $data["fk_process_area"] = intval($_POST["fk_process_area"]);
        $data["fk_sample_category"] = intval($_POST["fk_sample_category"]);
        $data["fk_testing_method"] = intval($_POST["fk_testing_method"]);
        $data["fk_test_acredit_attrib"] = intval($_POST["fk_test_acredit_attrib"]);

        $childData = array();
        $childData["id_entity"] = 1;    // current company
        $childData["process_min"] = intval($_POST["po_process_min"]);
        $childData["process_max"] = intval($_POST["po_process_max"]);
        $childData["cost"] = floatval($_POST["po_cost"]);
        $childData["is_default"] = boolval($_POST["po_is_default"]);

        if (!empty($_POST["id"])) {
            $data["id"] = intval($_POST["id"]);
            $childData["id_test"] = $data["id"];
        }

        $childProcessOpt = new ModTestProcessOpt($connection);
        $childProcessOpt->setData($childData);
        $registry->addChildProcessOpt($childProcessOpt);

        try {
            $registry->setData($data);
            $registry->validate();
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
echo '<h3>' . ModUtils::getEntityClassSing($class) . '</h3>';

if (!empty($errmsg)) {
    echo '<div class="alert alert-danger alert-dismissible">';
    echo '<a href="#" class="close" data-dismiss="alert" aria-label="cerrar">&times;</a>';
    echo '<strong>¡Error de captura!</strong> ' . $errmsg;
    echo '</div>';
}

// test:

echo '<form method="post" action="' . FUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '">';

echo '<div class="form_group">';
echo '<label for="code">' . $registry->getItem("code")->getName() . ': *</label>';
echo '<input type="text" class="form-control" name="code" value="' . $registry->getDatum("code") . '">';
echo '</div>';

if ($nature == ModUtils::ENTITY_NATURE_PER) {
    echo '<div class="form_group">';
    echo '<label for="surname">' . $registry->getItem("surname")->getName() . ': *</label>';
    echo '<input type="text" class="form-control" name="surname" value="' . $registry->getDatum("surname") . '">';
    echo '</div>';

    echo '<div class="form_group">';
    echo '<label for="forename">' . $registry->getItem("forename")->getName() . ': *</label>';
    echo '<input type="text" class="form-control" name="forename" value="' . $registry->getDatum("forename") . '">';
    echo '</div>';

    echo '<div class="form_group">';
    echo '<label for="prefix">' . $registry->getItem("prefix")->getName() . ': *</label>';
    echo '<input type="text" class="form-control" name="prefix" value="' . $registry->getDatum("prefix") . '">';
    echo '</div>';
}
else {
    echo '<div class="form_group">';
    echo '<label for="name">' . $registry->getItem("name")->getName() . ': *</label>';
    echo '<input type="text" class="form-control" name="name" value="' . $registry->getDatum("name") . '">';
    echo '</div>';
}

echo '<div class="form_group">';
echo '<label for="alias">' . $registry->getItem("alias")->getName() . ': *</label>';
echo '<input type="text" class="form-control" name="alias" value="' . $registry->getDatum("alias") . '">';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="fiscal_id">' . $registry->getItem("fiscal_id")->getName() . ': *</label>';
echo '<input type="text" class="form-control" name="fiscal_id" value="' . $registry->getDatum("fiscal_id") . '">';
echo '</div>';

echo '<div class="checkbox">';
echo '<label><input type="checkbox" name="is_credit" value="1"' . ($registry->getDatum("is_credit") ? " checked" : "") . '>' . $registry->getItem("is_credit")->getName() . '</label>';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="credit_days">' . $registry->getItem("credit_days")->getName() . ': *</label>';
echo '<input type="text" class="form-control" name="credit_days" value="' . $registry->getDatum("credit_days") . '">';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="notes">' . $registry->getItem("notes")->getName() . ': *</label>';
echo '<textarea class="form-control" name="notes" rows="3">' . $registry->getDatum("notes") . '</textarea>';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="nk_market_segment">' . $registry->getItem("nk_market_segment")->getName() . ': *</label>';
echo '<select class="form-control" name="nk_market_segment">';
foreach (AppUtils::getSelectOptions($connection, AppConsts::CC_MARKET_SEGMENT) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="nk_report_delivery_opt">' . $registry->getItem("nk_report_delivery_opt")->getName() . ': *</label>';
echo '<select class="form-control" name="nk_report_delivery_opt">';
foreach (AppUtils::getSelectOptions($connection, AppConsts::OC_REPORT_DELIVERY_OPT) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

if (!$registry->isRegistryNew()) {
    echo '<div class="form_group collapse">';
    echo '<label for="id">' . $registry->getItem("id_entity")->getName() . ':</label>';
    echo '<input type="text" class="form-control" name="id" value="' . $registry->getRegistryId() . '" readonly>';
    echo '</div>';
}
/*
// child test process options:

$childProcessOpt;

if (empty($registry->getChildProcessOpts())) {
    $childProcessOpt = new ModTestProcessOpt($connection);
    $data = array();
    $data["is_default"] = true;
    $childProcessOpt->setData($data);
}
else {
    $childProcessOpt = $registry->getChildProcessOpts()[0];
}

echo '<div class="form_group">';
echo '<label for="po_process_max">' . $childProcessOpt->getItem("process_max")->getName() . ': *</label>';
echo '<input type="text" class="form-control" name="po_process_max" value="' . $childProcessOpt->getDatum("process_max") . '">';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="po_cost">' . $childProcessOpt->getItem("cost")->getName() . ':</label>';
echo '<input type="text" class="form-control" name="po_cost" value="' . $childProcessOpt->getDatum("cost") . '">';
echo '</div>';

echo '<div class="checkbox">';
echo '<label><input type="checkbox" name="po_is_default" value="1"' . ($childProcessOpt->getDatum("is_default") ? " checked" : "") . '>' . $childProcessOpt->getItem("is_default")->getName() . '</label>';
echo '</div>';
*/

echo '<br><button type="submit" class="btn btn-primary">Guardar</button>';
echo '&nbsp;<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/catalogs/view_entity.php?class=' . $class . '" class="btn btn-danger" role="button">Cancelar</a>';

echo '</form>';
echo '</div>';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
