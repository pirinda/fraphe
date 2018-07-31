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
use Fraphe\Lib\FUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;
use app\models\ModUtils;
use app\models\catalogs\ModEntity;
use app\models\catalogs\ModEntityEntityType;
use app\models\catalogs\ModEntityAddress;
use app\models\catalogs\ModContact;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("catalogs");

$userSession = FGuiUtils::createUserSession();
$registry = new ModEntity();
$errmsg = "";
$entityClass = ModUtils::ENTITY_CLASS_CUST;     // 1=company; 2=customer; 3=provider
$entityNature = ModUtils::ENTITY_NATURE_PER;    // 1=person; 2=organization

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (!empty($_GET[FRegistry::ID])) {
            $registry->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_WRITE);
            $entityNature = $registry->getDatum("is_person") ? ModUtils::ENTITY_NATURE_PER : ModUtils::ENTITY_NATURE_ORG;
        }
        if (!empty($_GET["class"])) {
            $entityClass = intval($_GET["class"]);
        }
        if (!empty($_GET["nature"])) {
            $entityNature = intval($_GET["nature"]);
        }
        break;

    case "POST":
        $data = array();

        if (!empty($_POST[FRegistry::ID])) {
            $data["id_entity"] = intval($_POST[FRegistry::ID]);
        }
        if (!empty($_POST["class"])) {
            $entityClass = intval($_POST["class"]);
        }
        if (!empty($_POST["nature"])) {
            $entityNature = intval($_POST["nature"]);
        }

        if ($entityNature == ModUtils::ENTITY_NATURE_ORG) {
            $data["name"] = $_POST["name"];
        }

        $data["code"] = $_POST["code"];
        $data["alias"] = $_POST["alias"];

        if ($entityNature == ModUtils::ENTITY_NATURE_PER) {
            $data["prefix"] = $_POST["prefix"];
            $data["surname"] = $_POST["surname"];
            $data["forename"] = $_POST["forename"];
        }

        $data["fiscal_id"] = $_POST["fiscal_id"];
        $data["is_person"] = $entityNature == ModUtils::ENTITY_NATURE_PER;
        $data["apply_credit"] = empty($_POST["apply_credit"]) ? false : intval($_POST["apply_credit"]) == 1;
        $data["credit_days"] = intval($_POST["credit_days"]);
        //$data["billing_prefs"] = $_POST["billing_prefs"];
        $data["web_page"] = $_POST["web_page"];
        $data["notes"] = $_POST["notes"];
        $data["apply_report_images"] = empty($_POST["apply_report_images"]) ? false : intval($_POST["apply_report_images"]) == 1;
        //$data["is_system"] = $_POST["is_system"];
        //$data["is_deleted"] = $_POST["is_deleted"];
        $data["fk_entity_class"] = $entityClass;
        if ($entityClass == ModUtils::ENTITY_CLASS_CUST) {
            $data["nk_market_segment"] = intval($_POST["nk_market_segment"]);
        }
        //$data["nk_entity_parent"] = $_POST["nk_entity_parent"];
        //$data["nk_entity_billing"] = $_POST["nk_entity_billing"];
        if ($entityClass == ModUtils::ENTITY_CLASS_CUST) {
            //$data["nk_entity_agent"] = $_POST["nk_entity_agent"];
            //$data["nk_user_agent"] = $_POST["nk_user_agent"];
            $data["nk_report_delivery_opt"] = intval($_POST["nk_report_delivery_opt"]);
        }

        try {
            echo '<hr><h3>Antes</h3>';
            var_dump($registry);
            echo '<hr><h3>Después</h3>';
            $registry->setData($data);
            var_dump($registry);
            $registry->save($userSession);
            header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/catalogs/view_entity.php?class=$entityClass");
        }
        catch (Exception $e) {
            $errmsg = $e->getMessage();
        }
        break;

    default:
}

echo '<div class="container" style="margin-top:50px">';
echo '<div class="page-header">';
echo '<h1>' . ModUtils::getEntityClassSingular($entityClass) . ' ' . ModUtils::getEntityNatureAcronym($entityNature) . '</h1>';
echo '</div>';

if (!empty($errmsg)) {
    echo '<div class="alert alert-danger alert-dismissible">';
    echo '<a href="#" class="close" data-dismiss="alert" aria-label="cerrar">&times;</a>';
    echo '<strong>¡Error de captura!</strong> ' . $errmsg;
    echo '</div>';
}

