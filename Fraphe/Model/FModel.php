<?php
namespace Fraphe\Model;

use Fraphe\App\FUserSession;

abstract class FModel
{
    public static function processRegistry(FUserSession $userSession, FRegistry $registry, array $data)
    {
        $registry->setData($data);
        $registry->save($userSession);
    }
}
