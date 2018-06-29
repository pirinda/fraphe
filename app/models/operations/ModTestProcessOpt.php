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
    protected $process_min;
    protected $process_max;
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
        $this->process_min = new FItem(FItem::DATA_TYPE_INT, "process_min", "Días mínimos de procesamiento", false);
        $this->process_max = new FItem(FItem::DATA_TYPE_INT, "process_max", "Días máximos de procesamiento", false);
        $this->cost = new FItem(FItem::DATA_TYPE_FLOAT, "cost", "Costo", false);
        $this->is_default = new FItem(FItem::DATA_TYPE_BOOL, "is_default", "Es predeterminado", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Es de sistema", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Está eliminado", false);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", true);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", true);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", true);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", true);

        $this->items["id_test"] = $this->id_test;
        $this->items["id_entity"] = $this->id_entity;
        $this->items["process_min"] = $this->process_min;
        $this->items["process_max"] = $this->process_max;
        $this->items["cost"] = $this->cost;
        $this->items["is_default"] = $this->is_default;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        // create relation IDs:
        $this->relationIds["id_test"] = 0;
        $this->relationIds["id_entity"] = 0;
    }

    public function retrieve(FUserSession $session, array $ids, int $mode)
    {
        $this->setRelationIds($ids);
        $this->initialize();

        // copy relation IDs to simplify query:
        $id_test = $this->relationIds["id_test"];
        $id_entity = $this->relationIds["id_entity"];

        $sql = "SELECT * FROM oc_test_process_opt WHERE id_test = $id_test AND id_entity = $id_entity;";
        $statement = $this->connection->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id_test->setValue($row["id_test"]);
            $this->id_entity->setValue($row["id_entity"]);
            $this->process_min->setValue($row["process_min"]);
            $this->process_max->setValue($row["process_max"]);
            $this->cost->setValue($row["cost"]);
            $this->is_default->setValue($row["is_default"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_user_ins->setValue($row["fk_user_ins"]);
            $this->fk_user_upd->setValue($row["fk_user_upd"]);
            $this->ts_user_ins->setValue($row["ts_user_ins"]);
            $this->ts_user_upd->setValue($row["ts_user_upd"]);

            // retrieve as well relation IDs:
            $this->copyRelationIdsFromItems();

            $this->isRegistryNew = false;
            $this->mode = $mode;
        }
        else {
            throw new \Exception(FRegistry::ERR_MSG_REGISTRY_NOT_FOUND);
        }
    }

    public function save(FUserSession $session)
    {
        $this->validate();

        $statement;

        if ($this->isRegistryNew) {
            $statement = $this->connection->prepare("INSERT INTO oc_test_process_opt (" .
                "id_test, " .
                "id_entity, " .
                "process_min, " .
                "process_max, " .
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
                ":process_min, " .
                ":process_max, " .
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
                "process_min = :process_min, " .
                "process_max = :process_max, " .
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
        $process_min = $this->process_min->getValue();
        $process_max = $this->process_max->getValue();
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
        $statement->bindParam(":process_min", $process_min);
        $statement->bindParam(":process_max", $process_max);
        $statement->bindParam(":cost", $cost);
        $statement->bindParam(":is_default", $is_default);
        $statement->bindParam(":is_system", $is_system);
        $statement->bindParam(":is_deleted", $is_deleted);
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
}
