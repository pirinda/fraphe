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
use app\models\ModUtils;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("catalogs");

echo '<div class="container" style="margin-top:50px">';
echo '<h3>' . ModUtils::getEntityClassPlural(intval($_GET["class"])) . '</h3>';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/catalogs/form_entity.php?class=' . $_GET["class"] . '&nature=1" class="btn btn-primary btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="' . ModUtils::getEntityNature(ModUtils::ENTITY_NATURE_PER) . '">Crear ' .  ModUtils::getEntityNatureAcronym(ModUtils::ENTITY_NATURE_PER) . '</a>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/catalogs/form_entity.php?class=' . $_GET["class"] . '&nature=2" class="btn btn-primary btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="' . ModUtils::getEntityNature(ModUtils::ENTITY_NATURE_ORG) . '">Crear ' .  ModUtils::getEntityNatureAcronym(ModUtils::ENTITY_NATURE_ORG) . '</a>';

$sql = <<<SQL
SELECT c.name AS c_name, c.code AS c_code, c.id_entity AS c_id,
c.ts_user_ins AS c_ts_user_ins, c.ts_user_upd AS c_ts_user_upd,
ui.name AS ui_name, uu.name AS uu_name
FROM cc_entity AS c
INNER JOIN cc_user AS ui ON c.fk_user_ins = ui.id_user
INNER JOIN cc_user AS uu ON c.fk_user_upd = uu.id_user
SQL;
$sql .= " ";
$sql .= "WHERE NOT c.is_deleted AND c.fk_entity_class = " . $_GET["class"] . " ";
$sql .= "ORDER BY c.name, c.id_entity;";

echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Nombre</th>';
echo '<th>Código</th>';
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
    echo '<td>' . $row['c_name'] . '</td>';
    echo '<td>' . $row['c_code'] . '</td>';
    echo '<td class="small">' . $row['ui_name'] . '</td>';
    echo '<td class="small">' . $row['c_ts_user_ins'] . '</td>';
    echo '<td class="small">' . $row['uu_name'] . '</td>';
    echo '<td class="small">' . $row['c_ts_user_upd'] . '</td>';
    echo '<td><a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/catalogs/form_entity.php?class=' . $_GET["class"] . '&id=' . $row['c_id'] . '" class="btn btn-success btn-xs" role="button" data-toggle="tooltip" data-placement="right" title="Modificar"><span class="glyphicon glyphicon-edit"></span></a></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

echo '</div>';

echo FApp::composeFooter();
echo '<script>$(document).ready(function(){$(\'[data-toggle="tooltip"]\').tooltip();});</script>';  // enable Bootstrap tooltips
echo '</body>';
echo '</html>';
