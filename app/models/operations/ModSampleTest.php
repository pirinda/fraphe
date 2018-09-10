<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\Lib\FLibUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;

class ModSampleTest extends FRegistry
{
    protected $id_sample_test;
    protected $sample_test;
    protected $process_days_min;
    protected $process_days_max;
    protected $process_days;
    protected $process_start_date;
    protected $process_deadline;
    protected $cost;
    protected $is_system;
    protected $is_deleted;
    protected $fk_sample;
    protected $fk_test;
    protected $fk_entity;
    protected $fk_process_area;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    function __construct()
    {
        parent::__construct(AppConsts::O_SAMPLE_TEST, AppConsts::$tables[AppConsts::O_SAMPLE_TEST], AppConsts::$tableIds[AppConsts::O_SAMPLE_TEST]);

        $this->id_sample_test = new FItem(FItem::DATA_TYPE_INT, "id_sample_test", "ID muestra", "", false, true);
        $this->sample_test = new FItem(FItem::DATA_TYPE_INT, "sample_test", "Núm. ensayo muestra", "", false);
        $this->process_days_min = new FItem(FItem::DATA_TYPE_INT, "process_days_min", "Días mín. proceso", "", false);
        $this->process_days_max = new FItem(FItem::DATA_TYPE_INT, "process_days_max", "Días máx. proceso", "", false);
        $this->process_days = new FItem(FItem::DATA_TYPE_INT, "process_days", "Días proceso", "", false);
        $this->process_start_date = new FItem(FItem::DATA_TYPE_DATE, "process_start_date", "Fecha inicio proceso", "", true);
        $this->process_deadline = new FItem(FItem::DATA_TYPE_DATE, "process_deadline", "Fecha límite proceso", "", true);
        $this->cost = new FItem(FItem::DATA_TYPE_FLOAT, "cost", "Costo", "", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_sample = new FItem(FItem::DATA_TYPE_INT, "fk_sample", "Muestra", "", true);
        $this->fk_test = new FItem(FItem::DATA_TYPE_INT, "fk_test", "Ensayo", "", true);
        $this->fk_entity = new FItem(FItem::DATA_TYPE_INT, "fk_entity", "Entidad", "", true);
        $this->fk_process_area = new FItem(FItem::DATA_TYPE_INT, "fk_process_area", "Área proceso", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_sample_test"] = $this->id_sample_test;
        $this->items["sample_test"] = $this->sample_test;
        $this->items["process_days_min"] = $this->process_days_min;
        $this->items["process_days_max"] = $this->process_days_max;
        $this->items["process_days"] = $this->process_days;
        $this->items["process_start_date"] = $this->process_start_date;
        $this->items["process_deadline"] = $this->process_deadline;
        $this->items["cost"] = $this->cost;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_sample"] = $this->fk_sample;
        $this->items["fk_test"] = $this->fk_test;
        $this->items["fk_entity"] = $this->fk_entity;
        $this->items["fk_process_area"] = $this->fk_process_area;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;
    }

    protected function generateSampleTest(FUserSession $userSession) {
        $this->fk_sample->validate();

        $sql = "SELECT COALESCE(MAX(sample_test), 0) AS _max_sample_test FROM $this->tableName WHERE fk_sample = " . $this->fk_sample->getValue() . ";";
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
        $this->fk_entity->validate();
        $this->process_start_date->validate();

        // compute process days:
        $processDays = $this->process_days_max->getValue();
        if ($this->fk_entity->getValue() != 1) { // 1 is system's company
            $processDays += 5; // TODO: parameterize this configurable variable!
        }
        $this->process_days->setValue($processDays);

        // compute process deadline:
        $dt = new \DateTime(FLibUtils::formatStdDate($this->process_start_date->getValue()));
        $dt->add(new \DateInterval("P" . $processDays . "D"));
        $this->process_deadline->setValue($dt->getTimestamp()); // TODO: improve deadline computation!
    }

    /** Overriden method.
     */
    public function forceRegistryNew()
    {
        parent::forceRegistryNew();

        $this->sample_test->reset();
    }

    /** Overriden method.
     */
    public function validate(FUserSession $userSession)
    {
        // compute data:

        $this->fk_process_area->setValue(intval(AppUtils::readField($userSession, "fk_process_area", AppConsts::OC_TEST, $this->fk_test->getValue())));

        if (empty($this->sample_test->getValue())) {
            $this->generateSampleTest($userSession);
        }

        $this->computeProcessDays();

        // validate registry:
        parent::validate($userSession);
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM $this->tableName WHERE id_sample_test = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_sample_test"]);

            $this->id_sample_test->setValue($row["id_sample_test"]);
            $this->sample_test->setValue($row["sample_test"]);
            $this->process_days_min->setValue($row["process_days_min"]);
            $this->process_days_max->setValue($row["process_days_max"]);
            $this->process_days->setValue($row["process_days"]);
            $this->process_start_date->setValue($row["process_start_date"]);
            $this->process_deadline->setValue($row["process_deadline"]);
            $this->cost->setValue($row["cost"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_sample->setValue($row["fk_sample"]);
            $this->fk_test->setValue($row["fk_test"]);
            $this->fk_entity->setValue($row["fk_entity"]);
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

        if ($this->isRegistryNew) {
            $statement = $userSession->getPdo()->prepare("INSERT INTO $this->tableName (" .
                "id_sample_test, " .
                "sample_test, " .
                "process_days_min, " .
                "process_days_max, " .
                "process_days, " .
                "process_start_date, " .
                "process_deadline, " .
                "cost, " .
                "is_system, " .
                "is_deleted, " .
                "fk_sample, " .
                "fk_test, " .
                "fk_entity, " .
                "fk_process_area, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":sample_test, " .
                ":process_days_min, " .
                ":process_days_max, " .
                ":process_days, " .
                ":process_start_date, " .
                ":process_deadline, " .
                ":cost, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_sample, " .
                ":fk_test, " .
                ":fk_entity, " .
                ":fk_process_area, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE $this->tableName SET " .
                "sample_test = :sample_test, " .
                "process_days_min = :process_days_min, " .
                "process_days_max = :process_days_max, " .
                "process_days = :process_days, " .
                "process_start_date = :process_start_date, " .
                "process_deadline = :process_deadline, " .
                "cost = :cost, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_sample = :fk_sample, " .
                "fk_test = :fk_test, " .
                "fk_entity = :fk_entity, " .
                "fk_process_area = :fk_process_area, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_sample_test = :id;");
        }

        //$id_sample_test = $this->id_sample_test->getValue();
        $sample_test = $this->sample_test->getValue();
        $process_days_min = $this->process_days_min->getValue();
        $process_days_max = $this->process_days_max->getValue();
        $process_days = $this->process_days->getValue();
        $process_start_date = FLibUtils::formatStdDate($this->process_start_date->getValue());
        $process_deadline = FLibUtils::formatStdDate($this->process_deadline->getValue());
        $cost = $this->cost->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_sample = $this->fk_sample->getValue();
        $fk_test = $this->fk_test->getValue();
        $fk_entity = $this->fk_entity->getValue();
        $fk_process_area = $this->fk_process_area->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_sample_test", $id_sample_test, \PDO::PARAM_INT);
        $statement->bindParam(":sample_test", $sample_test, \PDO::PARAM_INT);
        $statement->bindParam(":process_days_min", $process_days_min, \PDO::PARAM_INT);
        $statement->bindParam(":process_days_max", $process_days_max, \PDO::PARAM_INT);
        $statement->bindParam(":process_days", $process_days, \PDO::PARAM_INT);
        $statement->bindParam(":process_start_date", $process_start_date);
        $statement->bindParam(":process_deadline", $process_deadline);
        $statement->bindParam(":cost", $cost);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_sample", $fk_sample, \PDO::PARAM_INT);
        $statement->bindParam(":fk_test", $fk_test, \PDO::PARAM_INT);
        $statement->bindParam(":fk_entity", $fk_entity, \PDO::PARAM_INT);
        $statement->bindParam(":fk_process_area", $fk_process_area, \PDO::PARAM_INT);
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
            $this->id_sample_test->setValue($this->id);
            $this->isRegistryNew = false;
        }

        // create or update test process entity if needed:

        $testEntity;
        $data = array();

        try {
            $testEntity = ModTestEntity::readTestEntity($userSession, $fk_test, $fk_entity);
        }
        catch (Exception $e) {
            $testEntity = null;
        }

        if (!isset($testEntity)) {
            // create new registry
            $data["process_days_min"] = $this->process_days_min->getValue();
            $data["process_days_max"] = $this->process_days_max->getValue();
            $data["cost"] = $this->cost->getValue();
            $data["is_default"] = false;
            $data["fk_test"] = $this->fk_test->getValue();
            $data["fk_entity"] = $this->fk_entity->getValue();

            $testEntity = new ModTestEntity();
        }
        else {
            // update existing registry:
            if ($testEntity->getDatum("process_days_min") != $this->process_days_min->getValue()) {
                $data["process_days_min"] = $this->process_days_min->getValue();
            }
            if ($testEntity->getDatum("process_days_max") != $this->process_days_max->getValue()) {
                $data["process_days_max"] = $this->process_days_max->getValue();
            }
            if ($testEntity->getDatum("cost") != $this->cost->getValue()) {
                $data["cost"] = $this->cost->getValue();
            }
        }

        if (count($data) > 0) {
            $testEntity->setData($data);
            $testEntity->save($userSession);
        }
    }

    public function delete(FUserSession $userSession)
    {

    }

    public function undelete(FUserSession $userSession)
    {

    }
}
