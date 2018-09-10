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
use app\models\operations\ModJob;
use app\models\operations\ModSample;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("process");

$userSession = FGuiUtils::createUserSession();
$job = new ModJob();
$job->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
$sample = new ModSample();
$sample->read($userSession, $job->getDatum("fk_sample"), FRegistry::MODE_READ);

//------------------------------------------------------------------------------
echo '<div class="container" style="margin-top:50px">';
echo '<h3><span class="glyphicon glyphicon-inbox"></span> Orden de trabajo</h3>';

//------------------------------------------------------------------------------
echo '<div class="panel-group">';
//------------------------------------------------------------------------------
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos de la orden de trabajo</div>';
echo '<div class="panel-body small">';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $job->getItem("job_num")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><span class="bg-info lead">' . $job->getDatum("job_num") . '</span></div>';
echo '<div class="col-sm-2"><b>' . $job->getItem("job_date")->getName() . ':</b></div>';
echo '<div class="col-sm-2"><span class="bg-info">' . FLibUtils::formatStdDate($job->getDatum("job_date")) . '</span></div>';
echo '<div class="col-sm-2"><b>' . $job->getItem("fk_process_area")->getName() . ':</b></div>';
echo '<div class="col-sm-1"><span class="bg-info">' . AppUtils::readField($userSession, "code", AppConsts::OC_PROCESS_AREA, $job->getDatum("fk_process_area")) . '</span></div>';
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

echo '<h4>Ensayos de la orden de trabajo</h4>';

echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_job.php" class="btn btn-danger btn-sm" role="button">Volver</a>';

$sql = <<<SQL
SELECT jt.job_test, jt.id_job_test, jt.process_days, jt.process_start_date, jt.process_deadline,
jt.ts_user_ins AS _ts_user_ins, jt.ts_user_upd AS _ts_user_upd,
t.name AS _t_name,
IF(e.alias <> '', e.alias, e.name) AS _entity,
js.code AS _js_code,
ui.name AS _ui_name, uu.name AS _uu_name
FROM o_job_test AS jt
INNER JOIN oc_test AS t ON jt.fk_test = t.id_test
INNER JOIN cc_entity AS e ON jt.fk_entity = e.id_entity
INNER JOIN oc_job_status AS js ON jt.fk_job_test_status = js.id_job_status
INNER JOIN cc_user AS ui ON jt.fk_user_ins = ui.id_user
INNER JOIN cc_user AS uu ON jt.fk_user_upd = uu.id_user
SQL;
$sql .= " ";
$sql .= "WHERE NOT jt.is_deleted AND jt.fk_job = " . $_GET[FRegistry::ID] . " ";
$sql .= "ORDER BY jt.job_test, jt.id_job_test;";

echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th><abbr title="Número">Núm.</abbr></th>';
echo '<th>Ensayo</th>';
echo '<th>Entidad</th>';
echo '<th>Estatus</th>';
echo '<th><abbr title="Días proceso">DP</abbr></th>';
echo '<th>Inicio proceso</th>';
echo '<th>Límite proceso</th>';
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
    echo '<td>' . $row['job_test'] . '</td>';
    echo '<td>' . $row['_t_name'] . '</td>';
    echo '<td>' . $row['_entity'] . '</td>';
    echo '<td>' . $row['_js_code'] . '</td>';
    echo '<td>' . $row['process_days'] . '</td>';
    echo '<td>' . $row['process_start_date'] . '</td>';
    echo '<td>' . $row['process_deadline'] . '</td>';
    echo '<td class="small">' . $row['_ui_name'] . '</td>';
    echo '<td class="small">' . $row['_ts_user_ins'] . '</td>';
    echo '<td class="small">' . $row['_uu_name'] . '</td>';
    echo '<td class="small">' . $row['_ts_user_upd'] . '</td>';
    echo '<td><nobr>';
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
