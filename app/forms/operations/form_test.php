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
use app\AppConsts;
use app\AppUtils;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("catalogs");

echo '<div class="container" style="margin-top:50px">';
echo '<h3>Ensayo</h3>';
echo '<form action="action_page.php">';

$conn = FGuiUtils::createConnection();

echo '<div class="form_group">';
echo '<label for="process_area">Área proceso: *</label>';
echo '<select class="form-control" id="process_area">';
foreach(AppUtils::getSelectOptions($conn, AppConsts::OC_PROCESS_AREA) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="code">Código: *</label>';
echo '<input type="text" class="form-control" id="code">';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="name">Nombre: *</label>';
echo '<input type="text" class="form-control" id="name">';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="sample_class">Clase muestra: *</label>';
echo '<select class="form-control" id="sample_class">';
foreach(AppUtils::getSelectOptions($conn, AppConsts::OC_SAMPLE_CLASS) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="testing_method">Método analítico: *</label>';
echo '<select class="form-control" id="testing_method">';
foreach(AppUtils::getSelectOptions($conn, AppConsts::OC_TESTING_METHOD) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="test_acredit_attrib">Acreditado/autorizado: *</label>';
echo '<select class="form-control" id="test_acredit_attrib">';
foreach(AppUtils::getSelectOptions($conn, AppConsts::OC_TEST_ACREDIT_ATTRIB) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="sample_quantity">Cantidad muestra mínima: *</label>';
echo '<input type="text" class="form-control" id="sample_quantity">';
echo '</div>';

echo '<div class="form_group">';
echo '<label for="sample_directs">Indicaciones muestra:</label>';
echo '<textarea class="form-control" id="sample_directs" rows="5"></textarea>';
echo '</div>';

echo '<button type="submit" class="btn btn-primary">Guardar</button>';

echo '</form>';
echo '</div>';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
