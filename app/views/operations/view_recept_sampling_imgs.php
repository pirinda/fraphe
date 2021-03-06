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
use app\AppConsts;
use app\AppUtils;
use app\models\ModConsts;
use app\models\operations\ModSample;
use app\models\operations\ModRecept;
use app\models\operations\ModSamplingImage;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("recept");

$userSession = FGuiUtils::createUserSession();
$sample = new ModSample();
$sample->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
$recept = new ModRecept();
$recept->read($userSession, $sample->getDatum("nk_recept"), FRegistry::MODE_READ);

//------------------------------------------------------------------------------
echo '<div class="container" style="margin-top:50px">';
echo '<h3><span class="glyphicon glyphicon-picture"></span> Muestra</h3>';

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

echo '<h4>Imágenes de muestreo de la muestra</h4>';

echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept_sampling_img.php?sample=' . $sample->getId() . '" class="btn btn-primary btn-sm" role="button">Crear</a>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_recept_samples.php?id=' . $sample->getDatum("nk_recept") . '" class="btn btn-info btn-sm" role="button">Volver</a>';

$sql = <<<SQL
SELECT si.sampling_img, si.id_sampling_img,
si.ts_user_ins AS _ts_user_ins, si.ts_user_upd AS _ts_user_upd,
ui.name AS _ui_name, uu.name AS _uu_name
FROM o_sampling_img AS si
INNER JOIN cc_user AS ui ON si.fk_user_ins = ui.id_user
INNER JOIN cc_user AS uu ON si.fk_user_upd = uu.id_user
SQL;
$sql .= " ";
$sql .= "WHERE NOT si.is_deleted AND si.fk_sample = " . $_GET[FRegistry::ID] . " ";
$sql .= "ORDER BY si.sampling_img, si.id_sampling_img;";

echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Nombre</th>';
echo '<th>Imagen</th>';
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
    echo '<td>' . $row['sampling_img'] . '</td>';
    echo '<td><a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept_sampling_img.php?id=' . $row['id_sampling_img'] . '">';
    echo '<img src="' . ModSamplingImage::PATH_IMG . $row['sampling_img'] . '" alt="' . $row['sampling_img'] . '" width="150" height="100"></a></td>';
    echo '<td class="small">' . $row['_ui_name'] . '</td>';
    echo '<td class="small">' . $row['_ts_user_ins'] . '</td>';
    echo '<td class="small">' . $row['_uu_name'] . '</td>';
    echo '<td class="small">' . $row['_ts_user_upd'] . '</td>';
    echo '<td><nobr>';
    echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/forms/operations/form_recept_sampling_img.php?id=' . $row['id_sampling_img'] . '" class="btn btn-success btn-xs" role="button"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;';
    echo '<button type="button" class="btn btn-danger btn-xs" ' .
        'onclick="deleteImage(\'' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/delete_recept_sampling_img.php?id=' . $row['id_sampling_img'] . '\', \'' . $row['sampling_img'] . '\');"' .
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
function deleteImage(url, image) {
    if (confirm("¿Eliminar la imagen " + image + "?")) {
        window.location.assign(url);
    }
}
</script>
SCRIPT;
echo '</body>';
echo '</html>';
