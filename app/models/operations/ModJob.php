<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\App\FGuiUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class ModJob extends FRegistry
{
    protected $id_job;
    protected $job_num;
    protected $job_date;
    protected $process_days;
    protected $process_start_date;
    protected $process_deadline;
    protected $is_system;
    protected $is_deleted;
    protected $fk_company_branch;
    protected $fk_process_area;
    protected $fk_sample;
    protected $fk_recept;
    protected $fk_job_status;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    protected $childJobTests;
    protected $childJobStatusLogs;

    function __construct()
    {
        parent::__construct(AppConsts::O_JOB, AppConsts::$tableIds[AppConsts::O_JOB]);

        $this->id_job = new FItem(FItem::DATA_TYPE_INT, "id_job", "ID orden trabajo", "", false, true);
        $this->job_num = new FItem(FItem::DATA_TYPE_STRING, "job_num", "Folio orden trabajo", "", true);
        $this->job_date = new FItem(FItem::DATA_TYPE_DATE, "job_date", "Fecha orden trabajo", "", true);
        $this->process_days = new FItem(FItem::DATA_TYPE_INT, "process_days", "Días proceso", "", false);
        $this->process_start_date = new FItem(FItem::DATA_TYPE_DATE, "process_start_date", "Fecha inicio proceso", "", true);
        $this->process_deadline = new FItem(FItem::DATA_TYPE_DATE, "process_deadline", "Fecha límite proceso", "", true);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_company_branch = new FItem(FItem::DATA_TYPE_INT, "fk_company_branch", "Sucursal empresa", "", true);
        $this->fk_process_area = new FItem(FItem::DATA_TYPE_INT, "fk_process_area", "Área proceso", "", true);
        $this->fk_sample = new FItem(FItem::DATA_TYPE_INT, "fk_sample", "Muestra", "", true);
        $this->fk_recept = new FItem(FItem::DATA_TYPE_INT, "fk_recept", "Recepción", "", true);
        $this->fk_job_status = new FItem(FItem::DATA_TYPE_INT, "fk_job_status", "Estatus orden trabajo", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_job"] = $this->id_job;
        $this->items["job_num"] = $this->job_num;
        $this->items["job_date"] = $this->job_date;
        $this->items["process_days"] = $this->process_days;
        $this->items["process_start_date"] = $this->process_start_date;
        $this->items["process_deadline"] = $this->process_deadline;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_company_branch"] = $this->fk_company_branch;
        $this->items["fk_process_area"] = $this->fk_process_area;
        $this->items["fk_sample"] = $this->fk_sample;
        $this->items["fk_recept"] = $this->fk_recept;
        $this->items["fk_job_status"] = $this->fk_job_status;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->job_num->setRangeLength(1, 25);

        $this->clearChildJobTests();
        $this->clearChildJobStatusLogs();
    }

    public function &getChildJobTests(): array
    {
        return $this->childJobTests;
    }

    public function &getChildJobStatusLogs(): array
    {
        return $this->childJobStatusLogs;
    }

    public function clearChildJobTests()
    {
        $this->childJobTests = array();
    }

    public function clearChildJobStatusLogs()
    {
        $this->childJobStatusLogs = array();
    }

    public function validate(FUserSession $userSession)
    {
        // validate registry:

        parent::validate($userSession);

        foreach ($this->childJobTests as $child) {
            $data = array();
            $data["fk_job"] = $this->isRegistryNew ? -1 : $this->id; // bypass validation
            $child->setData($data);
            $child->validate($userSession);
        }

        foreach ($this->$childJobStatusLogs as $child) {
            $data = array();
            $data["fk_job"] = $this->isRegistryNew ? -1 : $this->id; // bypass validation
            $child->setData($data);
            $child->validate($userSession);
        }
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM o_job WHERE id_job = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_job"]);

            $this->id_job->setValue($row["id_job"]);
            $this->job_num->setValue($row["job_num"]);
            $this->job_date->setValue($row["job_date"]);
            $this->process_days->setValue($row["process_days"]);
            $this->process_start_date->setValue($row["process_start_date"]);
            $this->process_deadline->setValue($row["process_deadline"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_company_branch->setValue($row["fk_company_branch"]);
            $this->fk_process_area->setValue($row["fk_process_area"]);
            $this->fk_sample->setValue($row["fk_sample"]);
            $this->fk_recept->setValue($row["fk_recept"]);
            $this->fk_job_status->setValue($row["fk_job_status"]);
            $this->fk_user_ins->setValue($row["fk_user_ins"]);
            $this->fk_user_upd->setValue($row["fk_user_upd"]);
            $this->ts_user_ins->setValue($row["ts_user_ins"]);
            $this->ts_user_upd->setValue($row["ts_user_upd"]);

            $this->isRegistryNew = false;
            $this->mode = $mode;

            // create PDO connection for reading children:
            $pdo = FGuiUtils::createPdo();

            // read child job tests:
            $sql = "SELECT id_job_test FROM o_job_test WHERE fk_job = $this->id ORDER BY id_job_test;";
            $statement = $pdo->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $child = new ModJobTest();
                $child->read($userSession, intval($row["id_job_test"]), $mode);
                $this->childJobTests[] = $child;
            }

            // read child job status log entries:
            $sql = "SELECT id_job_status_log FROM o_job_status_log WHERE fk_job = $this->id ORDER BY id_job_status_log;";
            $statement = $pdo->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $child = new ModJobStatusLog();
                $child->read($userSession, intval($row["id_job_status_log"]), $mode);
                $this->childJobStatusLogs[] = $child;
            }
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
            $statement = $userSession->getPdo()->prepare("INSERT INTO o_job (" .
                "id_job, " .
                "job_num, " .
                "job_date, " .
                "process_days, " .
                "process_start_date, " .
                "process_deadline, " .
                "is_system, " .
                "is_deleted, " .
                "fk_company_branch, " .
                "fk_process_area, " .
                "fk_sample, " .
                "fk_recept, " .
                "fk_job_status, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":job_num, " .
                ":job_date, " .
                ":process_days, " .
                ":process_start_date, " .
                ":process_deadline, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_company_branch, " .
                ":fk_process_area, " .
                ":fk_sample, " .
                ":fk_recept, " .
                ":fk_job_status, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE o_job SET " .
                "job_num = :job_num, " .
                "job_date = :job_date, " .
                "process_days = :process_days, " .
                "process_start_date = :process_start_date, " .
                "process_deadline = :process_deadline, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_company_branch = :fk_company_branch, " .
                "fk_process_area = :fk_process_area, " .
                "fk_sample = :fk_sample, " .
                "fk_recept = :fk_recept, " .
                "fk_job_status = :fk_job_status, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_sample = :id;");
        }

        //$id_job = $this->id_job->getValue();
        $job_num = $this->job_num->getValue();
        $job_date = FUtils::formatStdDate($this->job_date->getValue());
        $process_days = $this->process_days->getValue();
        $process_start_date = FUtils::formatStdDate($this->process_start_date->getValue());
        $process_deadline = FUtils::formatStdDate($this->process_deadline->getValue());
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_company_branch = $this->fk_company_branch->getValue();
        $fk_process_area = $this->fk_process_area->getValue();
        $fk_sample = $this->fk_sample->getValue();
        $fk_recept = $this->fk_recept->getValue();
        $fk_job_status = $this->fk_job_status->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_job", $id_job, \PDO::PARAM_INT);
        $statement->bindParam(":job_num", $job_num);
        $statement->bindParam(":job_date", $job_date);
        $statement->bindParam(":process_days", $process_days, \PDO::PARAM_INT);
        $statement->bindParam(":process_start_date", $process_start_date);
        $statement->bindParam(":process_deadline", $process_deadline);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_company_branch", $fk_company_branch, \PDO::PARAM_INT);
        $statement->bindParam(":fk_process_area", $fk_process_area, \PDO::PARAM_INT);
        $statement->bindParam(":fk_sample", $fk_sample, \PDO::PARAM_INT);
        $statement->bindParam(":fk_recept", $fk_recept, \PDO::PARAM_INT);
        $statement->bindParam(":fk_job_status", $fk_job_status, \PDO::PARAM_INT);
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
            $this->id_job->setValue($this->id);
            $this->isRegistryNew = false;
        }

        // save child job tests:
        foreach ($this->childJobTests as $test) {
            // ensure link to parent:
            $data = array();
            $data["fk_job"] = $this->id;

            // save child:
            $child->setData($data);
            $child->save($userSession);
        }

        // save child job status log entries:
        foreach ($this->childJobStatusLogs as $child) {
            // ensure link to parent:
            $data = array();
            $data["fk_job"] = $this->id;

            // save child:
            $child->setData($data);
            $child->save($userSession);
        }
    }

    public function delete(FUserSession $userSession)
    {

    }

    public function undelete(FUserSession $userSession)
    {

    }
}
