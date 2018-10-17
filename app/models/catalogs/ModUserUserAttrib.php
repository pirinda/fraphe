<?php
namespace app\models\catalogs;

use Fraphe\App\FUserSession;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use Fraphe\Model\FRelation;
use app\AppConsts;

class ModUserUserAttrib extends FRelation
{
    public const PREFIX = "user_user_attrib_";

    protected $id_user;
    protected $id_user_attrib;

    function __construct()
    {
        parent::__construct(AppConsts::CC_USER_USER_ATTRIB, AppConsts::$tables[AppConsts::CC_USER_USER_ATTRIB]);

        $this->id_user = new FItem(FItem::DATA_TYPE_INT, "id_user", "", "", false, true);
        $this->id_user_attrib = new FItem(FItem::DATA_TYPE_INT, "id_user_attrib", "", "", false, true);

        $this->items["id_user"] = $this->id_user;
        $this->items["id_user_attrib"] = $this->id_user_attrib;

        // create relation IDs:
        $this->ids["id_user"] = 0;
        $this->ids["id_user_attrib"] = 0;
    }

    public function retrieve(FUserSession $userSession, array $ids, int $mode)
    {
        $this->initialize();

        // copy relation IDs to simplify query:
        $id_user = $ids["id_user"];
        $id_user_attrib = $ids["id_user_attrib"];

        $sql = "SELECT * FROM $this->tableName WHERE id_user = $id_user AND id_user_attrib = $id_user_attrib;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id_user->setValue($row["id_user"]);
            $this->id_user_attrib->setValue($row["id_user_attrib"]);

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
                "id_user_attrib) " .
                "VALUES (" .
                ":id_user, " .
                ":id_user_attrib);");
        }
        else {
            throw new \Exception(__METHOD__ . ": " . FRegistry::ERR_MSG_REGISTRY_NON_UPDATABLE);
        }

        $id_user = $this->id_user->getValue();
        $id_user_attrib = $this->id_user_attrib->getValue();

        $statement->bindParam(":id_user", $id_user, \PDO::PARAM_INT);
        $statement->bindParam(":id_user_attrib", $id_user_attrib, \PDO::PARAM_INT);

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
