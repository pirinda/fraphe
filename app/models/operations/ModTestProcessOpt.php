<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use Fraphe\Model\FRelation;
use app\AppConsts;

class ModTestProcessOpt extends FRelation
{
    protected $id_test;
    protected $id_entity;
    protected $process_days_min;
    protected $process_days_max;
    protected $cost;
    protected $is_default;
    protected $is_system;
    protected $is_deleted;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    function __construct(\PDO $connection)
    {
        parent::__construct($connection, AppConsts::OC_TEST_PROCESS_OPT);

        $this->id_test = new FItem(FItem::DATA_TYPE_INT, "id_test", "ID ensayo", false);
        $this->id_entity = new FItem(FItem::DATA_TYPE_INT, "id_entity", "ID entidad", false);
        $this->process_days_min = new FItem(FItem::DATA_TYPE_INT, "process_days_min", "Días mínimos proceso", true);
        $this->process_days_max = new FItem(FItem::DATA_TYPE_INT, "process_days_max", "Días máximos proceso", true);
        $this->cost = new FItem(FItem::DATA_TYPE_FLOAT, "cost", "Costo", false);
        $this->is_default = new FItem(FItem::DATA_TYPE_BOOL, "is_default", "Predeterminado", true);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", true);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", false);

        $this->items["id_test"] = $this->id_test;
        $this->items["id_entity"] = $this->id_entity;
        $this->items["process_days_min"] = $this->process_days_min;
        $this->items["process_days_max"] = $this->process_days_max;
        $this->items["cost"] = $this->cost;
        $this->items["is_default"] = $this->is_default;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        // create relation IDs:
        $this->ids["id_test"] = 0;
        $this->ids["id_entity"] = 0;
    }

    public function retrieve(FUserSession $session, array $ids, int $mode)
    {
        $this->initialize();
        $this->setIds($ids);

        // copy relation IDs to simplify query:
        $id_test = $this->ids["id_test"];
        $id_entity = $this->ids["id_entity"];

        $sql = "SELECT * FROM oc_test_process_opt WHERE id_test = $id_test AND id_entity = $id_entity;";
        $statement = $this->connection->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id_test->setValue($row["id_test"]);
            $this->id_entity->setValue($row["id_entity"]);
            $this->process_days_min->setValue($row["process_days_min"]);
            $this->process_days_max->setValue($row["process_days_max"]);
            $this->cost->setValue($row["cost"]);
            $this->is_default->setValue($row["is_default"]);
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

    public function save(FUserSession $session)
    {
        $this->validateOnSave();

        $statement;
        $this->isRegistryNew = $this->checkRegistryNew($session, "oc_test_process_opt") == 0;

        if ($this->isRegistryNew) {
            $statement = $this->connection->prepare("INSERT INTO oc_test_process_opt (" .
                "id_test, " .
                "id_entity, " .
                "process_days_min, " .
                "process_days_max, " .
                "cost, " .
                "is_default, " .
                "is_system, " .
                "is_deleted, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                ":id_test, " .
                ":id_entity, " .
                ":process_days_min, " .
                ":process_days_max, " .
                ":cost, " .
                ":is_default, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $this->connection->prepare("UPDATE oc_test_process_opt SET " .
                "process_days_min = :process_days_min, " .
                "process_days_max = :process_days_max, " .
                "cost = :cost, " .
                "is_default = :is_default, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_test = :id_test AND id_entity = :id_entity;");
        }

        $id_test = $this->id_test->getValue();
        $id_entity = $this->id_entity->getValue();
        $process_days_min = $this->process_days_min->getValue();
        $process_days_max = $this->process_days_max->getValue();
        $cost = $this->cost->getValue();
        $is_default = $this->is_default->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        //$fk_user_ins = $this->fk_user_ins->getValue();
        //$fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $session->getCurUser()->getId();

        $statement->bindParam(":id_test", $id_test);
        $statement->bindParam(":id_entity", $id_entity);
        $statement->bindParam(":process_days_min", $process_days_min);
        $statement->bindParam(":process_days_max", $process_days_max);
        $statement->bindParam(":cost", $cost);
        $statement->bindParam(":is_default", $is_default, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        //$statement->bindParam(":fk_user_ins", $fk_user_ins);
        //$statement->bindParam(":fk_user_upd", $fk_user_upd);
        //$statement->bindParam(":ts_user_ins", $ts_user_ins);
        //$statement->bindParam(":ts_user_upd", $ts_user_upd);

        $statement->bindParam(":fk_user", $fk_user);

        $statement->execute();

        $this->isRegistryModified = false;
        if ($this->isRegistryNew) {
            $this->isRegistryNew = false;
        }
    }

    public function delete(FUserSession $session)
    {

    }

    public function undelete(FUserSession $session)
    {

    }
}
