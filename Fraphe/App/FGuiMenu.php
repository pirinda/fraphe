<?php
namespace Fraphe\App;

class FGuiMenu
{
    protected $id;
    protected $name;
    protected $href;
    protected $submenus;

    public function __construct(string $id, string $name, string $href, array $submenus)
    {
        $this->id = $id;
        $this->name = $name;
        $this->href = $href;
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

    public function getHref(): string
    {
        return $this->href;
    }

    public function getSubmenus(): array
    {
        return $this->submenus;
    }
}
