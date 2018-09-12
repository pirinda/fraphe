<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\Lib\FLibUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class ModReportTest extends FRegistry
{
    protected $id_report_test;
    protected $report_test;
    protected $result;
    protected $uncertainty;
    protected $is_system;
    protected $is_deleted;
    protected $fk_report;
    protected $fk_test;
    protected $fk_job_test;
    protected $fk_sample_test;
    protected $fk_result_permiss_limit;
    protected $nk_result_unit;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    function __construct()
    {
        parent::__construct(AppConsts::O_REPORT_TEST, AppConsts::$tables[AppConsts::O_REPORT_TEST], AppConsts::$tableIds[AppConsts::O_REPORT_TEST]);

        $this->id_report_test = new FItem(FItem::DATA_TYPE_INT, "id_report_test", "ID IR + ensayo", "", false, true);
        $this->report_test = new FItem(FItem::DATA_TYPE_INT, "report_test", "Núm. ensayo IR", "", true);
        $this->result = new FItem(FItem::DATA_TYPE_STRING, "result", "Resultado", "", false);
        $this->uncertainty = new FItem(FItem::DATA_TYPE_STRING, "uncertainty", "Incertidumbre", "", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_report = new FItem(FItem::DATA_TYPE_INT, "fk_report", "Reporte", "", true);
        $this->fk_test = new FItem(FItem::DATA_TYPE_INT, "fk_test", "Ensayo", "", true);
        $this->fk_job_test = new FItem(FItem::DATA_TYPE_INT, "fk_job_test", "Orden trabajo + ensayo", "", true);
        $this->fk_sample_test = new FItem(FItem::DATA_TYPE_INT, "fk_sample_test", "Muestra + ensayo", "", true);
        $this->fk_result_permiss_limit = new FItem(FItem::DATA_TYPE_INT, "fk_result_permiss_limit", "Límites permisibles", "", true);
        $this->nk_result_unit = new FItem(FItem::DATA_TYPE_INT, "nk_result_unit", "Unidad medida resultado", "", false);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_report_test"] = $this->id_report_test;
        $this->items["report_test"] = $this->report_test;
        $this->items["result"] = $this->result;
        $this->items["uncertainty"] = $this->uncertainty;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_report"] = $this->fk_report;
        $this->items["fk_test"] = $this->fk_test;
        $this->items["fk_job_test"] = $this->fk_job_test;
        $this->items["fk_sample_test"] = $this->fk_sample_test;
        $this->items["fk_result_permiss_limit"] = $this->fk_result_permiss_limit;
        $this->items["nk_result_unit"] = $this->nk_result_unit;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->result->setRangeLength(1, 100);
        $this->uncertainty->setRangeLength(1, 10);
    }

    protected function updateJobTest(FUserSession $userSession)
    {
        $sql = "SELECT id_job_test FROM o_job_test WHERE fk_sample_test = " . $this->fk_sample_test->getValue() . " AND NOT is_deleted;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->fk_job_test->setValue(intval($row["id_job_test"]));
        }
    }

    /** Overriden method.
     */
    public function validate(FUserSession $userSession)
    {
        // compute data:
        $this->updateJobTest($userSession);

        // validate registry:
        parent::validate($userSession);
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM $this->tableName WHERE id_report_test = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_report_test"]);

            $this->id_report_test->setValue($row["id_report_test"]);
            $this->report_test->setValue($row["report_test"]);
            $this->result->setValue($row["result"]);
            $this->uncertainty->setValue($row["uncertainty"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_report->setValue($row["fk_report"]);
            $this->fk_test->setValue($row["fk_test"]);
            $this->fk_job_test->setValue($row["fk_job_test"]);
            $this->fk_sample_test->setValue($row["fk_sample_test"]);
            $this->fk_result_permiss_limit->setValue($row["fk_result_permiss_limit"]);
            $this->nk_result_unit->setValue($row["nk_result_unit"]);
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
                "id_report_test, " .
                "report_test, " .
                "result, " .
                "uncertainty, " .
                "is_system, " .
                "is_deleted, " .
                "fk_report, " .
                "fk_test, " .
                "fk_job_test, " .
                "fk_sample_test, " .
                "fk_result_permiss_limit, " .
                "nk_result_unit, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":report_test, " .
                ":result, " .
                ":uncertainty, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_report, " .
                ":fk_test, " .
                ":fk_job_test, " .
                ":fk_sample_test, " .
                ":fk_result_permiss_limit, " .
                ":nk_result_unit, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE $this->tableName SET " .
                "report_test = :report_test, " .
                "result = :result, " .
                "uncertainty = :uncertainty, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_report = :fk_report, " .
                "fk_test = :fk_test, " .
                "fk_job_test = :fk_job_test, " .
                "fk_sample_test = :fk_sample_test, " .
                "fk_result_permiss_limit = :fk_result_permiss_limit, " .
                "nk_result_unit = :nk_result_unit, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_report_test = :id;");
        }

        //$id_report_test = $this->id_report_test->getValue();
        $report_test = $this->report_test->getValue();
        $result = $this->result->getValue();
        $uncertainty = $this->uncertainty->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_report = $this->fk_report->getValue();
        $fk_test = $this->fk_test->getValue();
        $fk_job_test = $this->fk_job_test->getValue();
        $fk_sample_test = $this->fk_sample_test->getValue();
        $fk_result_permiss_limit = $this->fk_result_permiss_limit->getValue();
        $nk_result_unit = $this->nk_result_unit->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_report_test", $id_report_test, \PDO::PARAM_INT);
        $statement->bindParam(":report_test", $report_test, \PDO::PARAM_INT);
        $statement->bindParam(":result", $result);
        $statement->bindParam(":uncertainty", $uncertainty);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_report", $fk_report, \PDO::PARAM_INT);
        $statement->bindParam(":fk_test", $fk_test, \PDO::PARAM_INT);
        $statement->bindParam(":fk_job_test", $fk_job_test, \PDO::PARAM_INT);
        $statement->bindParam(":fk_sample_test", $fk_sample_test, \PDO::PARAM_INT);
        $statement->bindParam(":fk_result_permiss_limit", $fk_result_permiss_limit, \PDO::PARAM_INT);
        if (empty($nk_result_unit)) {
            $statement->bindValue(":nk_result_unit", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":nk_result_unit", $nk_result_unit, \PDO::PARAM_INT);
        }
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
            $this->id_report_test->setValue($this->id);
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
