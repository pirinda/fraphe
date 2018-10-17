<?php
namespace app\models\catalogs;

use Fraphe\App\FUserSession;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use Fraphe\Model\FRelation;
use app\AppConsts;

class ModUserProcessArea extends FRelation
{
    public const PREFIX = "user_process_area_";

    protected $id_user;
    protected $id_process_area;

    function __construct()
    {
        parent::__construct(AppConsts::CC_USER_PROCESS_AREA, AppConsts::$tables[AppConsts::CC_USER_PROCESS_AREA]);

        $this->id_user = new FItem(FItem::DATA_TYPE_INT, "id_user", "", "", false, true);
        $this->id_process_area = new FItem(FItem::DATA_TYPE_INT, "id_process_area", "", "", false, true);

        $this->items["id_user"] = $this->id_user;
        $this->items["id_process_area"] = $this->id_process_area;

        // create relation IDs:
        $this->ids["id_user"] = 0;
        $this->ids["id_process_area"] = 0;
    }

    public function retrieve(FUserSession $userSession, array $ids, int $mode)
    {
        $this->initialize();

        // copy relation IDs to simplify query:
        $id_user = $ids["id_user"];
        $id_process_area = $ids["id_process_area"];

        $sql = "SELECT * FROM $this->tableName WHERE id_user = $id_user AND id_process_area = $id_process_area;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id_user->setValue($row["id_user"]);
            $this->id_process_area->setValue($row["id_process_area"]);

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
            $statement = $userSession->getPdo()->prepare("INSERT INTO $this->tableName (" .
                "id_user, " .
                "id_process_area) " .
                "VALUES (" .
                ":id_user, " .
                ":id_process_area);");
        }
        else {
            throw new \Exception(__METHOD__ . ": " . FRegistry::ERR_MSG_REGISTRY_NON_UPDATABLE);
        }

        $id_user = $this->id_user->getValue();
        $id_process_area = $this->id_process_area->getValue();

        $statement->bindParam(":id_user", $id_user, \PDO::PARAM_INT);
        $statement->bindParam(":id_process_area", $id_process_area, \PDO::PARAM_INT);

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
