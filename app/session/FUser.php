<?php
namespace Fraphe\Session;

class FUser
{
    private $userName;

    function __construct($userName)
    {
        $this->userName = $userName;
    }

    public function getName()
    {
        return $this->userName;
    }
}
