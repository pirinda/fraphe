<?php
namespace Fraphe\App;

abstract class FAppBody
{
    public static function compose(): string
    {
        $html = "";
        $option = "";
        if (isset($_GET[FApp::OPT])) {
            $option = $_GET[FApp::OPT];
        }

        switch ($option) {
            case FApp::OPT_FEAT:
                $html .= '<div class="container" style="margin-top:50px">';
                $html .= '  <h1>Prestaciones</h1>';
                $html .= '  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
                $html .= '  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
                $html .= '</div>';
                break;

            case FApp::OPT_HELP:
                $html .= '<div class="container" style="margin-top:50px">';
                $html .= '  <h1>Ayuda</h1>';
                $html .= '  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
                $html .= '  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
                $html .= '  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
                $html .= '</div>';
                break;

            default:
                if (!FApp::isUserSessionActive()) {
                    $html .= '<div class="container" style="margin-top:50px">';
                    $html .= '  <h1>Inicio</h1>';
                    $html .= '  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
                    $html .= '</div>';
                }
                else {
                    $html .= '<div class="container text-center" style="margin-top:60px">';
                    $html .= '  <div class="row">';
                    $html .= '    <div class="col-sm-4">';
                    $html .= '      <span class="glyphicon glyphicon-bell"></span>';
                    $html .= '      <h4>RECEPCIÓN</h4>';
                    $html .= '      <p>Recepción de muestras, cadenas de custodia y solicitudes de ensayos. Generación de órdenes de trabajo.</p>';
                    $html .= '    </div>';
                    $html .= '    <div class="col-sm-4">';
                    $html .= '      <span class="glyphicon glyphicon-screenshot"></span>';
                    $html .= '      <h4>PROCESO</h4>';
                    $html .= '      <p>Procesamiento de órdenes de trabajo.</p>';
                    $html .= '    </div>';
                    $html .= '    <div class="col-sm-4">';
                    $html .= '      <span class="glyphicon glyphicon-print"></span>';
                    $html .= '      <h4>RESULTADOS</h4>';
                    $html .= '      <p>Verificación, validación y liberación de informes de resultados.</p>';
                    $html .= '    </div>';
                    $html .= '  </div>';
                    $html .= '  <div class="row">';
                    $html .= '    <div class="col-sm-4">';
                    $html .= '      <span class="glyphicon glyphicon-stats"></span>';
                    $html .= '      <h4>ESTADÍSTICAS</h4>';
                    $html .= '      <p>Generación de consultas, reportes y estadísticas.</p>';
                    $html .= '    </div>';
                    $html .= '    <div class="col-sm-4">';
                    $html .= '      <a href="' . $_SERVER['PHP_SELF'] . '?' . FApp::OPT . '=cats">';
                    $html .= '      <span class="glyphicon glyphicon-th-list"></span>';
                    $html .= '      <h4>CATÁLOGOS</h4>';
                    $html .= '      </a>';
                    $html .= '      <p>Gestión de catálogos de usuario.</p>';
                    $html .= '    </div>';
                    $html .= '    <div class="col-sm-4">';
                    $html .= '      <span class="glyphicon glyphicon-cog"></span>';
                    $html .= '      <h4>CONFIGURACIÓN</h4>';
                    $html .= '      <p>Configuración general de la aplicación.</p>';
                    $html .= '    </div>';
                    $html .= '  </div>';
                    $html .= '</div>';
                }
                break;
        }

        return $html;
    }
}
