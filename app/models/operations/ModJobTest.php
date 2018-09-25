<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\Lib\FLibUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class ModJobTest extends FRegistry
{
    protected $id_job_test;
    protected $job_test;
    protected $process_days;
    protected $process_start_date;
    protected $process_deadline;
    protected $ext_job_num;
    protected $ext_tracking_num;
    protected $ext_result_deadline_n;
    protected $ext_result_released_n;
    protected $is_system;
    protected $is_deleted;
    protected $fk_job;
    protected $fk_test;
    protected $fk_entity;
    protected $fk_sample_test;
    protected $fk_job_test_status;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    function __construct()
    {
        parent::__construct(AppConsts::O_JOB_TEST, AppConsts::$tables[AppConsts::O_JOB_TEST], AppConsts::$tableIds[AppConsts::O_JOB_TEST]);

        $this->id_job_test = new FItem(FItem::DATA_TYPE_INT, "id_job_test", "ID orden trabajo", "", false, true);
        $this->job_test = new FItem(FItem::DATA_TYPE_INT, "job_test", "Núm. ensayo orden trabajo", "", true);
        $this->process_days = new FItem(FItem::DATA_TYPE_INT, "process_days", "Días proceso", "", false);
        $this->process_start_date = new FItem(FItem::DATA_TYPE_DATE, "process_start_date", "Fecha inicio proceso", "", true);
        $this->process_deadline = new FItem(FItem::DATA_TYPE_DATE, "process_deadline", "Fecha límite proceso", "", true);
        $this->ext_job_num = new FItem(FItem::DATA_TYPE_STRING, "ext_job_num", "Externos: folio orden trabajo", "", false);
        $this->ext_tracking_num = new FItem(FItem::DATA_TYPE_STRING, "ext_tracking_num", "Externos: núm. rastreo paquetería", "", false);
        $this->ext_result_deadline_n = new FItem(FItem::DATA_TYPE_DATE, "ext_result_deadline_n", "Externos: fecha límite resultados", "", false);
        $this->ext_result_released_n = new FItem(FItem::DATA_TYPE_DATE, "ext_result_released_n", "Externos: fecha liberación resultados", "", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_job = new FItem(FItem::DATA_TYPE_INT, "fk_job", "Orden trabajo", "", true);
        $this->fk_test = new FItem(FItem::DATA_TYPE_INT, "fk_test", "Ensayo", "", true);
        $this->fk_entity = new FItem(FItem::DATA_TYPE_INT, "fk_entity", "Entidad", "", true);
        $this->fk_sample_test = new FItem(FItem::DATA_TYPE_INT, "fk_sample_test", "Muestra + ensayo", "", true);
        $this->fk_job_test_status = new FItem(FItem::DATA_TYPE_INT, "fk_job_test_status", "Estatus orden trabajo", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_job_test"] = $this->id_job_test;
        $this->items["job_test"] = $this->job_test;
        $this->items["process_days"] = $this->process_days;
        $this->items["process_start_date"] = $this->process_start_date;
        $this->items["process_deadline"] = $this->process_deadline;
        $this->items["ext_job_num"] = $this->ext_job_num;
        $this->items["ext_tracking_num"] = $this->ext_tracking_num;
        $this->items["ext_result_deadline_n"] = $this->ext_result_deadline_n;
        $this->items["ext_result_released_n"] = $this->ext_result_released_n;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_job"] = $this->fk_job;
        $this->items["fk_test"] = $this->fk_test;
        $this->items["fk_entity"] = $this->fk_entity;
        $this->items["fk_sample_test"] = $this->fk_sample_test;
        $this->items["fk_job_test_status"] = $this->fk_job_test_status;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->ext_job_num->setRangeLength(0, 25);
        $this->ext_tracking_num->setRangeLength(0, 25);
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM $this->tableName WHERE id_job_test = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_job_test"]);

            $this->id_job_test->setValue($row["id_job_test"]);
            $this->job_test->setValue($row["job_test"]);
            $this->process_days->setValue($row["process_days"]);
            $this->process_start_date->setValue($row["process_start_date"]);
            $this->process_deadline->setValue($row["process_deadline"]);
            $this->ext_job_num->setValue($row["ext_job_num"]);
            $this->ext_tracking_num->setValue($row["ext_tracking_num"]);
            $this->ext_result_deadline_n->setValue($row["ext_result_deadline_n"]);
            $this->ext_result_released_n->setValue($row["ext_result_released_n"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_job->setValue($row["fk_job"]);
            $this->fk_test->setValue($row["fk_test"]);
            $this->fk_entity->setValue($row["fk_entity"]);
            $this->fk_sample_test->setValue($row["fk_sample_test"]);
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
            $statement = $userSession->getPdo()->prepare("INSERT INTO $this->tableName (" .
                "id_job_test, " .
                "job_test, " .
                "process_days, " .
                "process_start_date, " .
                "process_deadline, " .
                "ext_job_num, " .
                "ext_tracking_num, " .
                "ext_result_deadline_n, " .
                "ext_result_released_n, " .
                "is_system, " .
                "is_deleted, " .
                "fk_job, " .
                "fk_test, " .
                "fk_entity, " .
                "fk_sample_test, " .
                "fk_job_test_status, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":job_test, " .
                ":process_days, " .
                ":process_start_date, " .
                ":process_deadline, " .
                ":ext_job_num, " .
                ":ext_tracking_num, " .
                ":ext_result_deadline_n, " .
                ":ext_result_released_n, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_job, " .
                ":fk_test, " .
                ":fk_entity, " .
                ":fk_sample_test, " .
                ":fk_job_test_status, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE $this->tableName SET " .
                "job_test = :job_test, " .
                "process_days = :process_days, " .
                "process_start_date = :process_start_date, " .
                "process_deadline = :process_deadline, " .
                "ext_job_num = :ext_job_num, " .
                "ext_tracking_num = :ext_tracking_num, " .
                "ext_result_deadline_n = :ext_result_deadline_n, " .
                "ext_result_released_n = :ext_result_released_n, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_job = :fk_job, " .
                "fk_test = :fk_test, " .
                "fk_entity = :fk_entity, " .
                "fk_sample_test = :fk_sample_test, " .
                "fk_job_test_status = :fk_job_test_status, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_job_test = :id;");
        }

        //$id_job_test = $this->id_job_test->getValue();
        $job_test = $this->job_test->getValue();
        $process_days = $this->process_days->getValue();
        $process_start_date = FLibUtils::formatStdDate($this->process_start_date->getValue());
        $process_deadline = FLibUtils::formatStdDate($this->process_deadline->getValue());
        $ext_job_num = $this->ext_job_num->getValue();
        $ext_tracking_num = $this->ext_tracking_num->getValue();
        $ext_result_deadline_n = FLibUtils::formatStdDate($this->ext_result_deadline_n->getValue());
        $ext_result_released_n = FLibUtils::formatStdDate($this->ext_result_released_n->getValue());
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_job = $this->fk_job->getValue();
        $fk_test = $this->fk_test->getValue();
        $fk_entity = $this->fk_entity->getValue();
        $fk_sample_test = $this->fk_sample_test->getValue();
        $fk_job_test_status = $this->fk_job_test_status->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_job_test", $id_job_test, \PDO::PARAM_INT);
        $statement->bindParam(":job_test", $job_test, \PDO::PARAM_INT);
        $statement->bindParam(":process_days", $process_days, \PDO::PARAM_INT);
        $statement->bindParam(":process_start_date", $process_start_date);
        $statement->bindParam(":process_deadline", $process_deadline);
        $statement->bindParam(":ext_job_num", $ext_job_num);
        $statement->bindParam(":ext_tracking_num", $ext_tracking_num);
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
        $statement->bindParam(":fk_job", $fk_job, \PDO::PARAM_INT);
        $statement->bindParam(":fk_test", $fk_test, \PDO::PARAM_INT);
        $statement->bindParam(":fk_entity", $fk_entity, \PDO::PARAM_INT);
        $statement->bindParam(":fk_sample_test", $fk_sample_test, \PDO::PARAM_INT);
        $statement->bindParam(":fk_job_test_status", $fk_job_test_status, \PDO::PARAM_INT);
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
            $this->id_job_test->setValue($this->id);
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
