<?php
namespace Fraphe\Model;

use Fraphe\App\FUserSession;

abstract class FModel
{
    public static function save(FUserSession $userSession, FRegistry $registry)
    {
        try {
            $userSession->getPdo()->beginTransaction();
            $registry->save($userSession);
            $userSession->getPdo()->commit();
        }
        catch (\PDOException $exception) {
            if ($userSession->getPdo()->inTransaction()) {
                $userSession->getPdo()->rollBack();
            }
            throw $exception;   // re-throw exception to be catched by caller
        }
    }
}
