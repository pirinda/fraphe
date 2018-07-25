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
$connection = FGuiUtils::createConnection();
$registry = new ModEntity($connection);
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
        $data["is_credit"] = empty($_POST["is_credit"]) ? false : intval($_POST["is_credit"]) == 1;
        $data["credit_days"] = intval($_POST["credit_days"]);
        //$data["billing_prefs"] = $_POST["billing_prefs"];
        $data["web_page"] = $_POST["web_page"];
        $data["notes"] = $_POST["notes"];
        if ($entityClass == ModUtils::ENTITY_CLASS_CUST) {
            $data["is_report_images"] = $_POST["is_report_images"];
        }
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
            $registry->setData($data);
            $registry->validate();
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

// entity:

echo '<form class="form-horizontal" method="post" action="' . FUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '">';

// preserve entity class and nature in post:
echo '<div class="form-group collapse">';
echo '<input type="text" class="form-control input-sm" name="class" value="' . $entityClass . '" readonly>';
echo '<input type="text" class="form-control input-sm" name="nature" value="' . $entityNature . '" readonly>';
echo '</div>';

echo '<div class="form-group">';
echo '<label class="control-label col-sm-2 input-sm" for="code">' . $registry->getItem("code")->getName() . ': *</label>';
echo '<div class="col-sm-2">';
echo '<input type="text" class="form-control input-sm" name="code" value="' . $registry->getDatum("code") . '">';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<label class="control-label col-sm-2 input-sm" for="fiscal_id">' . $registry->getItem("fiscal_id")->getName() . ': *</label>';
echo '<div class="col-sm-2">';
echo '<input type="text" class="form-control input-sm" name="fiscal_id" value="' . $registry->getDatum("fiscal_id") . '">';
echo '</div>';
echo '</div>';

if ($entityNature == ModUtils::ENTITY_NATURE_PER) {
    // person:

    echo '<div class="form-group">';
    echo '<label class="control-label col-sm-2 input-sm" for="surname">' . $registry->getItem("surname")->getName() . ': *</label>';
    echo '<div class="col-sm-10">';
    echo '<input type="text" class="form-control input-sm" name="surname" value="' . $registry->getDatum("surname") . '">';
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label class="control-label col-sm-2 input-sm" for="forename">' . $registry->getItem("forename")->getName() . ': *</label>';
    echo '<div class="col-sm-10">';
    echo '<input type="text" class="form-control input-sm" name="forename" value="' . $registry->getDatum("forename") . '">';
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label class="control-label col-sm-2 input-sm" for="prefix">' . $registry->getItem("prefix")->getName() . ':</label>';
    echo '<div class="col-sm-2">';
    echo '<input type="text" class="form-control input-sm" name="prefix" value="' . $registry->getDatum("prefix") . '">';
    echo '</div>';
    echo '</div>';
}
else {
    // organization:

    echo '<div class="form-group">';
    echo '<label class="control-label col-sm-2 input-sm" for="name">' . $registry->getItem("name")->getName() . ': *</label>';
    echo '<div class="col-sm-10">';
    echo '<input type="text" class="form-control input-sm" name="name" value="' . $registry->getDatum("name") . '">';
    echo '</div>';
    echo '</div>';
}

echo '<div class="form-group">';
echo '<label class="control-label col-sm-2 input-sm" for="alias">' . $registry->getItem("alias")->getName() . ': *</label>';
echo '<div class="col-sm-10">';
echo '<input type="text" class="form-control input-sm" name="alias" value="' . $registry->getDatum("alias") . '">';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<div class="col-sm-offset-2 col-sm-2">';
echo '<div class="checkbox input-sm">';
echo '<label><input type="checkbox" name="is_credit" value="1"' . ($registry->getDatum("is_credit") ? " checked" : "") . '>' . $registry->getItem("is_credit")->getName() . '</label>';
echo '</div>';
echo '</div>';
//echo '</div>';

//echo '<div class="form-group">';
echo '<label class="control-label col-sm-2 input-sm" for="credit_days">' . $registry->getItem("credit_days")->getName() . ':</label>';
echo '<div class="col-sm-2">';
echo '<input type="text" class="form-control input-sm" name="credit_days" value="' . $registry->getDatum("credit_days") . '">';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<label class="control-label col-sm-2 input-sm" for="web_page">' . $registry->getItem("web_page")->getName() . ':</label>';
echo '<div class="col-sm-10">';
echo '<input type="text" class="form-control input-sm" name="web_page" value="' . $registry->getDatum("web_page") . '">';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<label class="control-label col-sm-2 input-sm" for="notes">' . $registry->getItem("notes")->getName() . ':</label>';
echo '<div class="col-sm-10">';
echo '<textarea class="form-control input-sm" name="notes" rows="1">' . $registry->getDatum("notes") . '</textarea>';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<label class="control-label col-sm-2 input-sm" for="nk_market_segment">' . $registry->getItem("nk_market_segment")->getName() . ': *</label>';
echo '<div class="col-sm-10">';
echo '<select class="form-control input-sm" name="nk_market_segment">';
foreach (AppUtils::getSelectOptions($connection, AppConsts::CC_MARKET_SEGMENT, $registry->getDatum("nk_market_segment")) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<label class="control-label col-sm-2 input-sm" for="nk_report_delivery_opt">' . $registry->getItem("nk_report_delivery_opt")->getName() . ': *</label>';
echo '<div class="col-sm-10">';
echo '<select class="form-control input-sm" name="nk_report_delivery_opt">';
foreach (AppUtils::getSelectOptions($connection, AppConsts::OC_REPORT_DELIVERY_OPT, $registry->getDatum("nk_report_delivery_opt")) as $option) {
    echo $option;
}
echo '</select>';
echo '</div>';
echo '</div>';

if (!$registry->isRegistryNew()) {
    echo '<div class="form-group">';
    echo '<label class="control-label col-sm-2 input-sm" for="' . FRegistry::ID . '">' . $registry->getItem("id_entity")->getName() . ':</label>';
    echo '<div class="col-sm-2">';
    echo '<input type="text" class="form-control input-sm" name="' . FRegistry::ID . '" value="' . $registry->getId() . '" readonly>';
    echo '</div>';
    echo '</div>';
}
/*
// child test process options:

$childProcessOpt;

if (empty($registry->getChildProcessOpts())) {
    $childProcessOpt = new ModTestProcessOpt($connection);
    $data = array();
    $data["is_default"] = true;
    $childProcessOpt->setData($data);
}
else {
    $childProcessOpt = $registry->getChildProcessOpts()[0];
}

echo '<div class="form-group">';
echo '<label for="po_process_days_max">' . $childProcessOpt->getItem("process_days_max")->getName() . ': *</label>';
echo '<input type="text" class="form-control" name="po_process_days_max" value="' . $childProcessOpt->getDatum("process_days_max") . '">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="po_cost">' . $childProcessOpt->getItem("cost")->getName() . ':</label>';
echo '<input type="text" class="form-control" name="po_cost" value="' . $childProcessOpt->getDatum("cost") . '">';
echo '</div>';

echo '<div class="checkbox">';
echo '<label><input type="checkbox" name="po_is_default" value="1"' . ($childProcessOpt->getDatum("is_default") ? " checked" : "") . '>' . $childProcessOpt->getItem("is_default")->getName() . '</label>';
echo '</div>';
*/

echo '<br><button type="submit" class="btn btn-primary btn-sm">Guardar</button>';
echo '&nbsp;<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/catalogs/view_entity.php?class=' . $entityClass . '" class="btn btn-danger btn-sm" role="button">Cancelar</a>';

echo '</form>';
echo '</div>';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
