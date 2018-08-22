<?php
namespace app\models\catalogs;

use Fraphe\App\FUserSession;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use Fraphe\Model\FRelation;
use app\AppConsts;

class ModSampleTest extends FRelation
{
    protected $id_sample;
    protected $id_test;
    protected $id_entity;
    protected $position;
    protected $process_days_min;
    protected $process_days_max;
    protected $cost;

    function __construct()
    {
        parent::__construct(AppConsts::O_SAMPLE_TEST);

        $this->id_sample = new FItem(FItem::DATA_TYPE_INT, "id_sample", "ID muestra", "", false, true);
        $this->id_test = new FItem(FItem::DATA_TYPE_INT, "id_test", "ID ensayo", "", false, true);
        $this->id_entity = new FItem(FItem::DATA_TYPE_INT, "id_entity", "ID entidad", "", false, true);
        $this->position = new FItem(FItem::DATA_TYPE_INT, "position", "Partida ensayo", "", true);
        $this->process_days_min = new FItem(FItem::DATA_TYPE_INT, "process_days_min", "Días mínimos proceso", "", true);
        $this->process_days_max = new FItem(FItem::DATA_TYPE_INT, "process_days_max", "Días máximos proceso", "", true);
        $this->cost = new FItem(FItem::DATA_TYPE_FLOAT, "cost", "Costo", "", false);

        $this->items["id_sample"] = $this->id_sample;
        $this->items["id_test"] = $this->id_test;
        $this->items["id_entity"] = $this->id_entity;
        $this->items["position"] = $this->position;
        $this->items["process_days_min"] = $this->process_days_min;
        $this->items["process_days_max"] = $this->process_days_max;
        $this->items["cost"] = $this->cost;

        // create relation IDs:
        $this->ids["id_sample"] = 0;
        $this->ids["id_test"] = 0;
        $this->ids["id_entity"] = 0;
    }

    public function retrieve(FUserSession $userSession, array $ids, int $mode)
    {
        $this->initialize();
        $this->setIds($ids);

        // copy relation IDs to simplify query:
        $id_sample = $this->ids["id_sample"];
        $id_test = $this->ids["id_test"];
        $id_entity = $this->ids["id_entity"];

        $sql = "SELECT * FROM o_sample_test WHERE id_sample = $id_sample AND id_test = $id_test AND id_entity = $id_entity;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id_sample->setValue($row["id_sample"]);
            $this->id_test->setValue($row["id_test"]);
            $this->id_entity->setValue($row["id_entity"]);
            $this->position->setValue($row["position"]);
            $this->process_days_min->setValue($row["process_days_min"]);
            $this->process_days_max->setValue($row["process_days_max"]);
            $this->cost->setValue($row["cost"]);

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
            $statement = $userSession->getPdo()->prepare("INSERT INTO o_sample_test (" .
                "id_sample, " .
                "id_test, " .
                "id_entity, " .
                "position, " .
                "process_days_min, " .
                "process_days_max, " .
                "cost) " .
                "VALUES (" .
                ":id_sample, " .
                ":id_test, " .
                ":id_entity, " .
                ":position, " .
                ":process_days_min, " .
                ":process_days_max, " .
                ":cost);");
        }
        else {
            throw new \Exception(__METHOD__ . ": " . FRegistry::ERR_MSG_REGISTRY_NON_UPDATABLE);
        }

        $id_sample = $this->id_sample->getValue();
        $id_test = $this->id_test->getValue();
        $id_entity = $this->id_entity->getValue();
        $position = $this->position->getValue();
        $process_days_min = $this->process_days_min->getValue();
        $process_days_max = $this->process_days_max->getValue();
        $cost = $this->cost->getValue();

        $statement->bindParam(":id_sample", $id_sample, \PDO::PARAM_INT);
        $statement->bindParam(":id_test", $id_test, \PDO::PARAM_INT);
        $statement->bindParam(":id_entity", $id_entity, \PDO::PARAM_INT);
        $statement->bindParam(":position", $position, \PDO::PARAM_INT);
        $statement->bindParam(":process_days_min", $process_days_min, \PDO::PARAM_INT);
        $statement->bindParam(":process_days_max", $process_days_max, \PDO::PARAM_INT);
        $statement->bindParam(":cost", $cost);

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
