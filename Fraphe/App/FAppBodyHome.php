<?php
namespace Fraphe\App;

use app\models\ModConsts; // TODO Move FAppBodyHome class to the implementation of Fraphe framework, or remove this from Fraphe framework!

abstract class FAppBodyHome
{
    public static function compose(): string
    {
        $html = "";

        if (!FApp::isUserSessionActive()) {
            // session is inactive
            $curPage = "";

            if (isset($_GET[FAppConsts::TAG_PAGE])) {
                $curPage = $_GET[FAppConsts::TAG_PAGE];
            }

            switch ($curPage) {
                case FAppConsts::PAGE_FEAT:
                    $html .= '<div class="container" style="margin-top:50px">';
                    $html .= '  <h2>Prestaciones</h2>';
                    $html .= '  <img class="img-responsive" src="app/images/pexels-photo-256262.jpeg" alt="Laboratory">';
                    //$html .= '  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
                    //$html .= '  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
                    $html .= '</div>';
                    break;

                case FAppConsts::PAGE_HELP:
                    $html .= '<div class="container" style="margin-top:50px">';
                    $html .= '  <h2>Ayuda</h2>';
                    $html .= '  <img class="img-responsive" src="app/images/pexels-photo-355988.jpeg" alt="Laboratory">';
                    //$html .= '  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
                    //$html .= '  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
                    //$html .= '  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
                    $html .= '</div>';
                    break;

                case FAppConsts::PAGE_HOME:
                    // falls through...

                default:
                    $html .= '<div class="container" style="margin-top:50px">';
                    $html .= '  <h2>' . $_SESSION[FAppConsts::APP_VENDOR] . '</h2>';
                    $html .= '  <img class="img-responsive" src="app/images/pexels-photo-1366942.jpeg" alt="Laboratory">';
                    //$html .= '  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
                    $html .= '</div>';
            }
        }
        else {
            // session is active
            $curModule = "";

            if (isset($_GET[FAppConsts::TAG_MOD])) {
                $curModule = $_GET[FAppConsts::TAG_MOD];
            }

            if ($curModule == FAppConsts::PAGE_HOME || $curModule == '') {
                // session is active
                $html .= '<div class="container text-center" style="margin-top:60px">';
                $html .= '  <div class="row">';

                $roles = array();
                $roles[] = ModConsts::CC_USER_ROLE_RECEPT;
                $roles[] = ModConsts::CC_USER_ROLE_DIRECTION;
                $hasRole = FApp::hasUserSessionUserRoles($roles);
                $module = FGuiUtils::getModule("recept");
                $html .= '    <div class="col-sm-4">';
                $html .= !$hasRole ? '' : '      <a href="' . $module->getHref() . '">';
                $html .= '      <h1><span class="glyphicon glyphicon-bell"></span></h1>';
                $html .= '      <h4>RECEPCIÓN</h4>';
                $html .= !$hasRole ? '' : '      </a>';
                $html .= '      <p>Recepción de muestras. Generación de órdenes de trabajo.</p>';
                $html .= '    </div>';

                $roles = array();
                $roles[] = ModConsts::CC_USER_ROLE_PROCESS;
                $roles[] = ModConsts::CC_USER_ROLE_DIRECTION;
                $hasRole = FApp::hasUserSessionUserRoles($roles);
                $module = FGuiUtils::getModule("process");
                $html .= '    <div class="col-sm-4">';
                $html .= !$hasRole ? '' : '      <a href="' . $module->getHref() . '">';
                $html .= '      <h1><span class="glyphicon glyphicon-screenshot"></span></h1>';
                $html .= '      <h4>PROCESO</h4>';
                $html .= !$hasRole ? '' : '      </a>';
                $html .= '      <p>Procesamiento de órdenes de trabajo.</p>';
                $html .= '    </div>';

                $roles = array();
                $roles[] = ModConsts::CC_USER_ROLE_REPORT;
                $roles[] = ModConsts::CC_USER_ROLE_DIRECTION;
                $hasRole = FApp::hasUserSessionUserRoles($roles);
                $module = FGuiUtils::getModule("report");
                $html .= '    <div class="col-sm-4">';
                $html .= !$hasRole ? '' : '      <a href="' . $module->getHref() . '">';
                $html .= '      <h1><span class="glyphicon glyphicon-file"></span></h1>';
                $html .= '      <h4>RESULTADOS</h4>';
                $html .= !$hasRole ? '' : '      </a>';
                $html .= '      <p>Verificación, validación y liberación de informes de resultados.</p>';
                $html .= '    </div>';

                $html .= '  </div>';

                $html .= '  <div class="row">';

                $roles = array();
                $roles[] = ModConsts::CC_USER_ROLE_MARKETING;
                $roles[] = ModConsts::CC_USER_ROLE_DIRECTION;
                $hasRole = FApp::hasUserSessionUserRoles($roles);
                $module = FGuiUtils::getModule("queries");
                $html .= '    <div class="col-sm-4">';
                $html .= !$hasRole ? '' : '      <a href="' . $module->getHref() . '">';
                $html .= '      <h1><span class="glyphicon glyphicon-stats"></span></h1>';
                $html .= '      <h4>CONSULTAS</h4>';
                $html .= !$hasRole ? '' : '      </a>';
                $html .= '      <p>Generación de consultas, reportes y estadísticas.</p>';
                $html .= '    </div>';

                $roles = array();
                $roles[] = ModConsts::CC_USER_ROLE_RECEPT;
                $roles[] = ModConsts::CC_USER_ROLE_DIRECTION;
                $hasRole = FApp::hasUserSessionUserRoles($roles);
                $module = FGuiUtils::getModule("catalogs");
                $html .= '    <div class="col-sm-4">';
                $html .= !$hasRole ? '' : '      <a href="' . $module->getHref() . '">';
                $html .= '      <h1><span class="glyphicon glyphicon-th-list"></span></h1>';
                $html .= '      <h4>CATÁLOGOS</h4>';
                $html .= !$hasRole ? '' : '      </a>';
                $html .= '      <p>Gestión de catálogos de la aplicación.</p>';
                $html .= '    </div>';

                $html .= '    <div class="col-sm-4">';
                $html .= '      <h1><span class="glyphicon glyphicon-cog"></span></h1>';
                $html .= '      <h4>CONFIGURACIÓN</h4>';
                $html .= '      <p>Configuración general de la aplicación.</p>';
                $html .= '    </div>';

                $html .= '  </div>';
                $html .= '</div>';
            }
        }

        return $html;
    }
}
