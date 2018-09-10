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
use Fraphe\Lib\FFiles;
use Fraphe\Lib\FLibUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FModelUtils;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;
use app\models\ModConsts;
use app\models\operations\ModSample;
use app\models\operations\ModSamplingImage;
use app\models\operations\ModTest;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("recept");

$userSession = FGuiUtils::createUserSession();
$sample = new ModSample();
$samplingImg = new ModSamplingImage();
$errmsg = "";

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (!empty($_GET[FRegistry::ID])) {
            $samplingImg->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
            $sample->read($userSession, $samplingImg->getDatum("fk_sample"), FRegistry::MODE_READ);
        }
        else if (!empty($_GET["sample"])) {
            $sample->read($userSession, intval($_GET["sample"]), FRegistry::MODE_READ);
        }
        break;

    case "POST":
        if (!empty($_POST[FRegistry::ID])) {
            $samplingImg->read($userSession, intval($_POST[FRegistry::ID]), FRegistry::MODE_READ);
            $sample->read($userSession, $samplingImg->getDatum("fk_sample"), FRegistry::MODE_READ);
        }
        else if (!empty($_POST["sample"])) {
            $sample->read($userSession, intval($_POST["sample"]), FRegistry::MODE_READ);
        }

        $data = array();

        //$data["sampling_img"] = $_POST["sampling_img"];
        //$data["is_system"] = $_POST["is_system"];
        //$data["is_deleted"] = $_POST["is_deleted"];
        $data["fk_sample"] = $sample->getId();

        try {
            if (empty($_FILES["sampling_img"]["name"])) {
                throw new Exception("No se ha especificado un valor para '" . $samplingImg->getItem("sampling_img")->getName() . ".'");
            }

            $samplingImg->setParentSample($sample);
            $samplingImg->setData($data);

            FModelUtils::save($userSession, $samplingImg);

            FFiles::uploadFile($_FILES["sampling_img"], $samplingImg->getTargetFile());

            header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/operations/view_recept_sample_imgs.php?id=" . $sample->getId());
        }
        catch (Exception $e) {
            $errmsg = $e->getMessage();
        }
        break;

    default:
}

echo '<div class="container" style="margin-top:50px">';
echo '<div class="page-header">';
echo '<h3>Imagen de muestreo de la muestra</h3>';
echo '</div>';

if (!empty($errmsg)) {
    echo '<div class="alert alert-danger alert-dismissible">';
    echo '<a href="#" class="close" data-dismiss="alert" aria-label="cerrar">&times;</a>';
    echo '<strong>¡Error de captura!</strong> ' . $errmsg;
    echo '</div>';
}

////////////////////////////////////////////////////////////////////////////////
// Input Form for Samples
////////////////////////////////////////////////////////////////////////////////

echo '<form class="form-horizontal" method="post" action="' . FLibUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '" onsubmit="return validateForm();" enctype="multipart/form-data">';

// preserve registry ID in post:
echo '<input type="hidden" name="' . FRegistry::ID . '" value="' . $samplingImg->getId() . '">';
echo '<input type="hidden" name="sample" value="' . $sample->getId() . '">';

//------------------------------------------------------------------------------
// Sample:
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
echo '<div class="col-sm-3">' . $sample->getDatum("sample_name") . '</div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_quantity")->getName() . ':</b></div>';
echo '<div class="col-sm-2">' . $sample->getDatum("sample_quantity") . ' ' . AppUtils::readField($userSession, "code", AppConsts::OC_CONTAINER_UNIT, $sample->getDatum("fk_container_unit")) . '</div>';
echo '<div class="col-sm-2"><b>' . $sample->getItem("sample_lot")->getName() . ':</b></div>';
echo '<div class="col-sm-1">' . $sample->getDatum("sample_lot") . '</div>';
echo '</div>';

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Sampling image
//------------------------------------------------------------------------------

echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Imagen de muestreo</div>';
echo '<div class="panel-body">';

echo '<div class="row">';
echo '<div class="col-sm-2">';
echo '<label class="control-label small" for="">' . $samplingImg->getItem("sampling_img")->getName() . ':</label>';
echo '</div>';
echo '<div class="col-sm-10 text-center small">';
echo '<div class="thumbnail">';
if ($samplingImg->isRegistryNew()) {
    echo '<p>ND</p>';
}
else {
    echo '<img src="' . $samplingImg->getTargetFile() . '" alt="' . $samplingImg->getItem("sampling_img")->getName() . '" width="600" height="300">';
    echo '<p>' . $samplingImg->getItem("sampling_img")->getValue() . '</p>';
}
echo '</div>';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<div class="col-sm-2">';
echo '<label class="control-label small" for="sampling_img">' . $samplingImg->getItem("sampling_img")->getName() . ':*</label>';
echo '</div>';
echo '<div class="col-sm-10">';
echo '<input type="file" class="form-control input-sm" name="sampling_img" id="sampling_img">';
echo '</div>';
echo '</div>';

echo '</div>';
echo '</div>';
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
echo '<button type="submit" class="btn btn-sm btn-primary">Guardar</button>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_recept_sample_imgs.php?id=' . $sample->getId() . '" class="btn btn-sm btn-danger" role="button">Cancelar</a>';

echo '</form>';
echo '</div>';  // echo '<div class="container" style="margin-top:50px">';

echo FApp::composeFooter();
echo <<<SCRIPT
<script>
function validateForm() {
    return checkBeforeSubmit(); // prevent multiple form submitions
}
</script>
SCRIPT;
echo '</body>';
echo '</html>';
