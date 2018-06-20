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
use app\models\operations\CatTest;

$userSession = FGuiUtils::createUserSession();
$connection = FGuiUtils::createConnection();
$registry = new CatTest($connection);
$errmsg = "";

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (!empty($_GET["id"])) {
            $registry->read($userSession, $_GET["id"], FRegistry::MODE_WRITE);
        }
        break;

    case "POST":
        $data = array();

        if (!empty($_POST["id"])) {
            $data["id"] = intval($_POST["id"]);
        }
        if (!empty($_POST["name"])) {
            $data["name"] = $_POST["name"];
        }
        if (!empty($_POST["code"])) {
            $data["code"] = $_POST["code"];
        }
        if (!empty($_POST["sample_quantity"])) {
            $data["sample_quantity"] = $_POST["sample_quantity"];
        }
        if (!empty($_POST["sample_directs"])) {
            $data["sample_directs"] = $_POST["sample_directs"];
        }
        if (!empty($_POST["fk_process_area"])) {
            $data["fk_process_area"] = intval($_POST["fk_process_area"]);
        }
        if (!empty($_POST["fk_sample_category"])) {
            $data["fk_sample_category"] = intval($_POST["fk_sample_category"]);
        }
        if (!empty($_POST["fk_testing_method"])) {
            $data["fk_testing_method"] = intval($_POST["fk_testing_method"]);
        }
        if (!empty($_POST["fk_test_acredit_attrib"])) {
            $data["fk_test_acredit_attrib"] = intval($_POST["fk_test_acredit_attrib"]);
        }

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

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("catalogs");

echo '<div class="container" style="margin-top:50px">';
echo '<h3>Ensayo</h3>';

if (!empty($errmsg)) {
    echo '<div class="alert alert-danger alert-dismissible">';
    echo '<a href="#" class="close" data-dismiss="alert" aria-label="cerrar">&times;</a>';
    echo '<strong>¡Error de captura!</strong> ' . $errmsg;
    echo '</div>';
}

echo '<form method="post" action="' . FUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '">';

echo '<div class="form_group">';
echo '<label for="fk_process_area">Área proceso: *</label>';
echo '<select class="form-control" name="fk_process_area">';
foreach(AppUtils::getSelectOptions($connection, AppConsts::OC_PROCESS_AREA) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="code">Código: *</label>';
echo '<input type="text" class="form-control" name="code" value="' . $registry->getDatum("code") . '">';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="name">Nombre: *</label>';
echo '<input type="text" class="form-control" name="name" value="' . $registry->getDatum("name") . '">';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="fk_sample_category">Categoría muestra: *</label>';
echo '<select class="form-control" name="fk_sample_category">';
foreach(AppUtils::getSelectOptions($connection, AppConsts::OC_SAMPLE_CATEGORY, $registry->getDatum("fk_sample_category")) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="fk_testing_method">Método analítico: *</label>';
echo '<select class="form-control" name="fk_testing_method">';
foreach(AppUtils::getSelectOptions($connection, AppConsts::OC_TESTING_METHOD, $registry->getDatum("fk_testing_method")) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="fk_test_acredit_attrib">Acreditado/autorizado: *</label>';
echo '<select class="form-control" name="fk_test_acredit_attrib">';
foreach(AppUtils::getSelectOptions($connection, AppConsts::OC_TEST_ACREDIT_ATTRIB, $registry->getDatum("fk_test_acredit_attrib")) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="sample_quantity">Cantidad muestra mínima: *</label>';
echo '<input type="text" class="form-control" name="sample_quantity" value="' . $registry->getDatum("sample_quantity") . '">';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="sample_directs">Indicaciones muestra: *</label>';
echo '<textarea class="form-control" name="sample_directs" rows="3">' . $registry->getDatum("sample_directs") . '</textarea>';
echo '</div>';

if (!$registry->isRegistryNew()) {
    echo '<div class="form_group">';
    echo '<label for="id">ID:</label>';
    echo '<input type="text" class="form-control" name="id" value="' . $registry->getRegistryId() . '" readonly>';
    echo '</div>';
}

echo '<br><button type="submit" class="btn btn-primary">Guardar</button>';

echo '</form>';
echo '</div>';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
