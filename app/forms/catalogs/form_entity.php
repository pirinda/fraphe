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
use app\models\ModConsts;
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
$entity = new ModEntity();
$entityClass = 0;   // ModUtils::ENTITY_CLASS_...: 1=company; 2=customer; 3=provider
$entityNature = 0;  // ModUtils::ENTITY_NATURE_...: 1=person; 2=organization
$entityTypes;
$entityAddress;
$entityAddressCheckboxes = array("is_main", "is_recept", "is_process");
$contactTypes = ModEntityAddress::createContactTypes();
$errmsg = "";

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        // retrieve entity class and nature (if provided):

        if (!empty($_GET["class"])) {
            $entityClass = intval($_GET["class"]);
            $entityTypes = ModEntity::createEntityTypes($entityClass);
        }
        if (!empty($_GET["nature"])) {
            $entityNature = intval($_GET["nature"]);
        }

        // retrieve registry:

        if (!empty($_GET[FRegistry::ID])) {
            // registry modification:

            $entity->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_WRITE);
            $entityClass = $entity->getDatum("fk_entity_class");
            $entityNature = $entity->getDatum("is_person") ? ModUtils::ENTITY_NATURE_PER : ModUtils::ENTITY_NATURE_ORG;
            if (!isset($entityTypes)) {
                $entityTypes = ModEntity::createEntityTypes($entityClass);
            }
            if (count($entity->getChildAddresses()) > 0) {
                $entityAddress = $entity->getChildAddresses()[0];
            }
        }
        else {
            // registry creation:

            $data = array();
            $data["fk_entity_class"] = $entityClass;
            $data["is_person"] = $entityNature == ModUtils::ENTITY_NATURE_PER;
            $entity->setData($data);  // tailor registry
        }

        if (!isset($entityAddress)) {
            $entityAddress = new ModEntityAddress();
            $entity->getChildAddresses()[] = $entityAddress;
        }
        break;

    case "POST":
        // retrieve entity class and nature (must be provided):

        if (!empty($_POST["class"])) {
            $entityClass = intval($_POST["class"]);
            $entityTypes = ModEntity::createEntityTypes($entityClass);
        }
        if (!empty($_POST["nature"])) {
            $entityNature = intval($_POST["nature"]);
        }

        // retrieve registry data:

        $data = array();

        if (!empty($_POST[FRegistry::ID])) {
            $data["id_entity"] = intval($_POST[FRegistry::ID]);
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
            $data["nk_market_segment"] = $_POST["nk_market_segment"];
            //$data["nk_entity_parent"] = $_POST["nk_entity_parent"];
            //$data["nk_entity_billing"] = $_POST["nk_entity_billing"];
            //$data["nk_entity_agent"] = $_POST["nk_entity_agent"];
            //$data["nk_user_agent"] = $_POST["nk_user_agent"];
            $data["nk_report_delivery_opt"] = $_POST["nk_report_delivery_opt"];
        }

        $entity->clearChildEntityTypes();
        foreach ($entityTypes as $entityType) {
            if (!empty($_POST[ModEntityEntityType::PREFIX . $entityType]) && intval($_POST[ModEntityEntityType::PREFIX . $entityType]) == 1) {
                $entity->addChildEntityType($entityType);
            }
        }

        try {
            $entity->setData($data);
            $entity->save($userSession);
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

//------------------------------------------------------------------------------
// main section:
//------------------------------------------------------------------------------

echo '<div class="row">';

//------------------------------------------------------------------------------
// main section at the left:
//------------------------------------------------------------------------------

echo '<div class="col-sm-6">';
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Datos generales</div>';
echo '<div class="panel-body">';

echo $entity->getItem("code")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
echo $entity->getItem("fiscal_id")->composeHtmlInput(FItem::INPUT_TEXT, 4, 6);

if ($entityNature == ModUtils::ENTITY_NATURE_PER) {
    // person:
    echo $entity->getItem("surname")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
    echo $entity->getItem("forename")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
    echo $entity->getItem("prefix")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4);
}
else {
    // organization:
    echo $entity->getItem("name")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
}

