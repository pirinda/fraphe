<?php
namespace Fraphe\App;

class FGuiSubmenu
{
    protected $id;
    protected $name;
    protected $href;

    public function __construct(string $id, string $name, string $href)
    {
        $this->id = $id;
        $this->name = $name;
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

    public function getHref(): string
    {
        return $this->href;
    }
}
