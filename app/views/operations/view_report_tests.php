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
use Fraphe\Lib\FLibUtils;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;
use app\models\catalogs\ModEntity;
use app\models\operations\ModReport;
use app\models\operations\ModSample;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("report");

$userSession = FGuiUtils::createUserSession();
$report = new ModReport();
$report->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
$sample = new ModSample();
$sample->read($userSession, $report->getDatum("fk_sample"), FRegistry::MODE_READ);
$customer = new ModEntity();
$customer->read($userSession, $report->getDatum("fk_customer"), FRegistry::MODE_READ);

//------------------------------------------------------------------------------
echo '<div class="container" style="margin-top:50px">';
echo '<h3><span class="glyphicon glyphicon-file"></span> Informe de resultados</h3>';

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
echo '<div class="col-sm-3"><span class="bg-info">' . FLibUtils::formatStdDatetime($sample->getDatum("recept_datetime_n")) . '</span></div>';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_name")->getName() . ':</b></div>';
echo '<div class="col-sm-3">' . $sample->getDatum("sample_name") . '</div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_quantity")->getName() . ':</b></div>';
echo '<div class="col-sm-2">' . $sample->getDatum("sample_quantity") . ' ' . AppUtils::readField($userSession, "code", AppConsts::OC_CONTAINER_UNIT, $sample->getDatum("fk_container_unit")) . '</div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_lot")->getName() . ':</b></div>';
echo '<div class="col-sm-1">' . $sample->getDatum("sample_lot") . '</div>';
echo '</div>';

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------
echo '</div>';
//------------------------------------------------------------------------------

echo '<h4>Ensayos del informe de resultados</h4>';

echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_report.php" class="btn btn-danger btn-sm" role="button">Volver</a>';

$sql = <<<SQL
SELECT rt.report_test, rt.id_report_test, rt.result,
rt.ts_user_ins AS _ts_user_ins, rt.ts_user_upd AS _ts_user_upd,
t.name AS _t_name,
ru.code AS _ru_code,
ui.name AS _ui_name, uu.name AS _uu_name
FROM o_report_test AS rt
INNER JOIN oc_test AS t ON rt.fk_test = t.id_test
INNER JOIN cc_user AS ui ON rt.fk_user_ins = ui.id_user
INNER JOIN cc_user AS uu ON rt.fk_user_upd = uu.id_user
LEFT OUTER JOIN oc_result_unit AS ru ON rt.nk_result_unit = ru.id_result_unit
SQL;
$sql .= " ";
$sql .= "WHERE NOT rt.is_deleted AND rt.fk_report = " . $_GET[FRegistry::ID] . " ";
$sql .= "ORDER BY rt.report_test, rt.id_report_test;";

echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th><abbr title="Número">Núm.</abbr></th>';
echo '<th>Ensayo</th>';
echo '<th>Resultado</th>';
echo '<th><abbr title="Unidad resultado">UR</abbr></th>';
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
    echo '<td>' . $row['report_test'] . '</td>';
    echo '<td>' . $row['_t_name'] . '</td>';
    echo '<td>' . $row['result'] . '</td>';
    echo '<td>' . $row['_ru_code'] . '</td>';
    echo '<td class="small">' . $row['_ui_name'] . '</td>';
    echo '<td class="small">' . $row['_ts_user_ins'] . '</td>';
    echo '<td class="small">' . $row['_uu_name'] . '</td>';
    echo '<td class="small">' . $row['_ts_user_upd'] . '</td>';
    echo '<td><nobr>';
    echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_report_test.php?id=' . $row['id_report_test'] . '" class="btn btn-success btn-xs" role="button"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;';
    echo '<a href="#" class="btn btn-danger btn-xs" role="button"><span class="glyphicon glyphicon-ban-circle"></span></a>';
    echo '</nobr></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

echo '</div>';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
