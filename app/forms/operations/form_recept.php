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
use Fraphe\Model\FItem;
use Fraphe\Model\FModelUtils;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;
use app\models\ModConsts;
use app\models\operations\ModRecept;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("recept");

$userSession = FGuiUtils::createUserSession();
$recept = new ModRecept();
$errmsg = "";

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        // read registry:

        if (!empty($_GET[FRegistry::ID])) {
            $recept->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
        }
        else {
            $data = array();
            $data["service_type"] = ModRecept::SERVICE_ORDINARY;
            $data["fk_user_receiver"] = $userSession->getCurUser()->getId();
            $recept->setData($data);
        }
        break;

    case "POST":
        if (!empty($_POST[FRegistry::ID])) {
            $recept->read($userSession, intval($_POST[FRegistry::ID]), FRegistry::MODE_WRITE);
        }

        $data = array();

        $data["recept_num"] = $_POST["recept_num"]; // TODO reactivate automatic generation of reception numbers!
        //$data["recept_datetime"] = $_POST["recept_datetime"];
        //$data["process_days"] = $_POST["process_days"];
        //$data["process_start_date"] = $_POST["process_start_date"];
        //$data["process_deadline"] = $_POST["process_deadline"];
        //$data["recept_deadline"] = $_POST["recept_deadline"];
        $data["recept_deviations"] = $_POST["recept_deviations"];
        $data["recept_notes"] = $_POST["recept_notes"];
        $data["service_type"] = $_POST["service_type"];
        $data["ref_chain_custody"] = $_POST["ref_chain_custody"];
        $data["ref_request"] = $_POST["ref_request"];
        $data["ref_agreet"] = $_POST["ref_agreet"];
        //$data["is_system"] = $_POST["is_system"];
        //$data["is_deleted"] = $_POST["is_deleted"];
        $data["fk_company_branch"] = $userSession->getCompanyBranch();
        $data["fk_customer"] = intval($_POST["fk_customer"]);
        $data["fk_recept_status"] = ModConsts::OC_RECEPT_STATUS_NEW;
        $data["fk_user_receiver"] = $_POST["fk_user_receiver"];

        try {
            $recept->setData($data);

            FModelUtils::save($userSession, $recept);

            header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/operations/view_recept_samples.php?id=" . $recept->getId());
        }
        catch (Exception $e) {
            $errmsg = $e->getMessage();
        }
        break;

    default:
}

echo '<div class="container" style="margin-top:50px">';
echo '<div class="page-header">';
echo '<h3>Recepción de muestras</h3>';
echo '</div>';

if (!empty($errmsg)) {
    echo '<div class="alert alert-danger alert-dismissible">';
    echo '<a href="#" class="close" data-dismiss="alert" aria-label="cerrar">&times;</a>';
    echo '<strong>¡Error de captura!</strong> ' . $errmsg;
    echo '</div>';
}

////////////////////////////////////////////////////////////////////////////////
// Input Form for Reception
////////////////////////////////////////////////////////////////////////////////

echo '<form class="form-horizontal" method="post" action="' . FLibUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '" onsubmit="return validateForm();">';

// preserve registry ID in post:
echo '<input type="hidden" name="' . FRegistry::ID . '" value="' . $recept->getId() . '">';

//------------------------------------------------------------------------------
// main section:
//------------------------------------------------------------------------------
echo '<div class="row">';

//------------------------------------------------------------------------------
// Left panel
//------------------------------------------------------------------------------
echo '<div class="col-sm-6">';

echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos de la recepción</div>';
echo '<div class="panel-body">';

//$recept->getItem("recept_num")->setGuiReadOnly(true); TODO reactivate automatic generation of reception numbers!
echo $recept->getItem("recept_num")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

$recept->getItem("recept_datetime")->setGuiReadOnly(true);
echo $recept->getItem("recept_datetime")->composeHtmlInput(FItem::INPUT_DATETIME, 4, 6);

$params = array();
$params["fk_entity_class"] = ModConsts::CC_ENTITY_CLASS_CUST;
$options = AppUtils::getSelectOptions($userSession, AppConsts::CC_ENTITY, $recept->getDatum("fk_customer"), $params);
echo $recept->getItem("fk_customer")->composeHtmlSelect($options, 4, 8);

echo '<div class="form-group">';
echo '<div class="col-sm-4">';
echo '<label class="control-label small" for="service_type">' . $recept->getItem("service_type")->getName() . ': *</label>';
echo '</div>';
echo '<div class="col-sm-8">';
echo '<label class="radio-inline small"><input type="radio" name="service_type" value="O"' . ($recept->getDatum("service_type") == ModRecept::SERVICE_ORDINARY ? ' checked' : '') . '>Ordinario</label>';
echo '<label class="radio-inline small"><input type="radio" name="service_type" value="U"' . ($recept->getDatum("service_type") == ModRecept::SERVICE_URGENT ? ' checked' : '') . '>Urgente</label>';
echo '</div>';
echo '</div>';

echo $recept->getItem("recept_deviations")->composeHtmlTextArea(4, 8, 2);
echo $recept->getItem("recept_notes")->composeHtmlTextArea(4, 8, 2);

$params = array();
$params["id_user_role"] = ModConsts::CC_USER_ROLE_RECEPT;
$options = AppUtils::getSelectOptions($userSession, AppConsts::CC_USER, $recept->getDatum("fk_user_receiver"), $params);
echo $recept->getItem("fk_user_receiver")->composeHtmlSelect($options, 4, 8);

echo '</div>';  //echo '<div class="panel-body">';
echo '</div>';  //echo '<div class="panel panel-default">';

echo '</div>';  // left panel

//------------------------------------------------------------------------------
// Right panel
//------------------------------------------------------------------------------
echo '<div class="col-sm-6">';

echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Referencias</div>';
echo '<div class="panel-body">';

echo $recept->getItem("ref_chain_custody")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $recept->getItem("ref_request")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $recept->getItem("ref_agreet")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);

echo '</div>';  //echo '<div class="panel-body">';
echo '</div>';  //echo '<div class="panel panel-default">';

echo '</div>';  // right panel

echo '</div>';  // row

//echo '<div class="row">';
echo '<button type="submit" class="btn btn-sm btn-primary"' . ($recept->getDatum("fk_recept_status") >= ModConsts::OC_RECEPT_STATUS_PROCESSING ? " disabled" : "") . '>Guardar</button>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/operations/view_recept.php" class="btn btn-sm btn-danger" role="button">Cancelar</a>';
//echo '</div>';  // row

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
