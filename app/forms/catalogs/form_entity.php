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
use Fraphe\Lib\FUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FModel;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;
use app\models\ModConsts;
use app\models\ModUtils;
use app\models\catalogs\ModEntity;
use app\models\catalogs\ModEntityEntityType;
use app\models\catalogs\ModEntitySamplingImage;
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
$entitySamplingImage;
$entityAddress;
$entityAddressCheckboxes = array("is_main", "is_recept", "is_process");
$contact;
$errmsg = "";
$targetFile = "";

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

        // read registry:

        if (!empty($_GET[FRegistry::ID])) {
            $entity->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
            $entityClass = $entity->getDatum("fk_entity_class");
            $entityNature = $entity->getDatum("is_person") ? ModUtils::ENTITY_NATURE_PER : ModUtils::ENTITY_NATURE_ORG;

            if (!isset($entityTypes)) {
                $entityTypes = ModEntity::createEntityTypes($entityClass);
            }
            if (count($entity->getChildEntitySamplingImages()) > 0) {
                $entitySamplingImage = $entity->getChildEntitySamplingImages()[0];
            }
            if (count($entity->getChildEntityAddresses()) > 0) {
                $entityAddress = $entity->getChildEntityAddresses()[0];
            }

            $targetFile = "../../img/entity/" . $entity->composeTargetFileDefSamplingImage(1);
        }
        else {
            // registry creation to taylor entity object:

            $data = array();
            $data["fk_entity_class"] = $entityClass;
            $data["is_person"] = $entityNature == ModUtils::ENTITY_NATURE_PER;
            $entity->setData($data);  // tailors registry members aswell
        }

        if (!isset($entityAddress)) {
            $entityAddress = new ModEntityAddress();
            $entityAddress->getItem("name")->setValue("Matriz");
            $entityAddress->getItem("country")->setValue("MEX"); // TODO: parameterize this configurable value!
            $entityAddress->getItem("is_main")->setValue(true);
            $entity->getChildEntityAddresses()[0] = $entityAddress;
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

        // read registry:

        if (!empty($_POST[FRegistry::ID])) {
            $entity->read($userSession, intval($_POST[FRegistry::ID]), FRegistry::MODE_WRITE);
            $entityClass = $entity->getDatum("fk_entity_class");
            $entityNature = $entity->getDatum("is_person") ? ModUtils::ENTITY_NATURE_PER : ModUtils::ENTITY_NATURE_ORG;

            if (!isset($entityTypes)) {
                $entityTypes = ModEntity::createEntityTypes($entityClass);
            }
            if (count($entity->getChildEntitySamplingImages()) > 0) {
                $entitySamplingImage = $entity->getChildEntitySamplingImages()[0];
            }
            if (count($entity->getChildEntityAddresses()) > 0) {
                $entityAddress = $entity->getChildEntityAddresses()[0];
            }
        }

        if (!isset($entityAddress)) {
            $entityAddress = new ModEntityAddress();
            $entityAddress->getItem("name")->setValue("Matriz");
            $entityAddress->getItem("country")->setValue("MEX"); // TODO: parameterize this configurable value!
            $entityAddress->getItem("is_main")->setValue(true);
            $entity->getChildEntityAddresses()[0] = $entityAddress;
        }

        // recover registry data:

        $data = array();

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
        $data["is_credit"] = empty($_POST["is_credit"]) ? false : boolval($_POST["is_credit"]);
        $data["credit_days"] = intval($_POST["credit_days"]);
        if ($entityClass == ModUtils::ENTITY_CLASS_CUST) {
            $data["billing_prefs"] = $_POST["billing_prefs"];
        }
        $data["web_page"] = $_POST["web_page"];
        $data["notes"] = $_POST["notes"];
        $data["is_def_sampling_img"] = empty($_POST["is_def_sampling_img"]) ? false : boolval($_POST["is_def_sampling_img"]);
        //$data["is_system"] = $_POST["is_system"];
        //$data["is_deleted"] = $_POST["is_deleted"];
        $data["fk_entity_class"] = $entityClass;
        if ($entityClass == ModUtils::ENTITY_CLASS_CUST) {
            $data["nk_market_segment"] = intval($_POST["nk_market_segment"]);
            $data["nk_entity_parent"] = intval($_POST["nk_entity_parent"]);
            $data["nk_entity_billing"] = intval($_POST["nk_entity_billing"]);
            $data["nk_entity_agent"] = intval($_POST["nk_entity_agent"]);
            $data["nk_report_delivery_type"] = intval($_POST["nk_report_delivery_type"]);
        }

        // entity types:
        $entity->clearChildEntityEntityTypes();
        foreach ($entityTypes as $entityType) {
            if (!empty($_POST[ModEntityEntityType::PREFIX . $entityType]) && boolval($_POST[ModEntityEntityType::PREFIX . $entityType])) {
                $entity->addChildEntityEntityType($entityType);
            }
        }

        // entity image:
        if (!empty($_POST["load_def_sampling_img"]) && boolval($_POST["load_def_sampling_img"])) {
            if (!isset($entitySamplingImage)) {
                $entitySamplingImage = new ModEntitySamplingImage();
                $entity->getChildEntitySamplingImages()[0] = $entitySamplingImage;
            }

            $dataImage = array();
            $dataImage["id_entity_sampling_img"] = intval($_POST[ModEntitySamplingImage::PREFIX . "id_entity_sampling_img"]);
            //$dataImage["sampling_img"] = $_POST[ModEntitySamplingImage::PREFIX . "def_file"]; XXX!!!
            $dataImage["sampling_img"] = $_FILES[ModEntitySamplingImage::PREFIX . "def_file"]["name"];
            //$data["is_system"] = $_POST["is_system"];
            //$data["is_deleted"] = $_POST["is_deleted"];
            //$data["fk_entity"] = $_POST["fk_entity"];
        }

        // entity address:
        $dataAddress = array();
        $dataAddress["id_entity_address"] = intval($_POST[ModEntityAddress::PREFIX . "id_entity_address"]);
        $dataAddress["name"] = $_POST[ModEntityAddress::PREFIX . "name"];
        $dataAddress["street"] = $_POST[ModEntityAddress::PREFIX . "street"];
        $dataAddress["district"] = $_POST[ModEntityAddress::PREFIX . "district"];
        $dataAddress["postcode"] = $_POST[ModEntityAddress::PREFIX . "postcode"];
        $dataAddress["reference"] = $_POST[ModEntityAddress::PREFIX . "reference"];
        $dataAddress["city"] = $_POST[ModEntityAddress::PREFIX . "city"];
        $dataAddress["county"] = $_POST[ModEntityAddress::PREFIX . "county"];
        $dataAddress["state_region"] = $_POST[ModEntityAddress::PREFIX . "state_region"];
        $dataAddress["country"] = $_POST[ModEntityAddress::PREFIX . "country"];
        $dataAddress["location"] = $_POST[ModEntityAddress::PREFIX . "location"];
        $dataAddress["business_hr"] = $_POST[ModEntityAddress::PREFIX . "business_hr"];
        $dataAddress["notes"] = $_POST[ModEntityAddress::PREFIX . "notes"];
        $dataAddress["is_main"] = empty($_POST[ModEntityAddress::PREFIX . "is_main"]) ? false : boolval($_POST[ModEntityAddress::PREFIX . "is_main"]);
        $dataAddress["is_recept"] = empty($_POST[ModEntityAddress::PREFIX . "is_recept"]) ? false : boolval($_POST[ModEntityAddress::PREFIX . "is_recept"]);
        $dataAddress["is_process"] = empty($_POST[ModEntityAddress::PREFIX . "is_process"]) ? false : boolval($_POST[ModEntityAddress::PREFIX . "is_process"]);
        //$dataAddress["is_system"] = $_POST[ModEntityAddress::PREFIX . "is_system"];
        //$dataAddress["is_deleted"] = $_POST[ModEntityAddress::PREFIX . "is_deleted"];
        //$dataAddress["fk_entity"] = $_POST[ModEntityAddress::PREFIX . "fk_entity"];

        // contacts:
        $dataContacts = array();
        for ($contactType = ModConsts::CC_CONTACT_TYPE_MAIN; $contactType <= ModConsts::CC_CONTACT_TYPE_COLL; $contactType++) {
            $prefix = ModContact::PREFIX . $contactType . "_";
            if (!empty($_POST[$prefix . "apply"]) && boolval($_POST[$prefix . "apply"])) {
                $dataContact = array();
                $dataContact["id_contact"] = intval($_POST[$prefix . "id_contact"]);
                //$dataContact["name"] = $_POST[$prefix . "name"];
                $dataContact["prefix"] = $_POST[$prefix . "prefix"];
                $dataContact["surname"] = $_POST[$prefix . "surname"];
                $dataContact["forename"] = $_POST[$prefix . "forename"];
                $dataContact["job"] = $_POST[$prefix . "job"];
                $dataContact["mail"] = $_POST[$prefix . "mail"];
                $dataContact["phone"] = $_POST[$prefix . "phone"];
                $dataContact["mobile"] = $_POST[$prefix . "mobile"];
                $dataContact["is_report"] = empty($_POST[$prefix . "is_report"]) ? false : boolval($_POST[$prefix . "is_report"]);
                //$dataContact["is_system"] = $_POST[$prefix . "is_system"];
                //$dataContact["is_deleted"] = $_POST[$prefix . "is_deleted"];
                //$dataContact["fk_entity"] = $_POST[$prefix . "fk_entity"];
                //$dataContact["fk_entity_address"] = $_POST[$prefix . "fk_entity_address"];
                $dataContact["fk_contact_type"] = $_POST[$prefix . "fk_contact_type"];
                $dataContacts[] = $dataContact;
            }
        }

        try {
            if (!empty($_POST["load_def_sampling_img"]) && boolval($_POST["load_def_sampling_img"])) {
                $entitySamplingImage->setData($dataImage);
            }

            $entityAddress->setData($dataAddress);

            foreach ($dataContacts as $dataContact) {
                $contact = new ModContact();
                $contact->setData($dataContact);
                $entityAddress->getChildContacts()[] = $contact;
            }

            $entity->setData($data);

            FModel::save($userSession, $entity);

            if (!empty($_POST["load_def_sampling_img"]) && boolval($_POST["load_def_sampling_img"])) {
                $targetFile = "../../img/entity/" . $entity->composeTargetFileDefSamplingImage(1);
                FFiles::uploadFile($_FILES[ModEntitySamplingImage::PREFIX . "def_file"], $targetFile);
            }

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
echo '<h3>' . ModUtils::getEntityClassSingular($entityClass) . ' ' . ModUtils::getEntityNatureAcronym($entityNature) . '</h3>';
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

echo '<form class="form-horizontal" method="post" action="' . FUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '" onsubmit="return validateForm()" enctype="multipart/form-data">';

// preserve entity class and nature and registry ID in post:
echo '<input type="hidden" name="class" value="' . $entityClass . '">';
echo '<input type="hidden" name="nature" value="' . $entityNature . '">';
echo '<input type="hidden" name="' . FRegistry::ID . '" value="' . $entity->getId() . '">';

//------------------------------------------------------------------------------
// main section:
//------------------------------------------------------------------------------
echo '<div class="row">';

//------------------------------------------------------------------------------
// Left panel
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
echo $entity->getItem("is_credit")->composeHtmlInput(FItem::INPUT_CHECKBOX, 0, 12);
echo $entity->getItem("credit_days")->composeHtmlInput(FItem::INPUT_NUMBER, 4, 4);

if ($entityClass == ModUtils::ENTITY_CLASS_CUST) {
    echo $entity->getItem("billing_prefs")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
}

echo $entity->getItem("web_page")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8);
echo $entity->getItem("notes")->composeHtmlTextArea(4, 8, 1);

if ($entityClass == ModUtils::ENTITY_CLASS_CUST) {
    $options = AppUtils::getSelectOptions($userSession, AppConsts::CC_MARKET_SEGMENT, $entity->getDatum("nk_market_segment"));
    echo $entity->getItem("nk_market_segment")->composeHtmlSelect($options, 4, 8);

    $params = array();
    $params["fk_entity_class"] = ModConsts::CC_ENTITY_CLASS_CUST;
    $params["entity_type_1"] = ModConsts::CC_ENTITY_TYPE_CUST_CORP;
    $options = AppUtils::getSelectOptions($userSession, AppConsts::CC_ENTITY, $entity->getDatum("nk_entity_parent"), $params);
    echo $entity->getItem("nk_entity_parent")->composeHtmlSelect($options, 4, 8);

    $params = array();
    $params["fk_entity_class"] = ModConsts::CC_ENTITY_CLASS_CUST;
    $options = AppUtils::getSelectOptions($userSession, AppConsts::CC_ENTITY, $entity->getDatum("nk_entity_billing"), $params);
    echo $entity->getItem("nk_entity_billing")->composeHtmlSelect($options, 4, 8);

    $params = array();
    $params["fk_entity_class"] = ModConsts::CC_ENTITY_CLASS_PROV;
    $params["entity_type_1"] = ModConsts::CC_ENTITY_TYPE_CUST_SAL_EXT;
    $params["entity_type_2"] = ModConsts::CC_ENTITY_TYPE_CUST_SAL_INT;
    $options = AppUtils::getSelectOptions($userSession, AppConsts::CC_ENTITY, $entity->getDatum("nk_entity_agent"), $params);
    echo $entity->getItem("nk_entity_agent")->composeHtmlSelect($options, 4, 8);

    $options = AppUtils::getSelectOptions($userSession, AppConsts::OC_REPORT_DELIVERY_TYPE, $entity->getDatum("nk_report_delivery_type"));
    echo $entity->getItem("nk_report_delivery_type")->composeHtmlSelect($options, 4, 8);

    echo $entity->getItem("is_def_sampling_img")->composeHtmlInput(FItem::INPUT_CHECKBOX, 0, 12);

    echo '<input type="hidden" name="' . ModEntitySamplingImage::PREFIX . 'id_entity_sampling_img" value="' . (!isset($entitySamplingImage) ? 0 : $entitySamplingImage->getId()) . '">';

    echo '<div class="row">';
    echo '<div class="col-sm-4">';
    echo '<label class="control-label small" for="">Imagen muestreo x def.:</label>';
    echo '</div>';
    echo '<div class="col-sm-8 text-center small">';
    echo '<div class="thumbnail">';
    echo '<img src="' . $targetFile . '" alt="Sin imagen" width="150" height="100">';
    echo '<p>' . (!isset($entitySamplingImage) ? "n/d" : $entitySamplingImage->getItem("sampling_img")->getValue()) . '</p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<div class="col-sm-12">';
    echo '<div class="checkbox">';
    echo '<label class="small"><input type="checkbox" name="load_def_sampling_img" id="load_def_sampling_img" onclick="switchInputFile();" value="1">Subir nueva imagen muestreo x def.</label>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<div class="col-sm-4">';
    echo '<label class="control-label small" for="' . ModEntitySamplingImage::PREFIX . 'def_file">Imagen muestreo x def.:</label>';
    echo '</div>';
    echo '<div class="col-sm-8">';
    echo '<input type="file" class="form-control input-sm" name="' . ModEntitySamplingImage::PREFIX . 'def_file" id="' . ModEntitySamplingImage::PREFIX . 'def_file" disabled>';
    echo '</div>';
    echo '</div>';
}

// render checkboxes of entity types in 4 rows of 3 columnes each:
$index = 0;
for ($row = 0; $row < 3; $row++) {
    echo '<div class="row">';
    for ($col = 0; $col < 3; $col++) {
        echo '<div class="col-sm-4">';
        echo '<label class="checkbox-inline small"><input type="checkbox" name="' . ModEntityEntityType::PREFIX . $entityTypes[$index] . '" value="1"' . ($entity->hasChildEntityEntityType($entityTypes[$index]) ? ' checked' : '') . '>' .
        AppUtils::getName($userSession, AppConsts::CC_ENTITY_TYPE, $entityTypes[$index]) . '</label>';
        echo '</div>';
        $index++;
    }
    echo '</div>';
}

echo '</div>';  //echo '<div class="panel-body">';
echo '</div>';  //echo '<div class="panel panel-default">';

echo '</div>';  // left panel

//------------------------------------------------------------------------------
// Right panel
//------------------------------------------------------------------------------
echo '<div class="col-sm-6">';

echo '<div class="panel panel-default">';
echo '<div class="panel-heading">Domicilio</div>';
echo '<div class="panel-body">';

// Address:
echo '<input type="hidden" name="' . ModEntityAddress::PREFIX . 'id_entity_address" value="' . $entityAddress->getId() . '">';
echo $entityAddress->getItem("name")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("street")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("district")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("postcode")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("reference")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("city")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("county")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("state_region")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("country")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("location")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("business_hr")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, ModEntityAddress::PREFIX);
echo $entityAddress->getItem("notes")->composeHtmlTextArea(4, 8, 1, ModEntityAddress::PREFIX);

