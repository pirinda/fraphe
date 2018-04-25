<?php
namespace Fraphe\App;

abstract class FAppNavbar
{
    public static function compose(): string
    {
        $option = "";
        if (isset($_GET[FApp::OPT])) {
            $option = $_GET[FApp::OPT];
        }

        if (!FApp::isUserSessionActive()) {
            $html = '<nav class="navbar navbar-default navbar-fixed-top">';
        } else {
            $html = '<nav class="navbar navbar-inverse navbar-fixed-top">';
        }
        $html .= '<div class="container-fluid">';
        $html .= '<div class="navbar-header">';
        $html .= '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">';
        $html .= '<span class="icon-bar"></span>';
        $html .= '<span class="icon-bar"></span>';
        $html .= '<span class="icon-bar"></span> ';
        $html .= '</button>';
        $html .= '<a class="navbar-brand" href="' . $_SERVER['PHP_SELF'] . '">' . $_SESSION[FApp::APP_NAME] . '</a>';
        $html .= '</div>';
        $html .= '<div class="collapse navbar-collapse" id="myNavbar">';
        $html .= '<ul class="nav navbar-nav">';
        $html .= '<li' . ($option == FApp::OPT_HOME || $option == '' ? ' class="active"' : '') . '><a href="' . $_SERVER['PHP_SELF'] . '?' . FApp::OPT . '=' . FApp::OPT_HOME . '">Inicio</a></li>';
        if (!FApp::isUserSessionActive()) {
            $html .= '<li' . ($option == FApp::OPT_FEAT ? ' class="active"' : '') . '><a href="' . $_SERVER['PHP_SELF'] . '?' . FApp::OPT . '=' . FApp::OPT_FEAT . '">Prestaciones</a></li>';
        } else {
            $feature = FGuiUtils::getFeature($option);
            $len = count($feature->getMenus());
            for ($i = 0; $i < $len; $i++) {
                $html .= '<li><a href="#">' . $feature->getMenus()[$i]->getName() . '</a></li>';
            }
        }
        $html .= '<li' . ($option == FApp::OPT_HELP ? ' class="active"' : '') . '><a href="' . $_SERVER['PHP_SELF'] . '?' . FApp::OPT . '=' . FApp::OPT_HELP . '">Ayuda</a></li> ';
        $html .= '</ul>';
        $html .= '<ul class="nav navbar-nav navbar-right">';
        if (!FApp::isUserSessionActive()) {
            $html .= '<li><a href="' . $_SESSION[FApp::ROOT_DIR_WEB] . 'Fraphe/Lib/login.php"><span class="glyphicon glyphicon-log-in"></span>&nbsp;Iniciar</a></li>';
        } else {
            $html .= '<li><p class="navbar-text">' . $_SESSION[FApp::USER_NAME] . '</p></li>';
            $html .= '<li><a href="' . $_SESSION[FApp::ROOT_DIR_WEB] . 'Fraphe/Lib/logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Salir</a></li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</nav>';

        return $html;
    }
}
