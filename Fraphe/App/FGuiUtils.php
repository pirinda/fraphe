<?php
namespace Fraphe\App;

abstract class FGuiUtils
{
    public function getFeature(string $featureId): FGuiFeature
    {
        $feature = new FGuiFeature("", "", "", array());

        $name = $_SESSION[FApp::ROOT_DIR] . FApp::CFG_FILE_MENU;
        $file = fopen($name, "r") or die("Unable to open file " . $name . "!");
        $json = json_decode(fread($file, filesize($name)), true);

        // loop through features:
        foreach ($json as $featKey => $featVal) {
            if ($featureId == $featKey) {
                $featureName;
                $featureDescrip;
                $menus = array();

                // loop through feature attributes:
                foreach ($featVal as $featAttKey => $featAttVal) {
                    switch ($featAttKey) {
                        case "feature":
                            $featureName = $featAttVal;
                            break;

                        case "descrip":
                            $featureDescrip = $featAttVal;
                            break;

                        case "menus":
                            if (is_array($featAttVal)) {
                                // loop through feature menus:
                                foreach ($featAttVal as $menuKey => $menuVal) {
                                    $menuId = $menuKey;
                                    $menuName;
                                    $menuDescrip;
                                    $submenus = array();

                                    if (is_array($menuVal)) {
                                        // loop through current menu attributes:
                                        foreach ($menuVal as $menuAttKey => $menuAttVal) {
                                            switch ($menuAttKey) {
                                                case "menu":
                                                    $menuName = $menuAttVal;
                                                    break;

                                                case "descrip":
                                                    $menuDescrip = $menuAttVal;
                                                    break;

                                                case "submenus":
                                                    if (is_array($menuAttVal)) {
                                                        // loop through menu submenus:
                                                        foreach ($menuAttVal as $submenuKey => $submenuVal) {
                                                            $submenuId = $submenuKey;
                                                            $submenuName;
                                                            $submenuDescrip;
                                                            $submenuHref;

                                                            if (is_array($submenuVal)) {
                                                                // loop through current submenu attributes:
                                                                foreach ($submenuVal as $submenuAttKey => $submenuAttVal) {
                                                                    switch ($submenuAttKey) {
                                                                        case "submenu":
                                                                            $submenuName = $submenuAttVal;
                                                                            break;

                                                                        case "descrip":
                                                                            $submenuDescrip = $submenuAttVal;
                                                                            break;

                                                                        case "href":
                                                                            $submenuHref = $submenuAttVal;
                                                                            break;

                                                                        default:
                                                                            throw new Exception("Unknown feature/menu/submenue attribute '$submenuAttKey'!");
                                                                    }
                                                                }

                                                                $submenus[] = new FGuiSubmenu($submenuId, $submenuName, $submenuDescrip, $submenuHref);
                                                            }
                                                        }
                                                    }
                                                    break;

                                                default:
                                                    throw new Exception("Unknown feature/menu attribute '$menuAttKey'!");
                                            }
                                        }

                                        $menus[] = new FGuiMenu($menuId, $menuName, $menuDescrip, $submenus);
                                    }
                                }
                            }
                            break;

                        default:
                            throw new Exception("Unknown feature attribute '$featAttKey'!");
                    }
                }

                $feature = new FGuiFeature($featureId, $featureName, $featureDescrip, $menus);
                break;
            }
        }

        return $feature;
    }
}
