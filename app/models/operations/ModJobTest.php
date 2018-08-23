<?php
namespace app\models\catalogs;

use Fraphe\App\FUserSession;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use Fraphe\Model\FRelation;
use app\AppConsts;

class ModJobTest extends FRelation
{
    protected $id_job;
    protected $id_test;
    protected $id_entity;
    protected $job_test;
    protected $process_days;
    protected $process_deadline;
    protected $ext_number;
    protected $ext_tracking_number;
    protected $ext_result_deadline_n;
    protected $ext_result_released_n;
    protected $is_system;
    protected $is_deleted;
    protected $fk_job_test_status;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    function __construct()
    {
        parent::__construct(AppConsts::O_JOB_TEST);

        $this->id_job = new FItem(FItem::DATA_TYPE_INT, "id_job", "ID orden trabajo", "", false, true);
        $this->id_test = new FItem(FItem::DATA_TYPE_INT, "id_test", "ID ensayo", "", false, true);
        $this->id_entity = new FItem(FItem::DATA_TYPE_INT, "id_entity", "ID entidad", "", false, true);
        $this->job_test = new FItem(FItem::DATA_TYPE_INT, "job_test", "Núm. ensayo orden trabajo", "", true);
        $this->process_days = new FItem(FItem::DATA_TYPE_INT, "process_days", "Días proceso", "", true);
        $this->process_deadline = new FItem(FItem::DATA_TYPE_DATE, "process_deadline", "Fecha límite proceso", "", true);
        $this->ext_number = new FItem(FItem::DATA_TYPE_STRING, "ext_number", "Externos. Folio orden trabajo", "", false);
        $this->ext_tracking_number = new FItem(FItem::DATA_TYPE_STRING, "ext_tracking_number", "Externos. Núm. rastreo paquetería", "", false);
        $this->ext_result_deadline_n = new FItem(FItem::DATA_TYPE_DATE, "ext_result_deadline_n", "Externos: Fecha límite resultados", "", false);
        $this->ext_result_released_n = new FItem(FItem::DATA_TYPE_DATE, "ext_result_released_n", "Externos: Fecha liberación resultados", "", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_job_test_status = new FItem(FItem::DATA_TYPE_INT, "fk_job_test_status", "Estatus orden trabajo", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_job"] = $this->id_job;
        $this->items["id_test"] = $this->id_test;
        $this->items["id_entity"] = $this->id_entity;
        $this->items["job_test"] = $this->job_test;
        $this->items["process_days"] = $this->process_days;
        $this->items["process_deadline"] = $this->process_deadline;
        $this->items["ext_number"] = $this->ext_number;
        $this->items["ext_tracking_number"] = $this->ext_tracking_number;
        $this->items["ext_result_deadline_n"] = $this->ext_result_deadline_n;
        $this->items["ext_result_released_n"] = $this->ext_result_released_n;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_job_test_status"] = $this->fk_job_test_status;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->ext_number->setRangeLength(0, 25);
        $this->ext_tracking_number->setRangeLength(0, 25);

        // create relation IDs:
        $this->ids["id_job"] = 0;
        $this->ids["id_test"] = 0;
        $this->ids["id_entity"] = 0;
    }

    public function retrieve(FUserSession $userSession, array $ids, int $mode)
    {
        $this->initialize();
        $this->setIds($ids);

        // copy relation IDs to simplify query:
        $id_job = $this->ids["id_job"];
        $id_test = $this->ids["id_test"];
        $id_entity = $this->ids["id_entity"];

        $sql = "SELECT * FROM o_job_test WHERE id_job = $id_job AND id_test = $id_test AND id_entity = $id_entity;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id_job->setValue($row["id_job"]);
            $this->id_test->setValue($row["id_test"]);
            $this->id_entity->setValue($row["id_entity"]);
            $this->job_test->setValue($row["job_test"]);
            $this->process_days->setValue($row["process_days"]);
            $this->process_deadline->setValue($row["process_deadline"]);
            $this->ext_number->setValue($row["ext_number"]);
            $this->ext_tracking_number->setValue($row["ext_tracking_number"]);
            $this->ext_result_deadline_n->setValue($row["ext_result_deadline_n"]);
            $this->ext_result_released_n->setValue($row["ext_result_released_n"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_job_test_status->setValue($row["fk_job_test_status"]);
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
            $statement = $userSession->getPdo()->prepare("INSERT INTO o_job_test (" .
                "id_job, " .
                "id_test, " .
                "id_entity, " .
                "job_test, " .
                "process_days, " .
                "process_deadline, " .
                "ext_number, " .
                "ext_tracking_number, " .
                "ext_result_deadline_n, " .
                "ext_result_released_n, " .
                "is_system, " .
                "is_deleted, " .
                "fk_job_test_status, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                ":id_job, " .
                ":id_test, " .
                ":id_entity, " .
                ":job_test, " .
                ":process_days, " .
                ":process_deadline, " .
                ":ext_number, " .
                ":ext_tracking_number, " .
                ":ext_result_deadline_n, " .
                ":ext_result_released_n, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_job_test_status, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE o_job_test SET " .
                "job_test = :job_test, " .
                "process_days = :process_days, " .
                "process_deadline = :process_deadline, " .
                "ext_number = :ext_number, " .
                "ext_tracking_number = :ext_tracking_number, " .
                "ext_result_deadline_n = :ext_result_deadline_n, " .
                "ext_result_released_n = :ext_result_released_n, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_job_test_status = :fk_job_test_status, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_sample = :id_sample AND id_test = :id_test AND id_entity = :id_entity;");
        }

        $id_job = $this->id_job->getValue();
        $id_test = $this->id_test->getValue();
        $id_entity = $this->id_entity->getValue();
        $job_test = $this->job_test->getValue();
        $process_days = $this->process_days->getValue();
        $process_deadline = FUtils::formatDbmsDate($this->process_deadline->getValue());
        $ext_number = $this->ext_number->getValue();
        $ext_tracking_number = $this->ext_tracking_number->getValue();
        $ext_result_deadline_n = FUtils::formatDbmsDate($this->ext_result_deadline_n->getValue());
        $ext_result_released_n = FUtils::formatDbmsDate($this->ext_result_released_n->getValue());
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_job_test_status = $this->fk_job_test_status->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $statement->bindParam(":id_job", $id_job, \PDO::PARAM_INT);
        $statement->bindParam(":id_test", $id_test, \PDO::PARAM_INT);
        $statement->bindParam(":id_entity", $id_entity, \PDO::PARAM_INT);
        $statement->bindParam(":job_test", $job_test, \PDO::PARAM_INT);
        $statement->bindParam(":process_days", $process_days, \PDO::PARAM_INT);
        $statement->bindParam(":process_deadline", $process_deadline);
        $statement->bindParam(":ext_number", $ext_number);
        $statement->bindParam(":ext_tracking_number", $ext_tracking_number);
        if (empty($ext_result_deadline_n)) {
            $statement->bindValue(":ext_result_deadline_n", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":ext_result_deadline_n", $ext_result_deadline_n);
        }
        if (empty($ext_result_released_n)) {
            $statement->bindValue(":ext_result_released_n", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":ext_result_released_n", $ext_result_released_n);
        }
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_job_test_status", $fk_job_test_status, \PDO::PARAM_INT);
        $statement->bindParam(":fk_user_ins", $fk_user_ins, \PDO::PARAM_INT);
        $statement->bindParam(":fk_user_upd", $fk_user_upd, \PDO::PARAM_INT);
        //$statement->bindParam(":ts_user_ins", $ts_user_ins);
        //$statement->bindParam(":ts_user_upd", $ts_user_upd);

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
