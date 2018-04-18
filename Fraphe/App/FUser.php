<?php
namespace Fraphe\App;

class FUser
{
    private $userId;
    private $userName;

    public function __construct(int $userId, string $userName)
    {
        $this->userId = $userId;
        $this->userName = $userName;
    }

    public function getId(): int
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->userName;
    }
}
