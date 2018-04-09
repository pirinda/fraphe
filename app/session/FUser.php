<?php
namespace Fraphe\Session;

class FUser
{
    private $userId;
    private $userName;

    function __construct($userId, $userName)
    {
        $this->userId = $userId;
        $this->userName = $userName;
    }

    public function getId()
    {
        return $this->userId;
    }

    public function getName()
    {
        return $this->userName;
    }
}
