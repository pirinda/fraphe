<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\App\FGuiUtils;
use Fraphe\Lib\FLibUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class ModReport extends FRegistry
{
    protected $id_report;
    protected $report_num;
    protected $report_date;
    protected $process_deviats;
    protected $process_notes;
    protected $reissue;
    protected $is_system;
    protected $is_deleted;
    protected $fk_company_branch;
    protected $fk_customer;
    protected $fk_sample;
    protected $fk_recept;
    protected $fk_report_delivery_type;
    protected $nk_report_reissue_cause;
    protected $fk_report_status;
    protected $fk_user_finish;
    protected $fk_user_verif;
    protected $fk_user_valid;
    protected $fk_user_release;
    protected $fk_user_deliver;
    protected $fk_user_cancel;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_finish;
    protected $ts_user_verif;
    protected $ts_user_valid;
    protected $ts_user_release;
    protected $ts_user_deliver;
    protected $ts_user_cancel;
    protected $ts_user_ins;
    protected $ts_user_upd;

    protected $childReportTests;
    protected $childReportStatusLogs;

    protected $orig_fk_report_status;

    function __construct()
    {
        parent::__construct(AppConsts::O_REPORT, AppConsts::$tables[AppConsts::O_REPORT], AppConsts::$tableIds[AppConsts::O_REPORT]);

        $this->id_report = new FItem(FItem::DATA_TYPE_INT, "id_report", "ID IR", "", false, true);
        $this->report_num = new FItem(FItem::DATA_TYPE_STRING, "report_num", "Folio IR", "", true);
        $this->report_date = new FItem(FItem::DATA_TYPE_DATE, "report_date", "Fecha IR", "", true);
        $this->process_deviats = new FItem(FItem::DATA_TYPE_STRING, "process_deviats", "Desviaciones proceso", "", false);
        $this->process_notes = new FItem(FItem::DATA_TYPE_STRING, "process_notes", "Observaciones proceso", "", false);
        $this->reissue = new FItem(FItem::DATA_TYPE_INT, "reissue", "Reimpresión núm.", "", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_company_branch = new FItem(FItem::DATA_TYPE_INT, "fk_company_branch", "Sucursal empresa", "", true);
        $this->fk_customer = new FItem(FItem::DATA_TYPE_INT, "fk_customer", "Cliente", "", true);
        $this->fk_sample = new FItem(FItem::DATA_TYPE_INT, "fk_sample", "Muestra", "", true);
        $this->fk_recept = new FItem(FItem::DATA_TYPE_INT, "fk_recept", "Recepción", "", true);
        $this->fk_report_delivery_type = new FItem(FItem::DATA_TYPE_INT, "fk_report_delivery_type", "Tipo entrega IR", "", true);
        $this->nk_report_reissue_cause = new FItem(FItem::DATA_TYPE_INT, "nk_report_reissue_cause", "Causa reemisión IR", "", false);
        $this->fk_report_status = new FItem(FItem::DATA_TYPE_INT, "fk_report_status", "Estatus IR", "", true);
        $this->fk_user_finish = new FItem(FItem::DATA_TYPE_INT, "fk_user_finish", "Usuario terminación", "", false);
        $this->fk_user_verif = new FItem(FItem::DATA_TYPE_INT, "fk_user_verif", "Usuario verificación", "", false);
        $this->fk_user_valid = new FItem(FItem::DATA_TYPE_INT, "fk_user_valid", "Usuario validación", "", false);
        $this->fk_user_release = new FItem(FItem::DATA_TYPE_INT, "fk_user_release", "Usuario liberación", "", false);
        $this->fk_user_deliver = new FItem(FItem::DATA_TYPE_INT, "fk_user_deliver", "Usuario entrega", "", false);
        $this->fk_user_cancel = new FItem(FItem::DATA_TYPE_INT, "fk_user_cancel", "Usuario cancelación", "", false);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_finish = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_finish", "Terminación", "", false);
        $this->ts_user_verif = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_verif", "Verificación", "", false);
        $this->ts_user_valid = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_valid", "Validación", "", false);
        $this->ts_user_release = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_release", "Liberación", "", false);
        $this->ts_user_deliver = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_deliver", "Entrega", "", false);
        $this->ts_user_cancel = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_cancel", "Cancelación", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_report"] = $this->id_report;
        $this->items["report_num"] = $this->report_num;
        $this->items["report_date"] = $this->report_date;
        $this->items["process_deviats"] = $this->process_deviats;
        $this->items["process_notes"] = $this->process_notes;
        $this->items["reissue"] = $this->reissue;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_company_branch"] = $this->fk_company_branch;
        $this->items["fk_customer"] = $this->fk_customer;
        $this->items["fk_sample"] = $this->fk_sample;
        $this->items["fk_recept"] = $this->fk_recept;
        $this->items["fk_report_delivery_type"] = $this->fk_report_delivery_type;
        $this->items["nk_report_reissue_cause"] = $this->nk_report_reissue_cause;
        $this->items["fk_report_status"] = $this->fk_report_status;
        $this->items["fk_user_finish"] = $this->fk_user_finish;
        $this->items["fk_user_verif"] = $this->fk_user_verif;
        $this->items["fk_user_valid"] = $this->fk_user_valid;
        $this->items["fk_user_release"] = $this->fk_user_release;
        $this->items["fk_user_deliver"] = $this->fk_user_deliver;
        $this->items["fk_user_cancel"] = $this->fk_user_cancel;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_finish"] = $this->ts_user_finish;
        $this->items["ts_user_verif"] = $this->ts_user_verif;
        $this->items["ts_user_valid"] = $this->ts_user_valid;
        $this->items["ts_user_release"] = $this->ts_user_release;
        $this->items["ts_user_deliver"] = $this->ts_user_deliver;
        $this->items["ts_user_cancel"] = $this->ts_user_cancel;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->report_num->setRangeLength(1, 25);
        $this->process_deviats->setRangeLength(0, 500);
        $this->process_notes->setRangeLength(0, 500);

        $this->clearChildReportTests();
        $this->clearChildReportStatusLogs();

        $this->orig_fk_report_status = null;
    }

    public function &getChildReportTests(): array
    {
        return $this->childReportTests;
    }

    public function &getChildReportStatusLogs(): array
    {
        return $this->childReportStatusLogs;
    }

    public function clearChildReportTests()
    {
        $this->childReportTests = array();
    }

    public function clearChildReportStatusLogs()
    {
        $this->childReportStatusLogs = array();
    }

    /** Overriden method.
     */
    public function validate(FUserSession $userSession)
    {
        // validate registry:

        parent::validate($userSession);

        $test = 0;
        foreach ($this->childReportTests as $child) {
            $data = array();
            $data["fk_report"] = $this->isRegistryNew ? -1 : $this->id; // bypass validation
            $data["report_test"] = ++$test;
            $child->setData($data);
            $child->validate($userSession);
        }

        foreach ($this->childReportStatusLogs as $child) {
            $data = array();
            $data["fk_report"] = $this->isRegistryNew ? -1 : $this->id; // bypass validation
            $child->setData($data);
            $child->validate($userSession);
        }
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM $this->tableName WHERE id_report = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_report"]);

            $this->id_report->setValue($row["id_report"]);
            $this->report_num->setValue($row["report_num"]);
            $this->report_date->setValue($row["report_date"]);
            $this->process_deviats->setValue($row["process_deviats"]);
            $this->process_notes->setValue($row["process_notes"]);
            $this->reissue->setValue($row["reissue"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_company_branch->setValue($row["fk_company_branch"]);
            $this->fk_customer->setValue($row["fk_customer"]);
            $this->fk_sample->setValue($row["fk_sample"]);
            $this->fk_recept->setValue($row["fk_recept"]);
            $this->fk_report_delivery_type->setValue($row["fk_report_delivery_type"]);
            $this->nk_report_reissue_cause->setValue($row["nk_report_reissue_cause"]);
            $this->fk_report_status->setValue($row["fk_report_status"]);
            $this->fk_user_finish->setValue($row["fk_user_finish"]);
            $this->fk_user_verif->setValue($row["fk_user_verif"]);
            $this->fk_user_valid->setValue($row["fk_user_valid"]);
            $this->fk_user_release->setValue($row["fk_user_release"]);
            $this->fk_user_deliver->setValue($row["fk_user_deliver"]);
            $this->fk_user_cancel->setValue($row["fk_user_cancel"]);
            $this->fk_user_ins->setValue($row["fk_user_ins"]);
            $this->fk_user_upd->setValue($row["fk_user_upd"]);
            $this->ts_user_finish->setValue($row["ts_user_finish"]);
            $this->ts_user_verif->setValue($row["ts_user_verif"]);
            $this->ts_user_valid->setValue($row["ts_user_valid"]);
            $this->ts_user_release->setValue($row["ts_user_release"]);
            $this->ts_user_deliver->setValue($row["ts_user_deliver"]);
            $this->ts_user_cancel->setValue($row["ts_user_cancel"]);
            $this->ts_user_ins->setValue($row["ts_user_ins"]);
            $this->ts_user_upd->setValue($row["ts_user_upd"]);

            $this->isRegistryNew = false;
            $this->mode = $mode;

            $this->orig_fk_report_status = $this->fk_report_status->getValue();

            // create PDO connection for reading children:
            $pdo = FGuiUtils::createPdo();

            // read child report tests:
            $sql = "SELECT id_report_test FROM o_report_test WHERE fk_report = $this->id ORDER BY id_report_test;";
            $statement = $pdo->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $child = new ModReportTest();
                $child->read($userSession, intval($row["id_report_test"]), $mode);
                $this->childReportTests[] = $child;
            }

            // read child report status log entries:
            $sql = "SELECT id_report_status_log FROM o_report_status_log WHERE fk_report = $this->id ORDER BY id_report_status_log;";
            $statement = $pdo->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $child = new ModReportStatusLog();
                $child->read($userSession, intval($row["id_report_status_log"]), $mode);
                $this->childReportStatusLogs[] = $child;
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
            $statement = $userSession->getPdo()->prepare("INSERT INTO $this->tableName (" .
                "id_report, " .
                "report_num, " .
                "report_date, " .
                "process_deviats, " .
                "process_notes, " .
                "reissue, " .
                "is_system, " .
                "is_deleted, " .
                "fk_company_branch, " .
                "fk_customer, " .
                "fk_sample, " .
                "fk_recept, " .
                "fk_report_delivery_type, " .
                "nk_report_reissue_cause, " .
                "fk_report_status, " .
                "fk_user_finish, " .
                "fk_user_verif, " .
                "fk_user_valid, " .
                "fk_user_release, " .
                "fk_user_deliver, " .
                "fk_user_cancel, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_finish, " .
                "ts_user_verif, " .
                "ts_user_valid, " .
                "ts_user_release, " .
                "ts_user_deliver, " .
                "ts_user_cancel, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":report_num, " .
                ":report_date, " .
                ":process_deviats, " .
                ":process_notes, " .
                ":reissue, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_company_branch, " .
                ":fk_customer, " .
                ":fk_sample, " .
                ":fk_recept, " .
                ":fk_report_delivery_type, " .
                ":nk_report_reissue_cause, " .
                ":fk_report_status, " .
                "1, " .
                "1, " .
                "1, " .
                "1, " .
                "1, " .
                "1, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW(), " .
                "NOW(), " .
                "NOW(), " .
                "NOW(), " .
                "NOW(), " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE $this->tableName SET " .
                "report_num = :report_num, " .
                "report_date = :report_date, " .
                "process_deviats = :process_deviats, " .
                "process_notes = :process_notes, " .
                "reissue = :reissue, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_company_branch = :fk_company_branch, " .
                "fk_customer = :fk_customer, " .
                "fk_sample = :fk_sample, " .
                "fk_recept = :fk_recept, " .
                "fk_report_delivery_type = :fk_report_delivery_type, " .
                "nk_report_reissue_cause = :nk_report_reissue_cause, " .
                "fk_report_status = :fk_report_status, " .
                //"fk_user_finish = :fk_user_finish, " .
                //"fk_user_verif = :fk_user_verif, " .
                //"fk_user_valid = :fk_user_valid, " .
                //"fk_user_release = :fk_user_release, " .
                //"fk_user_deliver = :fk_user_deliver, " .
                //"fk_user_cancel = :fk_user_cancel, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_finish = NOW(), " .
                //"ts_user_verif = NOW(), " .
                //"ts_user_valid = NOW(), " .
                //"ts_user_release = NOW(), " .
                //"ts_user_deliver = NOW(), " .
                //"ts_user_cancel = NOW(), " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_report = :id;");
        }

        //$id_report = $this->id_report->getValue();
        $report_num = $this->report_num->getValue();
        $report_date = FLibUtils::formatStdDate($this->report_date->getValue());
        $process_deviats = $this->process_deviats->getValue();
        $process_notes = $this->process_notes->getValue();
        $reissue = $this->reissue->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_company_branch = $this->fk_company_branch->getValue();
        $fk_customer = $this->fk_customer->getValue();
        $fk_sample = $this->fk_sample->getValue();
        $fk_recept = $this->fk_recept->getValue();
        $fk_report_delivery_type = $this->fk_report_delivery_type->getValue();
        $nk_report_reissue_cause = $this->nk_report_reissue_cause->getValue();
        $fk_report_status = $this->fk_report_status->getValue();
        $fk_user_finish = $this->fk_user_finish->getValue();
        $fk_user_verif = $this->fk_user_verif->getValue();
        $fk_user_valid = $this->fk_user_valid->getValue();
        $fk_user_release = $this->fk_user_release->getValue();
        $fk_user_deliver = $this->fk_user_deliver->getValue();
        $fk_user_cancel = $this->fk_user_cancel->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_finish = $this->ts_user_finish->getValue();
        //$ts_user_verif = $this->ts_user_verif->getValue();
        //$ts_user_valid = $this->ts_user_valid->getValue();
        //$ts_user_release = $this->ts_user_release->getValue();
        //$ts_user_deliver = $this->ts_user_deliver->getValue();
        //$ts_user_cancel = $this->ts_user_cancel->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_report", $id_report, \PDO::PARAM_INT);
        $statement->bindParam(":report_num", $report_num);
        $statement->bindParam(":report_date", $report_date);
        $statement->bindParam(":process_deviats", $process_deviats);
        $statement->bindParam(":process_notes", $process_notes);
        $statement->bindParam(":reissue", $reissue, \PDO::PARAM_INT);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_company_branch", $fk_company_branch, \PDO::PARAM_INT);
        $statement->bindParam(":fk_customer", $fk_customer, \PDO::PARAM_INT);
        $statement->bindParam(":fk_sample", $fk_sample, \PDO::PARAM_INT);
        $statement->bindParam(":fk_recept", $fk_recept, \PDO::PARAM_INT);
        $statement->bindParam(":fk_report_delivery_type", $fk_report_delivery_type, \PDO::PARAM_INT);
        if (empty($nk_report_reissue_cause)) {
            $statement->bindValue(":nk_report_reissue_cause", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":nk_report_reissue_cause", $nk_report_reissue_cause, \PDO::PARAM_INT);
        }
        $statement->bindParam(":fk_report_status", $fk_report_status, \PDO::PARAM_INT);
        //$statement->bindParam(":fk_user_finish", $fk_user_finish, \PDO::PARAM_INT);
        //$statement->bindParam(":fk_user_verif", $fk_user_verif, \PDO::PARAM_INT);
        //$statement->bindParam(":fk_user_valid", $fk_user_valid, \PDO::PARAM_INT);
        //$statement->bindParam(":fk_user_release", $fk_user_release, \PDO::PARAM_INT);
        //$statement->bindParam(":fk_user_deliver", $fk_user_deliver, \PDO::PARAM_INT);
        //$statement->bindParam(":fk_user_cancel", $fk_user_cancel, \PDO::PARAM_INT);
        //$statement->bindParam(":fk_user_ins", $fk_user_ins, \PDO::PARAM_INT);
        //$statement->bindParam(":fk_user_upd", $fk_user_upd, \PDO::PARAM_INT);
        //$statement->bindParam(":ts_user_finish", $ts_user_finish);
        //$statement->bindParam(":ts_user_verif", $ts_user_verif);
        //$statement->bindParam(":ts_user_valid", $ts_user_valid);
        //$statement->bindParam(":ts_user_release", $ts_user_release);
        //$statement->bindParam(":ts_user_deliver", $ts_user_deliver);
        //$statement->bindParam(":ts_user_cancel", $ts_user_cancel);
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
            $this->id_report->setValue($this->id);
            $this->isRegistryNew = false;
        }

        // save child report tests:
        $test = 0;
        foreach ($this->childReportTests as $child) {
            // ensure link to parent and set other data:
            $data = array();
            $data["fk_report"] = $this->id;
            $data["report_test"] = ++$test;

            // save child:
            $child->setData($data);
            $child->save($userSession);
        }

        // save child report status log entries:
        foreach ($this->childReportStatusLogs as $child) {
            if ($child->isRegistryNew()) {
                // ensure link to parent:
                $data = array();
                $data["fk_report"] = $this->id;

                // save child:
                $child->setData($data);
                $child->save($userSession);
            }
        }

        // create status-log entry, if needed:
        if ($this->orig_fk_report_status != $this->fk_report_status->getValue()) {
            $data = array();
            $data["status_datetime"] = time();
            $data["status_notes"] = "";
            $data["reissue"] = 0;
            $data["is_system"] = true;
            //$data["is_deleted"] = ?;
            $data["fk_report"] = $this->id;
            $data["fk_report_status"] = $this->fk_report_status->getValue();
            $data["nk_report_reissue_cause"] = null;
            $data["fk_user_status"] = $fk_user;

            $entry = new ModReportStatusLog();
            $this->childReportStatusLogs[] = $entry; // append entry
            $entry->setData($data);
            $entry->save($userSession);
        }
    }

    public function delete(FUserSession $userSession)
    {

    }

    public function undelete(FUserSession $userSession)
    {

    }

    /** Overriden method.
     */
    public function resetAutoIncrement(FUserSession $userSession)
    {
        parent::resetAutoIncrement($userSession);

        if (count($this->childReportTests) > 0) {
            $this->childReportTests[0]->resetAutoIncrement($userSession);
        }

        if (count($this->childReportStatusLogs) > 0) {
            $this->childReportStatusLogs[0]->resetAutoIncrement($userSession);
        }
    }
}
