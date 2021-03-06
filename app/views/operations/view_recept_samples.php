<?php
//------------------------------------------------------------------------------
// start session:
if (!isset($_SESSION)) {
    session_start();
}
// bootstrap Fraphe:
require $_SESSION["rootDir"] . "Fraphe/fraphe.php";
//------------------------------------------------------------------------------

use Fraphe\App\FApp;
use Fraphe\App\FAppConsts;
use Fraphe\App\FAppNavbar;
use Fraphe\App\FGuiUtils;
use Fraphe\Lib\FLibUtils;
use Fraphe\Model\FRegistry;
use app\models\ModConsts;
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
echo '<h3><span class="glyphicon glyphicon-list-alt"></span> Recepción de muestras</h3>';

//------------------------------------------------------------------------------
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos de la recepción</div>';
echo '<div class="panel-body small">';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $recept->getItem("recept_num")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><span class="bg-info lead">' . $recept->getDatum("recept_num") . '</span></div>';
echo '<div class="col-sm-2"><b>' . $recept->getItem("recept_datetime")->getName() . ':</b></div>';
echo '<div class="col-sm-3"><span class="bg-info">' . FLibUtils::formatStdDatetime($recept->getDatum("recept_datetime")) . '</span></div>';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-sm-2"><b>' . $recept->getItem("fk_customer")->getName() . ':</b></div>';
echo '<div class="col-sm-10">' . $customer->getDatum("name") . '</div>';
echo '</div>';

$address = $customer->getChildEntityAddresses()[0];
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
//------------------------------------------------------------------------------

echo '<h4>Muestras de la recepción de muestras</h4>';

echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept_sample.php?recept=' . $recept->getId() . '" class="btn btn-primary btn-sm" role="button">Crear</a>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_recept.php?" class="btn btn-info btn-sm" role="button">Volver</a>';

$sql = <<<SQL
SELECT s.sample_num, s.id_sample AS _id, s.sample_name, round(s.sample_quantity, 3) AS _sample_quantity, s.sample_lot,
s.ts_user_ins AS _ts_user_ins, s.ts_user_upd AS _ts_user_upd,
cb.code AS _cb_code,
ss.code AS _ss_code,
cu.code AS _cu_code,
us.initials AS _us_initials,
ur.initials AS _ur_initials,
ui.name AS _ui_name, uu.name AS _uu_name,
(SELECT COUNT(*) FROM o_sample_test AS qst WHERE qst.fk_sample = s.id_sample AND NOT qst.is_deleted) AS _tests,
(SELECT COUNT(*) FROM o_sampling_img AS qsi WHERE qsi.fk_sample = s.id_sample AND NOT qsi.is_deleted) AS _imgs
FROM o_sample AS s
INNER JOIN cc_company_branch AS cb ON s.fk_company_branch = cb.id_company_branch
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
echo '<th><abbr title="Unidad medida">UM</abbr></th>';
echo '<th>Lote</th>';
echo '<th>Estatus</th>';
echo '<th><abbr title="Químico muestreo">QM</abbr></th>';
echo '<th><abbr title="Receptor">Rec.</abbr></th>';
echo '<th><abbr title="Sucursal">Suc.</abbr></th>';
echo '<th><abbr title="Cantidad ensayos">Ens.</abbr></th>';
echo '<th><abbr title="Cantidad imágenes muestreo">Img.</abbr></th>';
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
    echo '<td><nobr><b>' . $row['sample_num'] . '</b></nobr></td>';
    echo '<td>' . $row['sample_name'] . '</td>';
    echo '<td>' . $row['_sample_quantity'] . '</td>';
    echo '<td>' . $row['_cu_code'] . '</td>';
    echo '<td>' . $row['sample_lot'] . '</td>';
    echo '<td>' . $row['_ss_code'] . '</td>';
    echo '<td>' . $row['_us_initials'] . '</td>';
    echo '<td>' . $row['_ur_initials'] . '</td>';
    echo '<td>' . $row['_cb_code'] . '</td>';
    echo '<td><span class="badge">' . $row['_tests'] . '</span></td>';
    echo '<td><span class="badge">' . $row['_imgs'] . '</span></td>';
    echo '<td class="small">' . $row['_ui_name'] . '</td>';
    echo '<td class="small">' . $row['_ts_user_ins'] . '</td>';
    echo '<td class="small">' . $row['_uu_name'] . '</td>';
    echo '<td class="small">' . $row['_ts_user_upd'] . '</td>';
    echo '<td><nobr>';
    echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept_sample.php?id=' . $row['_id'] . '" class="btn btn-success btn-xs" role="button"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;';
    echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_recept_sample_tests.php?id=' . $row['_id'] . '" class="btn btn-warning btn-xs" role="button"><span class="glyphicon glyphicon-th-list"></span></a>&nbsp;';
    echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_recept_sampling_imgs.php?id=' . $row['_id'] . '" class="btn btn-warning btn-xs" role="button"><span class="glyphicon glyphicon-picture"></span></a>&nbsp;';
    echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept_sample.php?id=' . $row['_id'] . '&copy=1" class="btn btn-primary btn-xs" role="button"><span class="glyphicon glyphicon-duplicate"></span></a>&nbsp;';
    echo '<button type="button" class="btn btn-danger btn-xs" ' .
        'onclick="deleteSample(\'' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/delete_recept_sample.php?id=' . $row['_id'] . '\', \'' . $row['sample_num'] . '\');"' .
        ($recept->getDatum("fk_recept_status") >= ModConsts::OC_RECEPT_STATUS_PROCESSING ? " disabled" : "") . '>' .
        '<span class="glyphicon glyphicon-trash"></span></button>';
    echo '</nobr></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

echo '</div>';

echo FApp::composeFooter();
echo <<<SCRIPT
<script>
function deleteSample(url, num) {
    if (confirm("¿Eliminar la muestra " + num + "?")) {
        window.location.assign(url);
    }
}
</script>
SCRIPT;
echo '</body>';
echo '</html>';
