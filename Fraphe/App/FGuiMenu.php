<?php
namespace Fraphe\App;

class FGuiMenu
{
    protected $id;
    protected $name;
    protected $descrip;
    protected $submenus;

    public function __construct(string $id, string $name, string $descrip, array $submenus)
    {
        $this->id = $id;
        $this->name = $name;
        $this->descrip = $descrip;
        $this->submenus = $submenus;
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

    public function getSubmenus(): array
    {
        return $this->submenus;
    }
}
