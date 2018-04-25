<?php
namespace Fraphe\App;

class FGuiFeature
{
    protected $id;
    protected $name;
    protected $descrip;
    protected $menus;

    public function __construct(string $id, string $name, string $descrip, array $menus)
    {
        $this->id = $id;
        $this->name = $name;
        $this->descrip = $descrip;
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

    public function getDescrip(): string
    {
        return $this->descrip;
    }

    public function getMenus(): array
    {
        return $this->menus;
    }
}
