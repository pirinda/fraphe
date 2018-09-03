<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\App\FGuiUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class ModProcessArea extends FRegistry
{
    protected $id_process_area;
    protected $name;
    protected $code;
    protected $sorting;
    protected $is_system;
    protected $is_deleted;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    function __construct()
    {
        parent::__construct(AppConsts::OC_PROCESS_AREA, AppConsts::$tableIds[AppConsts::OC_PROCESS_AREA]);

        $this->id_process_area = new FItem(FItem::DATA_TYPE_INT, "id_process_area", "ID área proceso", "", false, true);
        $this->name = new FItem(FItem::DATA_TYPE_STRING, "name", "Nombre", "", true);
        $this->code = new FItem(FItem::DATA_TYPE_STRING, "code", "Código", "", true);
        $this->sorting = new FItem(FItem::DATA_TYPE_INT, "sorting", "Ordenamiento", "", true);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", true);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", true);

        $this->items["id_process_area"] = $this->id_process_area;
        $this->items["name"] = $this->name;
        $this->items["code"] = $this->code;
        $this->items["sorting"] = $this->sorting;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->name->setRangeLength(1, 50);
        $this->code->setRangeLength(1, 5);
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM oc_process_area WHERE id_process_area = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_process_area"]);

            $this->id_process_area->setValue($row["id_process_area"]);
            $this->name->setValue($row["name"]);
            $this->code->setValue($row["code"]);
            $this->sorting->setValue($row["sorting"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_user_ins->setValue($row["fk_user_ins"]);
            $this->fk_user_upd->setValue($row["fk_user_upd"]);
            $this->ts_user_ins->setValue($row["ts_user_ins"]);
            $this->ts_user_upd->setValue($row["ts_user_upd"]);

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
            $statement = $userSession->getPdo()->prepare("INSERT INTO oc_process_area (" .
                "id_process_area, " .
                "name, " .
                "code, " .
                "sorting, " .
                "is_system, " .
                "is_deleted, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":name, " .
                ":code, " .
                ":sorting, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE oc_process_area SET " .
                "name = :name, " .
                "code = :code, " .
                "sorting = :sorting, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_test = :id;");
        }

        //$id_process_area = $this->id_process_area->getValue();
        $name = $this->name->getValue();
        $code = $this->code->getValue();
        $sorting = $this->sorting->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_process_area", $id_process_area, \PDO::PARAM_INT);
        $statement->bindParam(":name", $name);
        $statement->bindParam(":code", $code);
        $statement->bindParam(":sorting", $sorting, \PDO::PARAM_INT);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        //$statement->bindParam(":fk_user_ins", $fk_user_ins, \PDO::PARAM_INT);
        //$statement->bindParam(":fk_user_upd", $fk_user_upd, \PDO::PARAM_INT);
        //$statement->bindParam(":ts_user_ins", $ts_user_ins);
        //$statement->bindParam(":ts_user_upd", $ts_user_upd);

        $statement->bindParam(":fk_user", $fk_user, \PDO::PARAM_INT);

        if (!$this->isRegistryNew) {
            $statement->bindParam(":id", $this->id, \PDO::PARAM_INT);
        }

        $statement->execute();

        $this->isRegistryModified = false;
        if ($this->isRegistryNew) {
            $this->id = intval($userSession->getPdo()->lastInsertId());
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
