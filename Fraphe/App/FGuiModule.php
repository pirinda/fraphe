<?php
namespace Fraphe\App;

class FGuiModule
{
    const JSON_HREF = "href";
    const JSON_MOD = "module";
    const JSON_MENU = "menu";
    const JSON_MENUS = "menus";
    const JSON_SUBMENU = "submenu";
    const JSON_SUBMENUS = "submenus";

    protected $id;
    protected $name;
    protected $href;
    protected $menus;

    public function __construct(string $id, string $name, string $href, array $menus)
    {
        $this->id = $id;
        $this->name = $name;
        $this->href = $href;
        $this->menus = $menus;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function getMenus(): array
    {
        return $this->menus;
    }

    public function getMenu(string $menuId): FGuiMenu
    {
        $menu;

        foreach ($this->menus as $m) {
            if ($m->getId() == $menuId) {
                $menu = $m;
                break;
            }
        }

        return $menu;
    }
}
