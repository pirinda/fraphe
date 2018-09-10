<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;

class ModTestEntity extends FRegistry
{
    public const PREFIX = "test_entity_";

    protected $id_test_entity;
    protected $process_days_min;
    protected $process_days_max;
    protected $cost;
    protected $is_default;
    protected $is_system;
    protected $is_deleted;
    protected $fk_test;
    protected $fk_entity;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    protected $dbmsFkProcessArea;

    function __construct()
    {
        parent::__construct(AppConsts::OC_TEST_ENTITY, AppConsts::$tables[AppConsts::OC_TEST_ENTITY], AppConsts::$tableIds[AppConsts::OC_TEST_ENTITY]);

        $this->id_test_entity = new FItem(FItem::DATA_TYPE_INT, "id_test_entity", "ID ensayo entidad", "", false, true);
        $this->process_days_min = new FItem(FItem::DATA_TYPE_INT, "process_days_min", "Días mín. proceso", "", false);
        $this->process_days_max = new FItem(FItem::DATA_TYPE_INT, "process_days_max", "Días máx. proceso", "", false);
        $this->cost = new FItem(FItem::DATA_TYPE_FLOAT, "cost", "Costo", "", false);
        $this->is_default = new FItem(FItem::DATA_TYPE_BOOL, "is_default", "Predeterminado", "", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_test = new FItem(FItem::DATA_TYPE_INT, "fk_test", "Ensayo", "", true);
        $this->fk_entity = new FItem(FItem::DATA_TYPE_INT, "fk_entity", "Entidad", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_test_entity"] = $this->id_test_entity;
        $this->items["process_days_min"] = $this->process_days_min;
        $this->items["process_days_max"] = $this->process_days_max;
        $this->items["cost"] = $this->cost;
        $this->items["is_default"] = $this->is_default;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_test"] = $this->fk_test;
        $this->items["fk_entity"] = $this->fk_entity;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->dbmsFkProcessArea = null;
    }

    public function getDbmsFkProcessArea()
    {
        return $this->dbmsFkProcessArea;
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM $this->tableName WHERE id_test_entity = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_test_entity"]);

            $this->id_test_entity->setValue($row["id_test_entity"]);
            $this->process_days_min->setValue($row["process_days_min"]);
            $this->process_days_max->setValue($row["process_days_max"]);
            $this->cost->setValue($row["cost"]);
            $this->is_default->setValue($row["is_default"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_test->setValue($row["fk_test"]);
            $this->fk_entity->setValue($row["fk_entity"]);
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

        // read DBMS complementary data:
        $this->dbmsFkProcessArea = intval(AppUtils::readField($userSession, "fk_process_area", AppConsts::OC_TEST, $this->fk_test->getValue()));
    }

    public function save(FUserSession $userSession)
    {
        $this->validate($userSession);

        $statement;

        if ($this->isRegistryNew) {
            $statement = $userSession->getPdo()->prepare("INSERT INTO $this->tableName (" .
                "id_test_entity, " .
                "process_days_min, " .
                "process_days_max, " .
                "cost, " .
                "is_default, " .
                "is_system, " .
                "is_deleted, " .
                "fk_test, " .
                "fk_entity, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":process_days_min, " .
                ":process_days_max, " .
                ":cost, " .
                ":is_default, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_test, " .
                ":fk_entity, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE $this->tableName SET " .
                "process_days_min = :process_days_min, " .
                "process_days_max = :process_days_max, " .
                "cost = :cost, " .
                "is_default = :is_default, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_test = :fk_test, " .
                "fk_entity = :fk_entity, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_test_entity = :id;");
        }

        //$id_test_entity = $this->id_test_entity->getValue();
        $process_days_min = $this->process_days_min->getValue();
        $process_days_max = $this->process_days_max->getValue();
        $cost = $this->cost->getValue();
        $is_default = $this->is_default->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_test = $this->fk_test->getValue();
        $fk_entity = $this->fk_entity->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_test_entity", $id_test_entity, \PDO::PARAM_INT);
        $statement->bindParam(":process_days_min", $process_days_min, \PDO::PARAM_INT);
        $statement->bindParam(":process_days_max", $process_days_max, \PDO::PARAM_INT);
        $statement->bindParam(":cost", $cost);
        $statement->bindParam(":is_default", $is_default, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_test", $fk_test, \PDO::PARAM_INT);
        $statement->bindParam(":fk_entity", $fk_entity, \PDO::PARAM_INT);
        //$statement->bindParam(":fk_user_ins", $fk_user_ins, \PDO::PARAM_INT);
        //$statement->bindParam(":fk_user_upd", $fk_user_upd, \PDO::PARAM_INT);
        //$statement->bindParam(":ts_user_ins", $ts_user_ins);
        //$statement->bindParam(":ts_user_upd", $ts_user_upd);

        $statement->bindParam(":fk_user", $fk_user);

        if (!$this->isRegistryNew) {
            $statement->bindParam(":id", $this->id, \PDO::PARAM_INT);
        }

        $statement->execute();

        $this->isRegistryModified = false;
        if ($this->isRegistryNew) {
            $this->id = intval($userSession->getPdo()->lastInsertId());
            $this->id_test_entity->setValue($this->id);
            $this->isRegistryNew = false;
        }

        // ensure uniqueness of the "is default" flag for current test:
        if ($is_default) {
            $userSession->getPdo()->exec("UPDATE $this->tableName SET is_default = 0 WHERE fk_test = $fk_test AND is_default AND id_test_entity <> $this->id;");
        }
    }

    public function delete(FUserSession $userSession)
    {

    }

    public function undelete(FUserSession $userSession)
    {

    }

    public static function readTestEntity(FUserSession $userSession, int $idTest, int $idEntity)
    {
        $testEntity = null;

        $sql = "SELECT id_test_entity FROM oc_test_entity WHERE fk_test = $idTest AND fk_entity = $idEntity;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $testEntity = new ModTestEntity();
            $testEntity->read($userSession, intval($row["id_test_entity"]), FRegistry::MODE_READ);
        }

        return $testEntity;
    }
}
