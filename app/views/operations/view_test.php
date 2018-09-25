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

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("catalogs");

echo '<div class="container" style="margin-top:50px">';
echo '<h3>Ensayos</h3>';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_test.php" class="btn btn-primary btn-sm" role="button">Crear</a>';

$sql = <<<SQL
SELECT c.name AS c_name, c.code AS c_code, c.id_test AS c_id,
c.ts_user_ins AS c_ts_user_ins, c.ts_user_upd AS c_ts_user_upd,
pa.code AS pa_code,
sc.name AS sc_name,
tm.name AS tm_name,
taa.code AS taa_code,
ui.name AS ui_name, uu.name AS uu_name
FROM oc_test AS c
INNER JOIN oc_process_area AS pa ON c.fk_process_area = pa.id_process_area
INNER JOIN oc_sample_class AS sc ON c.fk_sample_class = sc.id_sample_class
INNER JOIN oc_testing_method AS tm ON c.fk_testing_method = tm.id_testing_method
INNER JOIN oc_test_acredit_attrib AS taa ON c.fk_test_acredit_attrib = taa.id_test_acredit_attrib
INNER JOIN cc_user AS ui ON c.fk_user_ins = ui.id_user
INNER JOIN cc_user AS uu ON c.fk_user_upd = uu.id_user
WHERE NOT c.is_deleted
ORDER BY pa.sorting, pa.id_process_area, c.name, c.code, c.id_test;
SQL;

echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th><abbr title="Área proceso">AP</abbr></th>';
echo '<th>Nombre</th>';
echo '<th>Código</th>';
echo '<th>Clase muestra</th>';
echo '<th>Método analítico</th>';
echo '<th><abbr title="Acreditación/autorización">A/A</abbr></th>';
echo '<th class="small">Creador</th>';
echo '<th class="small">Creación</th>';
echo '<th class="small">Modificador</th>';
echo '<th class="small">Modificación</th>';
echo '<th></th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

$pdo = FGuiUtils::createPdo();
foreach ($pdo->query($sql) as $row) {
    echo '<tr>';
    echo '<td>' . $row['pa_code'] . '</td>';
    echo '<td>' . $row['c_name'] . '</td>';
    echo '<td>' . $row['c_code'] . '</td>';
    echo '<td>' . $row['sc_name'] . '</td>';
    echo '<td>' . $row['tm_name'] . '</td>';
    echo '<td>' . $row['taa_code'] . '</td>';
    echo '<td class="small">' . $row['ui_name'] . '</td>';
    echo '<td class="small">' . $row['c_ts_user_ins'] . '</td>';
    echo '<td class="small">' . $row['uu_name'] . '</td>';
    echo '<td class="small">' . $row['c_ts_user_upd'] . '</td>';
    echo '<td><a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_test.php?id=' . $row['c_id'] . '" class="btn btn-success btn-xs" role="button"><span class="glyphicon glyphicon-edit"></span></a></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

echo '</div>';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
