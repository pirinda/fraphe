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
use Fraphe\App\FGuiUtils;
use Fraphe\Model\FRegistry;
use app\models\catalogs\ModUser;

$loginMsg = "";
$userId = ModUser::getUserId(FGuiUtils::createPdo(), $_POST['username']);

if (empty($userId)) {
    $loginMsg = "Nombre o contraseña de usuario incorrectos. <small>(FC01021)</small>";
}
else {
    $_SESSION[FAppConsts::USER_ID] = $userId;
    $_SESSION[FAppConsts::USER_NAME] = $_POST['username'];
    $_SESSION[FAppConsts::USER_LOGIN_TS] = new \DateTime();
    $_SESSION[FAppConsts::USER_LOGIN_MSG] = "";

    $userSession = FGuiUtils::createUserSession();

    $user = new ModUser();
    $user->read($userSession, $userId, FRegistry::MODE_READ);

    if ($user->getDatum("is_deleted")) {
        $loginMsg = "Nombre o contraseña de usuario incorrectos. <small>(FC01022)</small>";
    }
    else if ($user->getDatum("user_pswd") !== $_POST['userpswd']) {
        $loginMsg = "Nombre o contraseña de usuario incorrectos. <small>(FC01023)</small>";
    }
    else {
        $_SESSION[FAppConsts::USER_TYPE] = $user->getDatum("fk_user_type");

        $roles = array();
        foreach ($user->getChildUserUserRoles() as $role) {
            $roles[] = $role->getDatum("id_user_role");
        }
        $_SESSION[FAppConsts::USER_ROLES] = $roles;
    }
}

if (empty($loginMsg)) {
    FApp::goHome();
}
else {
    $_SESSION[FAppConsts::USER_ID] = null;
    $_SESSION[FAppConsts::USER_NAME] = null;
    $_SESSION[FAppConsts::USER_TYPE] = null;
    $_SESSION[FAppConsts::USER_ROLES] = null;
    $_SESSION[FAppConsts::USER_LOGIN_TS] = null;
    $_SESSION[FAppConsts::USER_LOGIN_MSG] = $loginMsg;
    header("Location: login.php");
}