////////////////////////////////////////////////////////////////////////////////
// Input Form for Entity
////////////////////////////////////////////////////////////////////////////////

echo '<form class="form-horizontal" method="post" action="' . FUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '">';

// preserve entity class and nature in post:
echo '<div class="form-group collapse">';
echo '<input type="hidden" name="class" value="' . $entityClass . '" readonly>';
echo '<input type="hidden" name="nature" value="' . $entityNature . '" readonly>';
echo '</div>';

// main section:

echo '<div class="row">';

// main section at the left:

echo '<div class="col-sm-6">';

echo $registry->getItem("code")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $registry->getItem("fiscal_id")->composeHtmlInput(FItem::INPUT_TEXT, 4, 6);

if ($entityNature == ModUtils::ENTITY_NATURE_PER) {
    // person:
    echo $registry->getItem("surname")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
    echo $registry->getItem("forename")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
    echo $registry->getItem("prefix")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
}
else {
    // organization:
    echo $registry->getItem("name")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
}

echo $registry->getItem("alias")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $registry->getItem("apply_credit")->composeHtmlInput(FItem::INPUT_CHECKBOX, 0, 8);
/*
echo '<div class="form-group">';
echo '<div class="col-sm-4">';
echo '<div class="checkbox">';
echo '<label><input type="checkbox" name="apply_credit" value="1"' . ($registry->getDatum("apply_credit") ? " checked" : "") . '>' . $registry->getItem("apply_credit")->getName() . '</label>';
echo '</div>';
echo '</div>';
echo '</div>';
*/
echo $registry->getItem("credit_days")->composeHtmlInput(FItem::INPUT_NUMBER, 4, 4);
echo $registry->getItem("web_page")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $registry->getItem("notes")->composeHtmlTextArea(4, 8, 1);

$options = AppUtils::getSelectOptions($userSession, AppConsts::CC_MARKET_SEGMENT, $registry->getDatum("nk_market_segment"));
echo $registry->getItem("nk_market_segment")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_REPORT_DELIVERY_OPT, $registry->getDatum("nk_report_delivery_opt"));
echo $registry->getItem("nk_report_delivery_opt")->composeHtmlSelect($options, 4, 8);

if ($entityClass == ModUtils::ENTITY_CLASS_CUST) {
    echo $registry->getItem("apply_report_images")->composeHtmlInput(FItem::INPUT_CHECKBOX, 0, 8);
}

if (!$registry->isRegistryNew()) {
    echo $registry->getItem("id_entity")->composeHtmlInput(FItem::INPUT_NUMBER, 4, 4);
}

echo '</div>';

// main section at the right:

echo '<div class="col-sm-6">';
echo '</div>';
echo '</div>';

/*
// child test process options:

$childProcessOpt;

if (empty($registry->getChildProcessOpts())) {
    $childProcessOpt = new ModTestProcessOpt();
    $data = array();
    $data["is_default"] = true;
    $childProcessOpt->setData($data);
}
else {
    $childProcessOpt = $registry->getChildProcessOpts()[0];
}

echo '<div class="form-group">';
echo '<label class="control-label" for="po_process_days_max">' . $childProcessOpt->getItem("process_days_max")->getName() . ': *</label>';
echo '<input type="text" class="form-control input-sm" name="po_process_days_max" value="' . $childProcessOpt->getDatum("process_days_max") . '">';
echo '</div>';

echo '<div class="form-group">';
echo '<label class="control-label" for="po_cost">' . $childProcessOpt->getItem("cost")->getName() . ':</label>';
echo '<input type="text" class="form-control input-sm" name="po_cost" value="' . $childProcessOpt->getDatum("cost") . '">';
echo '</div>';

echo '<div class="checkbox">';
echo '<label><input type="checkbox" name="po_is_default" value="1"' . ($childProcessOpt->getDatum("is_default") ? " checked" : "") . '>' . $childProcessOpt->getItem("is_default")->getName() . '</label>';
echo '</div>';
*/

echo '<br><button type="submit" class="btn btn-sm btn-primary">Guardar</button>';
echo '&nbsp;<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/catalogs/view_entity.php?class=' . $entityClass . '" class="btn btn-sm btn-danger" role="button">Cancelar</a>';

echo '</form>';
echo '</div>';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
