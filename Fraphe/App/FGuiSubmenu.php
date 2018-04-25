<?php
namespace Fraphe\App;

class FGuiSubmenu
{
    protected $id;
    protected $name;
    protected $descrip;
    protected $href;

    public function __construct(string $id, string $name, string $descrip, string $href)
    {
        $this->id = $id;
        $this->name = $name;
        $this->descrip = $descrip;
        $this->href = $href;
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

    public function getHref(): string
    {
        return $this->href;
    }
}
