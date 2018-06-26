<?php
namespace app\models\catalogs;

use Fraphe\App\FUserSession;
use Fraphe\Model\FRelation;
use app\AppConsts;

class ModEntity extends FRelation
{
    function __construct(\PDO $connection)
    {
        parent::__construct($connection, AppConsts::CC_ENTITY_ENTITY_TYPE);

        $this->ids["id_entity"] = 0;
        $this->ids["id_entity_type"] = 0;
    }

    public function save(FUserSession $session)
    {
        $statement = $this->connection->prepare("INSERT INTO cc_entity_entity_type (" .
            "id_entity, " .
            "id_entity_type) " .
            "VALUES (" .
            ":id_entity, " .
            ":id_entity_type);");

        $id_entity = $this->ids["id_entity"];
        $id_entity_type = $this->ids["id_entity_type"];

        $statement->bindParam(":id_entity", $id_entity);
        $statement->bindParam(":id_entity_type", $id_entity_type);

        $statement->execute();
    }
}