echo '<div class="row">';
foreach ($entityAddressCheckboxes as $checkbox) {
    echo '<div class="col-sm-4">';
    echo '<label class="checkbox-inline small"><input type="checkbox" name="' . ModEntityAddress::PREFIX . $checkbox . '" value="1"' . ($entityAddress->getItem($checkbox)->getValue() ? ' checked' : '') . '>' .
    $entityAddress->getItem($checkbox)->getName() . '</label>';
    echo '</div>';
}
echo '</div>';

echo '</div>';  //echo '<div class="panel-body">';
echo '</div>';  //echo '<div class="panel panel-default">';

echo '</div>';  // right panel

echo '</div>';  // row

//------------------------------------------------------------------------------
// contacts section:
//------------------------------------------------------------------------------
echo '<div class="row">';

for ($panel = 1; $panel <= 2; $panel++) {
    $from;
    $to;
    switch ($panel) {
        case 1:
            $from = ModConsts::CC_CONTACT_TYPE_MAIN;    // 1
            $to = ModConsts::CC_CONTACT_TYPE_PROCESS;   // 5
            break;
        case 2:
            $from = ModConsts::CC_CONTACT_TYPE_RESULT;  // 6
            $to = ModConsts::CC_CONTACT_TYPE_COLL;      // 10
            break;
        default:
    }

    echo '<div class="col-sm-6">';
    echo '<div class="panel-group" id="accordion' . $panel . '">';

    for ($contactType = $from; $contactType <= $to; $contactType++) {
        $prefix = ModContact::PREFIX . $contactType . "_";
        $contact = $entityAddress->getChildContact($contactType);

        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading">';
        echo '<div class="panel-title">';
        echo '<a id="' . $prefix . 'panel" class="small" data-toggle="collapse" data-parent="#accordion' . $panel . '" href="#collapse' . $contactType . '">';
        echo 'Contacto ' . strtolower(AppUtils::getName($userSession, AppConsts::CC_CONTACT_TYPE, $contactType)) . '</a>';
        echo '&nbsp;<span id="' . $prefix . 'icon_ok" class="' . (!empty($contact) ? "glyphicon glyphicon-ok-sign small" : "") . '"></span>';
        echo '&nbsp;<span id="' . $prefix . 'icon_file" class="' . (!empty($contact) && $contact->getItem("is_report")->getValue() ? "glyphicon glyphicon-file small" : "") . '"></span>';
        echo '</div>';
        echo '</div>';
        echo '<div id="collapse' . $contactType . '" class="panel-collapse collapse' . ($contactType == $from ? " in" : "") . '">';
        echo '<div class="panel-body">';

        echo '<div class="form-group">';
        echo '<div class="col-sm-offset-0 col-sm-12">';
        echo '<div class="checkbox">';
        echo '<label class="small"><input type="checkbox" name="' . $prefix . 'apply" onclick="showContactIcons(\'' . $prefix . '\')" value="1"' . (empty($contact) ? "" : " checked") . '>Aplica</label>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        if (empty($contact)) {
            $contact = new ModContact();
        }

        echo '<input type="hidden" name="' . $prefix . 'id_contact" value="' . $contact->getId() . '">';
        echo '<input type="hidden" name="' . $prefix . 'fk_contact_type" value="' . $contactType . '">';
        echo $contact->getItem("surname")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, $prefix);
        echo $contact->getItem("forename")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, $prefix);
        echo $contact->getItem("prefix")->composeHtmlInput(FItem::INPUT_TEXT, 4, 4, $prefix);
        echo $contact->getItem("job")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, $prefix);
        echo $contact->getItem("phone")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, $prefix);
        echo $contact->getItem("mobile")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, $prefix);
        echo $contact->getItem("mail")->composeHtmlInput(FItem::INPUT_TEXT, 4, 8, $prefix);
        echo $contact->getItem("is_report")->composeHtmlInput(FItem::INPUT_CHECKBOX, 0, 12, $prefix);
        echo '<script>document.forms[0].elements["' . $prefix . 'is_report"].setAttribute("onclick", "showContactIcons(\'' . $prefix . '\')");</script>';

        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';  // accordion
    echo '</div>';  // panel
}

