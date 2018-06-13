<?php
namespace Fraphe\App;

abstract class FGuiUtils
{
    /*
    */
    public static function decodeJson(string $filename): array
    {
        $file = fopen($filename, "r") or die("Unable to open file " . $filename . "!");
        return json_decode(fread($file, filesize($filename)), true);
    }

    /*
    */
    private static function digestModule(string $moduleId, array $jsonMenu): FGuiModule
    {
        $module = new FGuiModule("", "", "", array());

        // loop through features:
        foreach ($jsonMenu as $moduleKey => $moduleVal) {
            if ($moduleId == $moduleKey) {
                $moduleName;
                $moduleHref;
                $menus = array();

                // loop through feature attributes:
                foreach ($moduleVal as $moduleAttKey => $moduleAttVal) {
                    switch ($moduleAttKey) {
                        case FGuiModule::JSON_MOD:
                            $moduleName = $moduleAttVal;
                            break;

                        case FGuiModule::JSON_HREF:
                            $moduleHref = $moduleAttVal;
                            break;

                        case FGuiModule::JSON_MENUS:
                            if (is_array($moduleAttVal)) {
                                // loop through feature menus:
                                foreach ($moduleAttVal as $menuKey => $menuVal) {
                                    $menuId = $menuKey;
                                    $menuName;
                                    $menuHref;
                                    $submenus = array();

                                    if (is_array($menuVal)) {
                                        // loop through current menu attributes:
                                        foreach ($menuVal as $menuAttKey => $menuAttVal) {
                                            switch ($menuAttKey) {
                                                case FGuiModule::JSON_MENU:
                                                    $menuName = $menuAttVal;
                                                    break;

                                                case FGuiModule::JSON_HREF:
                                                    $menuHref = $menuAttVal;
                                                    break;

                                                case FGuiModule::JSON_SUBMENUS:
                                                    if (is_array($menuAttVal)) {
                                                        // loop through menu submenus:
                                                        foreach ($menuAttVal as $submenuKey => $submenuVal) {
                                                            $submenuId = $submenuKey;
                                                            $submenuName;
                                                            $submenuHref;

                                                            if (is_array($submenuVal)) {
                                                                // loop through current submenu attributes:
                                                                foreach ($submenuVal as $submenuAttKey => $submenuAttVal) {
                                                                    switch ($submenuAttKey) {
                                                                        case FGuiModule::JSON_SUBMENU:
                                                                            $submenuName = $submenuAttVal;
                                                                            break;

                                                                        case FGuiModule::JSON_HREF:
                                                                            $submenuHref = $submenuAttVal;
                                                                            break;

                                                                        default:
                                                                            throw new Exception("Unknown " . SGuiModule::JSON_MOD . "/" . SGuiModule::JSON_MENU . "/" . SGuiModule::JSON_SUBMENU . " attribute '$submenuAttKey'!");
                                                                    }
                                                                }

                                                                $submenus[$submenuId] = new FGuiSubmenu($submenuId, $submenuName, $submenuHref);
                                                            }
                                                        }
                                                    }
                                                    break;

                                                default:
                                                    throw new Exception("Unknown " . SGuiModule::JSON_MOD . "/" . SGuiModule::JSON_MENU . " attribute '$menuAttKey'!");
                                            }
                                        }

                                        $menus[$menuId] = new FGuiMenu($menuId, $menuName, $menuHref, $submenus);
                                    }
                                }
                            }
                            break;

                        default:
                            throw new Exception("Unknown " . SGuiModule::JSON_MOD . " attribute '$moduleAttKey'!");
                    }
                }

                $module = new FGuiModule($moduleId, $moduleName, $moduleHref, $menus);
                break;
            }
        }

        return $module;
    }

    /*
    */
    public static function getModule(string $moduleId): FGuiModule
    {
        // read and decode JSON file of application menu:
        $jsonMenu = self::decodeJson($_SESSION[FAppConsts::ROOT_DIR] . FAppConsts::CFG_FILE_MENU);

        return self::digestModule($moduleId, $jsonMenu);
    }

    /*
    */
    public static function echoException($e) {
        echo 'Error: ' . $e->getMessage();
    }

    /*
    */
    public static function composeConnectionDsn(): string
    {
        return "mysql:host=" . $_SESSION[FAppConsts::DB_HOST] . ";port=" . $_SESSION[FAppConsts::DB_PORT] . ";dbname=" . $_SESSION[FAppConsts::DB_NAME] . ";charset=UTF8";
    }

    /*
    */
    public static function createConnection(): \PDO
    {
        $connection = new \PDO(self::composeConnectionDsn(), $_SESSION[FAppConsts::DB_USER_NAME], $_SESSION[FAppConsts::DB_USER_PSWD]);
        $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $connection;
    }
}
