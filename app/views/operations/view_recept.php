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
echo FAppNavbar::compose("recept");

echo '<div class="container" style="margin-top:50px">';
echo '<h3>Recepciones de muestras</h3>';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept.php" class="btn btn-primary btn-sm" role="button">Crear</a>';

$sql = <<<SQL
SELECT r.recept_num, r.id_recept, r.recept_datetime,
r.ts_user_ins AS c_ts_user_ins, r.ts_user_upd AS c_ts_user_upd,
cb.code AS _cb_code,
rs.code AS _rs_code,
cus.name AS _cus_name,
cus.alias AS _cus_alias,
ur.initials AS _ur_initials,
ui.name AS ui_name, uu.name AS uu_name,
(SELECT COUNT(*) FROM o_sample AS qs WHERE qs.nk_recept = r.id_recept) AS _samples
FROM o_recept AS r
INNER JOIN cc_company_branch AS cb ON r.fk_company_branch = cb.id_company_branch
INNER JOIN oc_recept_status AS rs ON r.fk_recept_status = rs.id_recept_status
INNER JOIN cc_entity AS cus ON r.fk_customer = cus.id_entity
INNER JOIN cc_user AS ur ON r.fk_user_receiver = ur.id_user
INNER JOIN cc_user AS ui ON r.fk_user_ins = ui.id_user
INNER JOIN cc_user AS uu ON r.fk_user_upd = uu.id_user
WHERE NOT r.is_deleted
ORDER BY r.recept_num, r.id_recept;
SQL;

echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Folio</th>';
echo '<th>Fecha-hr</th>';
echo '<th>Estatus</th>';
echo '<th>Cliente</th>';
echo '<th>Alias</th>';
echo '<th><abbr title="Cantidad muestras">C/M</abbr></th>';
echo '<th><abbr title="Receptor">Rec.</abbr></th>';
echo '<th><abbr title="Sucursal">Suc.</abbr></th>';
echo '<th class="small">Creador</th>';
echo '<th class="small">Creación</th>';
echo '<th class="small">Modificador</th>';
echo '<th class="small">Modificación</th>';
echo '<th></th>';
echo '<th></th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

$pdo = FGuiUtils::createPdo();
foreach ($pdo->query($sql) as $row) {
    echo '<tr>';
    echo '<td><b>' . $row['recept_num'] . '</b></td>';
    echo '<td>' . $row['recept_datetime'] . '</td>';
    echo '<td>' . $row['_rs_code'] . '</td>';
    echo '<td>' . $row['_cus_name'] . '</td>';
    echo '<td>' . $row['_cus_alias'] . '</td>';
    echo '<td><span class="badge">' . $row['_samples'] . '</span></td>';
    echo '<td>' . $row['_ur_initials'] . '</td>';
    echo '<td>' . $row['_cb_code'] . '</td>';
    echo '<td class="small">' . $row['ui_name'] . '</td>';
    echo '<td class="small">' . $row['c_ts_user_ins'] . '</td>';
    echo '<td class="small">' . $row['uu_name'] . '</td>';
    echo '<td class="small">' . $row['c_ts_user_upd'] . '</td>';
    echo '<td><a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept.php?id=' . $row['id_recept'] . '" class="btn btn-success btn-xs" role="button"><span class="glyphicon glyphicon-edit"></span></a></td>';
    echo '<td><a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_recept_samples.php?id=' . $row['id_recept'] . '" class="btn btn-warning btn-xs" role="button"><span class="glyphicon glyphicon-th-list"></span></a></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

echo '</div>';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
