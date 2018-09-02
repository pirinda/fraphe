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
use Fraphe\Lib\FUtils;
use Fraphe\Model\FRegistry;
use app\models\catalogs\ModEntity;
use app\models\operations\ModRecept;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("recept");

$userSession = FGuiUtils::createUserSession();
$recept = new ModRecept();
$recept->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
$customer = new ModEntity();
$customer->read($userSession, $recept->getDatum("fk_customer"), FRegistry::MODE_READ);

//------------------------------------------------------------------------------
echo '<div class="container" style="margin-top:50px">';
echo '<h3>Recepción de muestras</h3>';

echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Recepción</div>';
echo '<div class="panel-body small">';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $recept->getItem("recept_num")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><span class="bg-info lead">' . $recept->getDatum("recept_num") . '</span></div>';
echo '<div class="col-sm-2"><b>' . $recept->getItem("recept_datetime")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><span class="bg-info">' . FUtils::formatStdDatetime($recept->getDatum("recept_datetime")) . '</span></div>';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $recept->getItem("fk_customer")->getName() . ':</b></div>';
echo '<div class="col-sm-10">' . $customer->getDatum("name") . '</div>';
echo '</div>';

echo '<br>';

$address = $customer->getChildAddresses()[0];
echo '<div class="row">';
echo '<div class="col-sm-2"><b>Domicilio:</b></div>';
echo '<div class="col-sm-10">' . (empty($address) ? '' : $address->composeAddress()) . '</div>';
echo '</div>';

$contact = $address->getChildContactReport();
echo '<div class="row">';
echo '<div class="col-sm-2"><b>Contacto:</b></div>';
echo '<div class="col-sm-10">' . (empty($contact) ? '' : $contact->composeContact()) . '</div>';
echo '</div>';

echo '</div>';
echo '</div>';

echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept_sample.php?recept=' . $recept->getId() . '" class="btn btn-primary btn-sm" role="button">Crear</a>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_recept.php" class="btn btn-danger btn-sm" role="button">Volver</a>';

$sql = <<<SQL
SELECT s.sample_num, s.id_sample AS _id, s.sample_name, s.sample_quantity, s.sample_lot,
s.ts_user_ins AS _ts_user_ins, s.ts_user_upd AS _ts_user_upd,
cb.code AS _cb_code,
sc.name AS _sc_name,
st.name AS _st_name,
ss.code AS _ss_code,
cu.code AS _cu_code,
us.initials AS _us_initials,
ur.initials AS _ur_initials,
ui.name AS _ui_name, uu.name AS _uu_name
FROM o_sample AS s
INNER JOIN cc_company_branch AS cb ON s.fk_company_branch = cb.id_company_branch
INNER JOIN oc_sample_class AS sc ON s.fk_sample_class = sc.id_sample_class
INNER JOIN oc_sample_type AS st ON s.fk_sample_type = st.id_sample_type
INNER JOIN oc_sample_status AS ss ON s.fk_sample_status = ss.id_sample_status
INNER JOIN oc_container_unit AS cu ON s.fk_container_unit = cu.id_container_unit
INNER JOIN cc_user AS us ON s.fk_user_sampler = us.id_user
INNER JOIN cc_user AS ur ON s.fk_user_receiver = ur.id_user
INNER JOIN cc_user AS ui ON s.fk_user_ins = ui.id_user
INNER JOIN cc_user AS uu ON s.fk_user_upd = uu.id_user
SQL;
$sql .= " ";
$sql .= "WHERE NOT s.is_deleted AND s.nk_recept = " . $_GET[FRegistry::ID] . " ";
$sql .= "ORDER BY s.sample_num, s.id_sample;";

echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Folio</th>';
echo '<th>Nombre</th>';
echo '<th>Cantidad</th>';
echo '<th>Unidad</th>';
echo '<th>Lote</th>';
echo '<th>Tipo</th>';
echo '<th>Estatus</th>';
echo '<th>Mue.</th>';
echo '<th>Rec.</th>';
echo '<th>Suc.</th>';
echo '<th class="small">Creador</th>';
echo '<th class="small">Creación</th>';
echo '<th class="small">Modificador</th>';
echo '<th class="small">Modificación</th>';
echo '<th></th>';
echo '<th></th>';
//echo '<th></th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

$pdo = FGuiUtils::createPdo();
foreach ($pdo->query($sql) as $row) {
    echo '<tr>';
    echo '<td>' . $row['sample_num'] . '</td>';
    echo '<td>' . $row['sample_name'] . '</td>';
    echo '<td>' . $row['sample_quantity'] . '</td>';
    echo '<td>' . $row['_cu_code'] . '</td>';
    echo '<td>' . $row['sample_lot'] . '</td>';
    echo '<td>' . $row['_st_name'] . '</td>';
    echo '<td>' . $row['_ss_code'] . '</td>';
    echo '<td>' . $row['_us_initials'] . '</td>';
    echo '<td>' . $row['_ur_initials'] . '</td>';
    echo '<td>' . $row['_cb_code'] . '</td>';
    echo '<td class="small">' . $row['_ui_name'] . '</td>';
    echo '<td class="small">' . $row['_ts_user_ins'] . '</td>';
    echo '<td class="small">' . $row['_uu_name'] . '</td>';
    echo '<td class="small">' . $row['_ts_user_upd'] . '</td>';
    echo '<td><a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept_sample.php?id=' . $row['_id'] . '" class="btn btn-success btn-xs" role="button"><span class="glyphicon glyphicon-edit"></span></a></td>';
    echo '<td><a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept_sample.php?id=' . $row['_id'] . '&copy=1" class="btn btn-success btn-xs" role="button"><span class="glyphicon glyphicon-duplicate"></span></a></td>';
//    echo '<td><a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept_sample.php?id=' . $row['_id'] . '&copy=1" class="btn btn-danger btn-xs" role="button"><span class="glyphicon glyphicon-ban-circle"></span></a></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

echo '</div>';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