echo $entity->getItem("alias")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $entity->getItem("apply_credit")->composeHtmlInput(FItem::INPUT_CHECKBOX, 0, 8);
echo $entity->getItem("credit_days")->composeHtmlInput(FItem::INPUT_NUMBER, 4, 4);
echo $entity->getItem("web_page")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $entity->getItem("notes")->composeHtmlTextArea(4, 8, 1);

$options = AppUtils::getSelectOptions($userSession, AppConsts::CC_MARKET_SEGMENT, $entity->getDatum("nk_market_segment"));
echo $entity->getItem("nk_market_segment")->composeHtmlSelect($options, 4, 8);

$options = AppUtils::getSelectOptions($userSession, AppConsts::OC_REPORT_DELIVERY_OPT, $entity->getDatum("nk_report_delivery_opt"));
echo $entity->getItem("nk_report_delivery_opt")->composeHtmlSelect($options, 4, 8);

if ($entityClass == ModUtils::ENTITY_CLASS_CUST) {
    echo $entity->getItem("apply_report_images")->composeHtmlInput(FItem::INPUT_CHECKBOX, 0, 8);
}

// render checkboxes of entity types in 4 rows of 3 columnes each:
$index = 0;
for ($row = 0; $row < 3; $row++) {
    echo '<div class="row">';
    for ($col = 0; $col < 3; $col++) {
        echo '<div class="col-sm-4">';
        echo '<label class="checkbox-inline small"><input type="checkbox" name="entity_type_' . $entityTypes[$index] . '" value="1"' . ($entity->isChildEntityType($entityTypes[$index]) ? ' checked' : '') . '>' .
        AppUtils::getName($userSession, AppConsts::CC_ENTITY_TYPE, $entityTypes[$index]) . '</label>';
        echo '</div>';
        $index++;
    }
    echo '</div>';
}

if (!$entity->isRegistryNew()) {
    echo '<br>';
    echo $entity->getItem("id_entity")->composeHtmlInput(FItem::INPUT_NUMBER, 4, 4);
}

echo '</div>';  //echo '<div class="panel-body">';
echo '</div>';  //echo '<div class="panel panel-default">';
echo '</div>';  //echo '<div class="col-sm-6">';

//------------------------------------------------------------------------------
// main section at the right:
//------------------------------------------------------------------------------

echo '<div class="col-sm-6">';
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Domicilio</div>';
echo '<div class="panel-body">';

echo $entityAddress->getItem("name")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("street")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("district")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("postcode")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("reference")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("city")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("county")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("state_region")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("country")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("location")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("business_hr")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("notes")->composeHtmlTextArea(4, 8, 1, ModEntityAddress::PREFIX);

echo '<div class="row">';
foreach ($entityAddressCheckboxes as $checkbox) {
    echo '<div class="col-sm-4">';
    echo '<label class="checkbox-inline small"><input type="checkbox" name="' . ModEntityEntityType::PREFIX . $checkbox . '" value="1"' . ($entityAddress->getItem($checkbox)->getValue() ? ' checked' : '') . '>' .
    $entityAddress->getItem($checkbox)->getName() . '</label>';
    echo '</div>';
}
echo '</div>';

echo '<div class="panel-group" id="accordion">';
echo '<div class="panel panel-default">
echo '<div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
        Collapsible Group 1</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse in">
      <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
      minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
      commodo consequat.</div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
        Collapsible Group 2</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse">
      <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
      minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
      commodo consequat.</div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
        Collapsible Group 3</a>
      </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse">
      <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
      minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
      commodo consequat.</div>
    </div>
  </div>
</div>

echo '</div>';  //echo '<div class="panel-body">';
echo '</div>';  //echo '<div class="panel panel-default">';
echo '</div>';  //echo '<div class="col-sm-6">';

echo '</div>';  // echo '<div class="row">';

//------------------------------------------------------------------------------
// main section at the right:
//------------------------------------------------------------------------------

echo '<br>';
echo '<button type="submit" class="btn btn-sm btn-primary">Guardar</button>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/catalogs/view_entity.php?class=' . $entityClass . '" class="btn btn-sm btn-danger" role="button">Cancelar</a>';

echo '</form>';
echo '</div>';  // echo '<div class="container" style="margin-top:50px">';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
