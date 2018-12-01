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
use Fraphe\Lib\FDevUtils;
use Fraphe\Lib\FLibUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FModelUtils;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;
use app\models\ModConsts;
use app\models\catalogs\ModMarketSegment;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose("catalogs");

$userSession = FGuiUtils::createUserSession();
$marketSegment = new ModMarketSegment();
$errmsg = "";

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        // read registry:

        if (!empty($_GET[FRegistry::ID])) {
            $marketSegment->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
        }
        break;

    case "POST":
        // read registry:

        if (!empty($_POST[FRegistry::ID])) {
            $marketSegment->read($userSession, intval($_POST[FRegistry::ID]), FRegistry::MODE_WRITE);
        }

        // recover registry data:

        $data = array();

        $data["name"] = $_POST["name"];
        $data["sorting"] = $_POST["sorting"];
        //$data["is_system"] = $_POST["is_system"];
        //$data["is_deleted"] = $_POST["is_deleted"];

        try {
            $marketSegment->setData($data);

            FModelUtils::save($userSession, $marketSegment);

            header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/catalogs/view_market_segment.php");
        }
        catch (Exception $e) {
            $errmsg = $e->getMessage();
        }
        break;

    default:
}

echo '<div class="container" style="margin-top:50px">';
echo '<div class="page-header">';
echo '<h3>Segmento de mercado</h3>';
echo '</div>';

if (!empty($errmsg)) {
    echo '<div class="alert alert-danger alert-dismissible">';
    echo '<a href="#" class="close" data-dismiss="alert" aria-label="cerrar">&times;</a>';
    echo '<strong>¡Error de captura!</strong> ' . $errmsg;
    echo '</div>';
}

// market segment:

echo '<form class="form-horizontal" method="post" action="' . FLibUtils::sanitizeInput($_SERVER["PHP_SELF"]) . '" onsubmit="return validateForm();">';

// preserve entity class and nature and registry ID in post:
echo '<input type="hidden" name="' . FRegistry::ID . '" value="' . $marketSegment->getId() . '">';

echo $marketSegment->getItem("sorting")->composeHtmlInput(FItem::INPUT_NUMBER, 2, 2);
echo $marketSegment->getItem("name")->composeHtmlInput(FItem::INPUT_TEXT, 2, 4);

echo '<br><button type="submit" class="btn btn-sm btn-primary">Guardar</button>';
echo '&nbsp;<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'app/views/catalogs/view_market_segment.php" class="btn btn-sm btn-danger" role="button">Cancelar</a>';

echo '</form>';
echo '</div>';

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
