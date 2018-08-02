<?php
namespace app\models\catalogs;

use Fraphe\App\FUserSession;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use Fraphe\Model\FRelation;
use app\AppConsts;

class ModEntityEntityType extends FRelation
{
    public const PREFIX = "entity_type_";

    protected $id_entity;
    protected $id_entity_type;

    function __construct()
    {
        parent::__construct(AppConsts::CC_ENTITY_ENTITY_TYPE);

        $this->id_entity = new FItem(FItem::DATA_TYPE_INT, "id_entity", "ID entidad", "", false, true);
        $this->id_entity_type = new FItem(FItem::DATA_TYPE_INT, "id_entity_type", "ID tipo entidad", "", false, true);

        $this->items["id_entity"] = $this->id_entity;
        $this->items["id_entity_type"] = $this->id_entity_type;

        // create relation IDs:
        $this->ids["id_entity"] = 0;
        $this->ids["id_entity_type"] = 0;
    }

    public function retrieve(FUserSession $userSession, array $ids, int $mode)
    {
        $this->initialize();
        $this->setIds($ids);

        // copy relation IDs to simplify query:
        $id_entity = $this->ids["id_entity"];
        $id_entity_type = $this->ids["id_entity_type"];

        $sql = "SELECT * FROM cc_entity_entity_type WHERE id_entity = $id_entity AND id_entity_type = $id_entity_type;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id_entity->setValue($row["id_entity"]);
            $this->id_entity_type->setValue($row["id_entity_type"]);

            $this->isRegistryNew = false;
            $this->mode = $mode;
        }
        else {
            throw new \Exception(__METHOD__ . ": " . FRegistry::ERR_MSG_REGISTRY_NOT_FOUND);
        }
    }

    public function save(FUserSession $userSession)
    {
        $this->validate($userSession);

        $statement;

        if ($this->isRegistryNew) {
            $statement = $userSession->getPdo()->prepare("INSERT INTO cc_entity_entity_type (" .
                "id_entity, " .
                "id_entity_type) " .
                "VALUES (" .
                ":id_entity, " .
                ":id_entity_type);");
        }
        else {
            throw new \Exception(__METHOD__ . ": " . FRegistry::ERR_MSG_REGISTRY_NON_UPDATABLE);
        }

        $id_entity = $this->ids["id_entity"];
        $id_entity_type = $this->ids["id_entity_type"];

        $statement->bindParam(":id_entity", $id_entity, \PDO::PARAM_INT);
        $statement->bindParam(":id_entity_type", $id_entity_type, \PDO::PARAM_INT);

        $statement->execute();

        $this->isRegistryModified = false;
        if ($this->isRegistryNew) {
            $this->isRegistryNew = false;
        }
    }

    public function delete(FUserSession $userSession)
    {

    }

    public function undelete(FUserSession $userSession)
    {

    }
}
