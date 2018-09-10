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
echo FAppNavbar::compose("report");

$report_st = intval(FApp::getVariable("report_st")); // URL or session parameter
$_SESSION["report_st"] = $report_st;

$stName = "";
switch ($report_st) {
    case ModConsts::OC_REPORT_STATUS_PENDING:
        $stName = "pendientes";
        break;
    case ModConsts::OC_REPORT_STATUS_PROCESSING:
        $stName = "en proceso";
        break;
    case ModConsts::OC_REPORT_STATUS_FINISHED:
        $stName = "terminados";
        break;
    case ModConsts::OC_REPORT_STATUS_VERIFIED:
        $stName = "verificados";
        break;
    case ModConsts::OC_REPORT_STATUS_VALIDATED:
        $stName = "validados";
        break;
    case ModConsts::OC_REPORT_STATUS_RELEASED:
        $stName = "liberados";
        break;
    case ModConsts::OC_REPORT_STATUS_DELIVERED:
        $stName = "entregados";
        break;
    case ModConsts::OC_REPORT_STATUS_CANCELLED:
        $stName = "cancelados";
        break;
    default:
        $stName = "(todas)";
}

echo '<div class="container" style="margin-top:50px">';
echo '<h3>Informes de resultados <span class="label label-default">' . $stName . '</span></h3>';
//echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept.php" class="btn btn-primary btn-sm" role="button">Crear</a>';

$sql = <<<SQL
SELECT r.report_num, r.id_report, r.report_date, r.reissue,
r.ts_user_ins AS c_ts_user_ins, r.ts_user_upd AS c_ts_user_upd,
cb.code AS _cb_code,
cus.name AS _cus_name,
cus.alias AS _cus_alias,
rdt.code AS _rdt_code,
rs.code AS _rs_code,
ui.name AS ui_name, uu.name AS uu_name,
(SELECT COUNT(*) FROM o_report_test AS rt WHERE rt.fk_report = r.id_report) AS _tests
FROM o_report AS r
INNER JOIN cc_company_branch AS cb ON r.fk_company_branch = cb.id_company_branch
INNER JOIN cc_entity AS cus ON r.fk_customer = cus.id_entity
INNER JOIN oc_report_status AS rs ON r.fk_report_status = rs.id_report_status
INNER JOIN oc_report_delivery_type AS rdt ON r.fk_report_delivery_type = rdt.id_report_delivery_type
INNER JOIN cc_user AS ui ON r.fk_user_ins = ui.id_user
INNER JOIN cc_user AS uu ON r.fk_user_upd = uu.id_user
WHERE NOT r.is_deleted
SQL;

// add filter status, if any:
if (!empty($report_st)) {
    $sql .= " AND r.fk_report_status = $report_st";
}

$sql .= " ORDER BY r.report_num, r.id_report;";

echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Folio</th>';
echo '<th></th>';
echo '<th>Fecha</th>';
echo '<th>Estatus</th>';
echo '<th>Cliente</th>';
echo '<th>Alias</th>';
echo '<th><abbr title="Número reimpresión">#R</abbr></th>';
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
    echo '<td><nobr><b>' . $row['report_num'] . '</b></nobr></td>';
    echo '<td><nobr>';
    // button back:
    echo '<button type="button" class="btn btn-default btn-xs" ' .
        'onclick="procRecept(\'' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/proc_report.php?id=' . $row['id_report'] . '&move=back\', \'back\');"' .
        ($report_st <= ModConsts::OC_REPORT_STATUS_PENDING || $report_st == 0 ? " disabled" : "") . '>' .
        '<span class="glyphicon glyphicon-circle-arrow-left"></span></button>&nbsp;';
    // button next:
    echo '<button type="button" class="btn btn-default btn-xs" ' .
        'onclick="procRecept(\'' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/proc_report.php?id=' . $row['id_report'] . '&move=next\', \'next\');"' .
        ($report_st >= ModConsts::OC_REPORT_STATUS_FINISHED || $report_st == 0 ? " disabled" : "") . '>' .
        '<span class="glyphicon glyphicon-circle-arrow-right"></span></button>';
    echo '</nobr></td>';
    echo '<td>' . $row['report_date'] . '</td>';
    echo '<td>' . $row['_rs_code'] . '</td>';
    echo '<td>' . $row['_cus_name'] . '</td>';
    echo '<td>' . $row['_cus_alias'] . '</td>';
    echo '<td>' . $row['reissue'] . '</td>';
    echo '<td>' . $row['_cb_code'] . '</td>';
    echo '<td><span class="badge">' . $row['_tests'] . '</span></td>';
    echo '<td class="small">' . $row['ui_name'] . '</td>';
    echo '<td class="small">' . $row['c_ts_user_ins'] . '</td>';
    echo '<td class="small">' . $row['uu_name'] . '</td>';
    echo '<td class="small">' . $row['c_ts_user_upd'] . '</td>';
    echo '<td><nobr>';
    echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_report.php?id=' . $row['id_report'] . '" class="btn btn-success btn-xs" role="button"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;';
    echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_report_tests.php?id=' . $row['id_report'] . '" class="btn btn-warning btn-xs" role="button"><span class="glyphicon glyphicon-file"></span></a>&nbsp;';
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
