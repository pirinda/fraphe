<?php
namespace Fraphe\App;

abstract class FAppNavbar
{
    public static function composeNav(): string
    {
        $html = '<nav class="navbar navbar-inverse navbar-fixed-top">';
        $html .= '<div class="container-fluid">';
        $html .= '<div class="navbar-header">';
        $html .= '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">';
        $html .= '<span class="icon-bar"></span>';
        $html .= '<span class="icon-bar"></span>';
        $html .= '<span class="icon-bar"></span> ';
        $html .= '</button>';
        $html .= '<a class="navbar-brand" href="#">' . $_SESSION[FApp::ATT_APP_NAME] . '</a>';
        $html .= '</div>';
        $html .= '<div class="collapse navbar-collapse" id="myNavbar">';
        $html .= '<ul class="nav navbar-nav">';
        $html .= '<li class="active"><a href="#">Inicio</a></li>';
        $html .= '<li><a href="#">Presentación</a></li>';
        $html .= '<li><a href="#">Funcionalidaes</a></li> ';
        $html .= '<li><a href="#">Ayuda</a></li> ';
        $html .= '</ul>';
        $html .= '<ul class="nav navbar-nav navbar-right">';
        if (!FApp::isUserSessionActive()) {
            $html .= '<li><a href="Fraphe/Lib/login.php"><span class="glyphicon glyphicon-log-in"></span>&nbsp;Iniciar</a></li>';
        } else {
            $html .= '<li><p class="navbar-text">' . $_SESSION[FApp::ATT_USER_SESSION]->getCurUser()->getName() . '</p></li>';
            $html .= '<li><a href="Fraphe/Lib/logout.php"><span class="glyphicon glyphicon-log-in"></span>&nbsp;Salir</a></li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</nav>';

        return $html;
    }
}
