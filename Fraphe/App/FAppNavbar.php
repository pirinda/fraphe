<?php
namespace Fraphe\App;

abstract class FAppNavbar
{
    private static function composeNavbarHeader(): string
    {
        $html = '<div class="navbar-header">';
        $html .= '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">';
        $html .= '<span class="icon-bar"></span>';
        $html .= '<span class="icon-bar"></span>';
        $html .= '<span class="icon-bar"></span> ';
        $html .= '</button>';
        $html .= '<a class="navbar-brand" href="' . $_SERVER['PHP_SELF'] . '">' . $_SESSION[FAppConsts::APP_NAME] . '</a>';
        $html .= '</div>';

        return $html;
    }

    private static function composeSessionInactive(): string
    {
        $curPage = "";

        if (isset($_GET[FAppConsts::TAG_PAGE])) {
            $curPage = $_GET[FAppConsts::TAG_PAGE];
        }

        // navbar:
        //$html = '<nav class="navbar navbar-default navbar-fixed-top">';
        $html = '<nav class="navbar navbar-default">';

        // navbar header:
        $html .= '<div class="container-fluid">';
        $html .= self::composeNavbarHeader();
        $html .= '<div class="collapse navbar-collapse" id="myNavbar">';

        // navbar options:
        $html .= '<ul class="nav navbar-nav">';
        $html .= '<li' . ($curPage == FAppConsts::PAGE_HOME || $curPage == '' ? ' class="active"' : '') . '><a href="' . $_SERVER['PHP_SELF'] . '?' . FAppConsts::TAG_PAGE . '=' . FAppConsts::PAGE_HOME . '">Inicio</a></li>';
        $html .= '<li' . ($curPage == FAppConsts::PAGE_FEAT ? ' class="active"' : '') . '><a href="' . $_SERVER['PHP_SELF'] . '?' . FAppConsts::TAG_PAGE . '=' . FAppConsts::PAGE_FEAT . '">Prestaciones</a></li>';
        $html .= '<li' . ($curPage == FAppConsts::PAGE_HELP ? ' class="active"' : '') . '><a href="' . $_SERVER['PHP_SELF'] . '?' . FAppConsts::TAG_PAGE . '=' . FAppConsts::PAGE_HELP . '">Ayuda</a></li> ';
        $html .= '</ul>';

        // user login:
        $html .= '<ul class="nav navbar-nav navbar-right">';
        $html .= '<li><a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'Fraphe/Lib/login.php"><span class="glyphicon glyphicon-log-in"></span>&nbsp;Iniciar</a></li>';
        $html .= '</ul>';

        $html .= '</div>';
        $html .= '</div>';

        $html .= '</nav>';

        return $html;
    }

    public static function composeSessionActive(string $optModuleId = ""): string
    {
        $moduleId = $optModuleId;
        $module;

        if ($moduleId === "" && isset($_GET[FAppConsts::TAG_MOD])) {
            $moduleId = $_GET[FAppConsts::TAG_MOD];
        }

        $module = FGuiUtils::getModule($moduleId);

        // navbar:
        //$html = '<nav class="navbar navbar-inverse navbar-fixed-top">';
        $html = '<nav class="navbar navbar-inverse">';

        // navbar header:
        $html .= '<div class="container-fluid">';
        $html .= self::composeNavbarHeader();
        $html .= '<div class="collapse navbar-collapse" id="myNavbar">';

        // navbar options:
        $html .= '<ul class="nav navbar-nav">';
        $html .= '<li' . ($moduleId == FAppConsts::PAGE_HOME || $moduleId == '' ? ' class="active"' : '') . '><a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . '">Inicio</a></li>';

        // menu for active session:
        if (isset($module)) {
            foreach ($module->getMenus() as $menu) {
                $html .= '<li class="dropdown">';
                $html .= '<a class="dropdown-toggle" data-toggle="dropdown" href="#">' . $menu->getName() . '<span class="caret"></span></a>';
                $html .= '<ul class="dropdown-menu">';

                foreach ($menu->getSubmenus() as $submenu) {
                    $html .= '<li><a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . $submenu->getHref() . '">' . $submenu->getName() . '</a></li>';
                }

                $html .= '</ul>';
                $html .= '</li>';
            }
        }

        $html .= '</ul>';

        // user logout:
        $html .= '<ul class="nav navbar-nav navbar-right">';
        $html .= '<li><p class="navbar-text">' . $_SESSION[FAppConsts::USER_NAME] . '</p></li>';
        $html .= '<li><a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'Fraphe/Lib/logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Salir</a></li>';
        $html .= '</ul>';

        $html .= '</div>';
        $html .= '</div>';

        $html .= '</nav>';

        return $html;
    }

    public static function compose(string $optModuleId = ""): string
    {
        $html;

        if (!FApp::isUserSessionActive()) {
            // session is inactive
            $html = self::composeSessionInactive();
        }
        else {
            // session is active
            $html = self::composeSessionActive($optModuleId);
        }

        return $html;
    }
}
