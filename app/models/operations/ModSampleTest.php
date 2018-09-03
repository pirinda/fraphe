<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\Lib\FUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use Fraphe\Model\FRelation;
use app\AppConsts;

class ModSampleTest extends FRelation
{
    protected $id_sample;
    protected $id_test;
    protected $id_entity;
    protected $sample_test;
    protected $process_days_min;
    protected $process_days_max;
    protected $process_days;
    protected $process_start_date;
    protected $process_deadline;
    protected $cost;
    protected $is_system;
    protected $is_deleted;
    protected $fk_process_area;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    function __construct()
    {
        parent::__construct(AppConsts::O_SAMPLE_TEST);

        $this->id_sample = new FItem(FItem::DATA_TYPE_INT, "id_sample", "ID muestra", "", false, true);
        $this->id_test = new FItem(FItem::DATA_TYPE_INT, "id_test", "ID ensayo", "", false, true);
        $this->id_entity = new FItem(FItem::DATA_TYPE_INT, "id_entity", "ID entidad", "", false, true);
        $this->sample_test = new FItem(FItem::DATA_TYPE_INT, "sample_test", "Núm. ensayo muestra", "", true);
        $this->process_days_min = new FItem(FItem::DATA_TYPE_INT, "process_days_min", "Días mínimos proceso", "", true);
        $this->process_days_max = new FItem(FItem::DATA_TYPE_INT, "process_days_max", "Días máximos proceso", "", true);
        $this->process_days = new FItem(FItem::DATA_TYPE_INT, "process_days", "Días proceso", "", true);
        $this->process_start_date = new FItem(FItem::DATA_TYPE_DATE, "process_start_date", "Fecha inicio proceso", "", true);
        $this->process_deadline = new FItem(FItem::DATA_TYPE_DATE, "process_deadline", "Fecha límite proceso", "", true);
        $this->cost = new FItem(FItem::DATA_TYPE_FLOAT, "cost", "Costo", "", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_process_area = new FItem(FItem::DATA_TYPE_INT, "fk_process_area", "Área proceso", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_sample"] = $this->id_sample;
        $this->items["id_test"] = $this->id_test;
        $this->items["id_entity"] = $this->id_entity;
        $this->items["sample_test"] = $this->sample_test;
        $this->items["process_days_min"] = $this->process_days_min;
        $this->items["process_days_max"] = $this->process_days_max;
        $this->items["process_days"] = $this->process_days;
        $this->items["process_start_date"] = $this->process_start_date;
        $this->items["process_deadline"] = $this->process_deadline;
        $this->items["cost"] = $this->cost;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_process_area"] = $this->fk_process_area;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        // create relation IDs:
        $this->ids["id_sample"] = 0;
        $this->ids["id_test"] = 0;
        $this->ids["id_entity"] = 0;
    }

    protected function generateSampleTest(FUserSession $userSession) {
        $this->id_sample->validate();

        $sql = "SELECT COALESCE(MAX(sample_test), 0) AS _max_sample_test FROM o_sample_test WHERE id_sample = " . $this->id_sample->getValue() . ";";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->sample_test->setValue(intval($row["_max_sample_test"]) + 1);
        }
    }

    /* Computes process days and deadline.
     * NOTE: 'entity ID' and 'process start date' must be set.
     */
    public function computeProcessDays()
    {
        // validate required data:
        $this->id_entity->validate();
        $this->process_start_date->validate();

        // compute process days:
        $processDays = $this->process_days_max->getValue();
        if ($this->id_entity->getValue() != 1) { // 1 is system's company
            $processDays += 5; // TODO: parameterize this configurable variable!
        }
        $this->process_days->setValue($processDays);

        // compute process deadline:
        $dt = new \DateTime(FUtils::formatStdDate($this->process_start_date->getValue()));
        $dt->add(new \DateInterval("P" . $processDays . "D"));
        $this->process_deadline->setValue($dt->getTimestamp()); // TODO: improve deadline computation!
    }

    public function validate(FUserSession $userSession)
    {
        // compute data:

        $test = new ModTest();
        $test->read($userSession, $this->id_test->getValue(), FRegistry::MODE_READ);
        $this->fk_process_area->setValue($test->getDatum("fk_process_area"));

        if (empty($this->sample_test->getValue())) {
            $this->generateSampleTest($userSession);
        }

        // compute data:
        $this->computeProcessDays();

        // validate registry:
        parent::validate($userSession);
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
            $this->sample_test->setValue($row["sample_test"]);
            $this->process_days_min->setValue($row["process_days_min"]);
            $this->process_days_max->setValue($row["process_days_max"]);
            $this->process_days->setValue($row["process_days"]);
            $this->process_start_date->setValue($row["process_start_date"]);
            $this->process_deadline->setValue($row["process_deadline"]);
            $this->cost->setValue($row["cost"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_process_area->setValue($row["fk_process_area"]);
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
        $this->isRegistryNew = $this->checkRegistryNew($userSession, "o_sample_test") == 0;

        if ($this->isRegistryNew) {
            $statement = $userSession->getPdo()->prepare("INSERT INTO o_sample_test (" .
                "id_sample, " .
                "id_test, " .
                "id_entity, " .
                "sample_test, " .
                "process_days_min, " .
                "process_days_max, " .
                "process_days, " .
                "process_start_date, " .
                "process_deadline, " .
                "cost, " .
                "is_system, " .
                "is_deleted, " .
                "fk_process_area, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                ":id_sample, " .
                ":id_test, " .
                ":id_entity, " .
                ":sample_test, " .
                ":process_days_min, " .
                ":process_days_max, " .
                ":process_days, " .
                ":process_start_date, " .
                ":process_deadline, " .
                ":cost, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_process_area, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE o_sample_test SET " .
                "sample_test = :sample_test, " .
                "process_days_min = :process_days_min, " .
                "process_days_max = :process_days_max, " .
                "process_days = :process_days, " .
                "process_start_date = :process_start_date, " .
                "process_deadline = :process_deadline, " .
                "cost = :cost, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_process_area = :fk_process_area, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_sample = :id_sample AND id_test = :id_test AND id_entity = :id_entity;");
        }

        $id_sample = $this->id_sample->getValue();
        $id_test = $this->id_test->getValue();
        $id_entity = $this->id_entity->getValue();
        $sample_test = $this->sample_test->getValue();
        $process_days_min = $this->process_days_min->getValue();
        $process_days_max = $this->process_days_max->getValue();
        $process_days = $this->process_days->getValue();
        $process_start_date = FUtils::formatStdDate($this->process_start_date->getValue());
        $process_deadline = FUtils::formatStdDate($this->process_deadline->getValue());
        $cost = $this->cost->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_process_area = $this->fk_process_area->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        $statement->bindParam(":id_sample", $id_sample, \PDO::PARAM_INT);
        $statement->bindParam(":id_test", $id_test, \PDO::PARAM_INT);
        $statement->bindParam(":id_entity", $id_entity, \PDO::PARAM_INT);
        $statement->bindParam(":sample_test", $sample_test, \PDO::PARAM_INT);
        $statement->bindParam(":process_days_min", $process_days_min, \PDO::PARAM_INT);
        $statement->bindParam(":process_days_max", $process_days_max, \PDO::PARAM_INT);
        $statement->bindParam(":process_days", $process_days, \PDO::PARAM_INT);
        $statement->bindParam(":process_start_date", $process_start_date);
        $statement->bindParam(":process_deadline", $process_deadline);
        $statement->bindParam(":cost", $cost);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_process_area", $fk_process_area, \PDO::PARAM_INT);
        //$statement->bindParam(":fk_user_ins", $fk_user_ins, \PDO::PARAM_INT);
        //$statement->bindParam(":fk_user_upd", $fk_user_upd, \PDO::PARAM_INT);
        //$statement->bindParam(":ts_user_ins", $ts_user_ins);
        //$statement->bindParam(":ts_user_upd", $ts_user_upd);

        $statement->bindParam(":fk_user", $fk_user, \PDO::PARAM_INT);

        $statement->execute();

        $this->isRegistryModified = false;
        if ($this->isRegistryNew) {
            $this->isRegistryNew = false;
        }

        // update test process entity if needed:
        $create = false;
        $testProcessEntity = new ModTestProcessEntity();
        $ids = array("id_test" => $this->id_test->getValue(), "id_entity" => $this->id_entity->getValue());

        try {
            $testProcessEntity->retrieve($userSession, $ids, FRegistry::MODE_READ);
        }
        catch (Exception $e) {
            $create = true;
        }

        $data = array();
        if ($create) {
            // create new registry
            $data["id_test"] = $this->id_test->getValue();
            $data["id_entity"] = $this->id_entity->getValue();
            $data["process_days_min"] = $this->process_days_min->getValue();
            $data["process_days_max"] = $this->process_days_max->getValue();
            $data["cost"] = $this->cost->getValue();
            $data["is_default"] = false;
            $testProcessEntity->initialize();
        }
        else {
            // update existing registry:
            if ($testProcessEntity->getDatum("process_days_min") != $this->process_days_min->getValue()) {
                $data["process_days_min"] = $this->process_days_min->getValue();
            }
            if ($testProcessEntity->getDatum("process_days_max") != $this->process_days_max->getValue()) {
                $data["process_days_max"] = $this->process_days_max->getValue();
            }
            if ($testProcessEntity->getDatum("cost") != $this->cost->getValue()) {
                $data["cost"] = $this->cost->getValue();
            }
        }

        if (count($data) > 0) {
            $testProcessEntity->setData($data);
            $testProcessEntity->save($userSession);
        }
    }

    public function delete(FUserSession $userSession)
    {

    }

    public function undelete(FUserSession $userSession)
    {

    }
}