echo '</div>';  // row

//------------------------------------------------------------------------------
echo '<button type="submit" class="btn btn-sm btn-primary" name="submit">Guardar</button>&nbsp;';
echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/catalogs/view_entity.php?class=' . $entityClass . '" class="btn btn-sm btn-danger" role="button">Cancelar</a>';

echo '</form>';
echo '</div>';  // echo '<div class="container" style="margin-top:50px">';

echo FApp::composeFooter();
echo <<<SCRIPT
<script>
(function () {
    for (i = 1; i <= 10; i++) {
        document.forms[0].elements["contact_" + i + "_surname"].removeAttribute("required");
        document.forms[0].elements["contact_" + i + "_forename"].removeAttribute("required");
    }
})();

function switchInputFile() {
    if (document.getElementById("load_def_sampling_img").checked) {
        document.getElementById("entity_sampling_img_def_file").removeAttribute("disabled");
    }
    else {
        document.getElementById("entity_sampling_img_def_file").setAttribute("disabled", "");
    }
}

function showContactIcons(id_prefix) {
    var enableIconOk = document.forms[0].elements[id_prefix + "apply"].checked;
    var enableIconFile = document.forms[0].elements[id_prefix + "is_report"].checked;

    document.getElementById(id_prefix + "icon_ok").className = enableIconOk ? "glyphicon glyphicon-ok-sign small" : "";
    document.getElementById(id_prefix + "icon_file").className = enableIconOk && enableIconFile ? "glyphicon glyphicon-file small" : "";
}

function validateForm() {
    for (i = 1; i <= 10; i++) {
        if (document.forms[0].elements["contact_" + i + "_apply"].checked) {
            if (document.forms[0].elements["contact_" + i + "_surname"].value == "") {
                alert("Se debe especificar un valor para 'apellido(s)' de '" + document.getElementById("contact_" + i + "_panel").innerHTML + "'.");
                return false;
            }
            if (document.forms[0].elements["contact_" + i + "_forename"].value == "") {
                alert("Se debe especificar un valor para 'nombre(s)' de '" + document.getElementById("contact_" + i + "_panel").innerHTML + "'.");
                return false;
            }
        }
    }
    return checkBeforeSubmit(); // prevent multiple form submitions
}
</script>
SCRIPT;
echo '</body>';
echo '</html>';
