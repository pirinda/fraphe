<?php
//------------------------------------------------------------------------------
// start session:
if (!isset($_SESSION)) {
    session_start();
}
// bootstrap Fraphe:
require $_SESSION["rootDir"] . "Fraphe" . DIRECTORY_SEPARATOR . "fraphe.php";
//------------------------------------------------------------------------------

use app\models\ModConsts;
use Fraphe\App\FApp;
use Fraphe\App\FAppConsts;
use Fraphe\App\FAppNavbar;
use Fraphe\App\FGuiUtils;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("process");

$job_st = intval(FApp::getVariable("job_st")); // URL status
$_SESSION["job_st"] = $job_st;

$stName = "";
switch ($job_st) {
    case ModConsts::OC_JOB_STATUS_PENDING:
        $stName = " pendientes";
        break;
    case ModConsts::OC_JOB_STATUS_PROCESSING:
        $stName = " en proceso";
        break;
    case ModConsts::OC_JOB_STATUS_FINISHED:
        $stName = " terminadas";
        break;
    case ModConsts::OC_JOB_STATUS_CANCELLED:
        $stName = " canceladas";
        break;
    default:
        $stName = " (todas)";
}

echo '<div class="container" style="margin-top:50px">';
echo '<h3>Órdenes de trabajo' . $stName . '</h3>';
//echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept.php" class="btn btn-primary btn-sm" role="button">Crear</a>';

$sql = <<<SQL
SELECT j.job_num, j.id_job, j.job_date, j.process_days, j.process_start_date, j.process_deadline,
j.ts_user_ins AS c_ts_user_ins, j.ts_user_upd AS c_ts_user_upd,
cb.code AS _cb_code,
pa.code AS _pa_code,
js.code AS _js_code,
ui.name AS ui_name, uu.name AS uu_name,
(SELECT COUNT(*) FROM o_job_test AS jt WHERE jt.fk_job = j.id_job) AS _tests
FROM o_job AS j
INNER JOIN cc_company_branch AS cb ON j.fk_company_branch = cb.id_company_branch
INNER JOIN oc_process_area AS pa ON j.fk_process_area = pa.id_process_area
INNER JOIN oc_job_status AS js ON j.fk_job_status = js.id_job_status
INNER JOIN cc_user AS ui ON j.fk_user_ins = ui.id_user
INNER JOIN cc_user AS uu ON j.fk_user_upd = uu.id_user
WHERE NOT j.is_deleted
SQL;

// add filter status, if any:
if (!empty($job_st)) {
    $sql .= " AND j.fk_job_status = $job_st";
}

$sql .= " ORDER BY j.job_num, pa.sorting, j.id_job;";

echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Folio</th>';
echo '<th><abbr title="Área proceso">AP</abbr></th>';
echo '<th></th>';
echo '<th>Fecha</th>';
echo '<th>Estatus</th>';
echo '<th><abbr title="Días proceso">DP</abbr></th>';
echo '<th>Inicio proceso</th>';
echo '<th>Límite proceso</th>';
echo '<th><abbr title="Sucursal">Suc.</abbr></th>';
echo '<th><abbr title="Cantidad ensayos">C/E</abbr></th>';
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
    echo '<td><nobr><b>' . $row['job_num'] . '</b></nobr></td>';
    echo '<td>' . $row['_pa_code'] . '</td>';
    echo '<td><nobr>';
    // button back:
    echo '<button type="button" class="btn btn-default btn-xs" ' .
        'onclick="procRecept(\'' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/proc_job.php?id=' . $row['id_job'] . '&move=back\', \'back\');"' .
        ($job_st <= ModConsts::OC_JOB_STATUS_PENDING || $job_st == 0 ? " disabled" : "") . '>' .
        '<span class="glyphicon glyphicon-circle-arrow-left"></span></button>&nbsp;';
    // button next:
    echo '<button type="button" class="btn btn-default btn-xs" ' .
        'onclick="procRecept(\'' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/proc_job.php?id=' . $row['id_job'] . '&move=next\', \'next\');"' .
        ($job_st >= ModConsts::OC_JOB_STATUS_FINISHED || $job_st == 0 ? " disabled" : "") . '>' .
        '<span class="glyphicon glyphicon-circle-arrow-right"></span></button>';
    echo '</nobr></td>';
    echo '<td>' . $row['job_date'] . '</td>';
    echo '<td>' . $row['_js_code'] . '</td>';
    echo '<td>' . $row['process_days'] . '</td>';
    echo '<td>' . $row['process_start_date'] . '</td>';
    echo '<td>' . $row['process_deadline'] . '</td>';
    echo '<td>' . $row['_cb_code'] . '</td>';
    echo '<td><span class="badge">' . $row['_tests'] . '</span></td>';
    echo '<td class="small">' . $row['ui_name'] . '</td>';
    echo '<td class="small">' . $row['c_ts_user_ins'] . '</td>';
    echo '<td class="small">' . $row['uu_name'] . '</td>';
    echo '<td class="small">' . $row['c_ts_user_upd'] . '</td>';
    echo '<td><nobr>';
    echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_job_tests.php?id=' . $row['id_job'] . '" class="btn btn-warning btn-xs" role="button"><span class="glyphicon glyphicon-inbox"></span></a>&nbsp;';
    echo '<a href="#" class="btn btn-danger btn-xs" role="button"><span class="glyphicon glyphicon-ban-circle"></span></a>';
    echo '</nobr></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

echo '</div>';

echo FApp::composeFooter();
echo <<<SCRIPT
<script>
function procRecept(url, move) {
    if (confirm("¿Enviar al estatus " + (move == "next" ? "siguiente" : "previo") + "?")) {
        window.location.assign(url);
    }
}
function validateForm() {
    return checkBeforeSubmit(); // prevent multiple form submitions
}
</script>
SCRIPT;
echo '</body>';
echo '</html>';
