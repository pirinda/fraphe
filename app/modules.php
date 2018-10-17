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

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose();

$moduleId = "";
$module;

if (isset( $_GET[FAppConsts::TAG_MOD])) {
    $moduleId = $_GET[FAppConsts::TAG_MOD];
    $module = FGuiUtils::getModule($moduleId);

    $menuId = "";
    $menu;
    $showSidenav = false;

    if (isset($_GET[FAppConsts::TAG_MENU])) {
        $menuId = $_GET[FAppConsts::TAG_MENU];
        $menu = $module->getMenu($menuId);
        $showSidenav = true;

        echo '<div class="sidenav" style="margin-top:50px">';
        echo '  <p>' . $menu->getName() . '</p>';
        foreach ($menu->getSubmenus() as $submenu) {
            echo '  <a href="' . $submenu->getHref() . '">' . $submenu->getName() . '</a>';
        }
        echo '</div>';
    }

    echo '<div class="' . ($showSidenav ? 'myworkspace ' : '') . 'container" style="margin-top:50px">';
    echo '  <h1>' . $module->getName() . '</h1>';
    //echo '  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
    //echo '  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
    echo '</div>';
}

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
