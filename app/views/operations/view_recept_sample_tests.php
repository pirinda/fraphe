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
use app\models\operations\ModSample;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("recept");

$userSession = FGuiUtils::createUserSession();
$sample = new ModSample();
$sample->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);

//------------------------------------------------------------------------------
echo '<div class="container" style="margin-top:50px">';
echo '<h3><span class="glyphicon glyphicon-th-list"></span> Muestra</h3>';

//------------------------------------------------------------------------------
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos de la muestra</div>';
echo '<div class="panel-body small">';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_num")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><span class="bg-info lead">' . $sample->getDatum("sample_num") . '</span></div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("recept_datetime_n")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><span class="bg-info">' . FLibUtils::formatStdDatetime($sample->getDatum("recept_datetime_n")) . '</span></div>';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_name")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><mark>' . $sample->getDatum("sample_name") . '</mark></div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_quantity")->getName() . ':</b></div>';
echo '<div class="col-sm-2">' . $sample->getDatum("sample_quantity") . ' ' . AppUtils::readField($userSession, "code", AppConsts::OC_CONTAINER_UNIT, $sample->getDatum("fk_container_unit")) . '</div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_lot")->getName() . ':</b></div>';
echo '<div class="col-sm-1">' . $sample->getDatum("sample_lot") . '</div>';
echo '</div>';

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------

echo '<h4>Ensayos de la muestra</h4>';

echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept_sample_test.php?sample=' . $sample->getId() . '" class="btn btn-primary btn-sm" role="button">Crear</a>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_recept_samples.php?id=' . $sample->getDatum("nk_recept") . '" class="btn btn-danger btn-sm" role="button">Volver</a>';

$sql = <<<SQL
SELECT st.sample_test, st.id_sample_test, st.process_days, st.process_start_date, st.process_deadline,
st.ts_user_ins AS _ts_user_ins, st.ts_user_upd AS _ts_user_upd,
t.name AS _t_name,
IF(e.alias <> '', e.alias, e.name) AS _entity,
pa.code AS _pa_code,
ui.name AS _ui_name, uu.name AS _uu_name
FROM o_sample_test AS st
INNER JOIN oc_test AS t ON st.fk_test = t.id_test
INNER JOIN cc_entity AS e ON st.fk_entity = e.id_entity
INNER JOIN oc_process_area AS pa ON st.fk_process_area = pa.id_process_area
INNER JOIN cc_user AS ui ON st.fk_user_ins = ui.id_user
INNER JOIN cc_user AS uu ON st.fk_user_upd = uu.id_user
SQL;
$sql .= " ";
$sql .= "WHERE NOT st.is_deleted AND st.fk_sample = " . $_GET[FRegistry::ID] . " ";
$sql .= "ORDER BY st.sample_test, st.id_sample_test;";

echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th><abbr title="Número">Núm.</abbr></th>';
echo '<th><abbr title="Área proceso">AP</abbr></th>';
echo '<th>Ensayo</th>';
echo '<th>Entidad</th>';
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
    echo '<td>' . $row['sample_test'] . '</td>';
    echo '<td>' . $row['_pa_code'] . '</td>';
    echo '<td>' . $row['_t_name'] . '</td>';
    echo '<td>' . $row['_entity'] . '</td>';
    echo '<td>' . $row['process_days'] . '</td>';
    echo '<td>' . $row['process_start_date'] . '</td>';
    echo '<td>' . $row['process_deadline'] . '</td>';
    echo '<td class="small">' . $row['_ui_name'] . '</td>';
    echo '<td class="small">' . $row['_ts_user_ins'] . '</td>';
    echo '<td class="small">' . $row['_uu_name'] . '</td>';
    echo '<td class="small">' . $row['_ts_user_upd'] . '</td>';
    echo '<td><nobr>';
    echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept_sample_test.php?id=' . $row['id_sample_test'] . '" class="btn btn-success btn-xs" role="button"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;';
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
