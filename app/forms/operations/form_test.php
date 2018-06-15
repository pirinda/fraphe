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

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (!empty($_GET["id"])) {
            $registry.read($userSession, $_GET["id"], FRegistry::MODE_WRITE);
        }
        break;

    case "POST":
        $data = array();

        if (!empty($_POST["name"])) {
            $data["name"] = $_POST["name"];
        }
        if (!empty($_POST["code"])) {
            $data["code"] = $_POST["code"];
        }

        var_dump($data);
        $registry->setData($data);
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
echo '<form method="post" action="' . FUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '">';


echo '<div class="form_group">';
echo '<label for="process_area">Área proceso: *</label>';
echo '<select class="form-control" name="process_area">';
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
echo '<label for="sample_class">Clase muestra: *</label>';
echo '<select class="form-control" name="sample_class">';
foreach(AppUtils::getSelectOptions($connection, AppConsts::OC_SAMPLE_CLASS) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="testing_method">Método analítico: *</label>';
echo '<select class="form-control" name="testing_method">';
foreach(AppUtils::getSelectOptions($connection, AppConsts::OC_TESTING_METHOD) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="test_acredit_attrib">Acreditado/autorizado: *</label>';
echo '<select class="form-control" name="test_acredit_attrib">';
foreach(AppUtils::getSelectOptions($connection, AppConsts::OC_TEST_ACREDIT_ATTRIB) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="sample_quantity">Cantidad muestra mínima: *</label>';
echo '<input type="text" class="form-control" name="sample_quantity">';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="sample_directs">Indicaciones muestra:</label>';
echo '<textarea class="form-control" name="sample_directs" rows="5"></textarea>';
echo '</div>';

echo '<button type="submit" class="btn btn-primary">Guardar</button>';

echo '</form>';
echo '</div>';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
