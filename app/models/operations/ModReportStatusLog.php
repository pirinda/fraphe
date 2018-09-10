<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\App\FGuiUtils;
use Fraphe\Lib\FLibUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class ModReportStatusLog extends FRegistry
{
    protected $id_report_status_log;
    protected $status_datetime;
    protected $status_notes;
    protected $reissue;
    protected $is_system;
    protected $is_deleted;
    protected $fk_report;
    protected $fk_report_status;
    protected $nk_report_reissue_cause;
    protected $fk_user_status;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    function __construct()
    {
        parent::__construct(AppConsts::O_REPORT_STATUS_LOG, AppConsts::$tables[AppConsts::O_REPORT_STATUS_LOG], AppConsts::$tableIds[AppConsts::O_REPORT_STATUS_LOG]);

        $this->id_report_status_log = new FItem(FItem::DATA_TYPE_INT, "id_report_status_log", "ID cambio estatus IR", "", false, true);
        $this->status_datetime = new FItem(FItem::DATA_TYPE_DATETIME, "status_datetime", "Fecha-hr cambio estatus", "", true);
        $this->status_notes = new FItem(FItem::DATA_TYPE_STRING, "status_notes", "Observaciones cambio estatus", "", false);
        $this->reissue = new FItem(FItem::DATA_TYPE_INT, "reissue", "Reimpresión núm.", "", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_report = new FItem(FItem::DATA_TYPE_INT, "fk_report", "Reporte", "", true);
        $this->fk_report_status = new FItem(FItem::DATA_TYPE_INT, "fk_report_status", "Estatus IR", "", true);
        $this->nk_report_reissue_cause = new FItem(FItem::DATA_TYPE_INT, "nk_report_reissue_cause", "Causa reemisión IR", "", false);
        $this->fk_user_status = new FItem(FItem::DATA_TYPE_INT, "fk_user_status", "Usuario estatus", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_report_status_log"] = $this->id_report_status_log;
        $this->items["status_datetime"] = $this->status_datetime;
        $this->items["status_notes"] = $this->status_notes;
        $this->items["reissue"] = $this->reissue;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_report"] = $this->fk_report;
        $this->items["fk_report_status"] = $this->fk_report_status;
        $this->items["nk_report_reissue_cause"] = $this->nk_report_reissue_cause;
        $this->items["fk_user_status"] = $this->fk_user_status;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->status_notes->setRangeLength(0, 500);
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM $this->tableName WHERE id_report_status_log = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_report_status_log"]);

            $this->id_report_status_log->setValue($row["id_report_status_log"]);
            $this->status_datetime->setValue($row["status_datetime"]);
            $this->status_notes->setValue($row["status_notes"]);
            $this->reissue->setValue($row["reissue"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_report->setValue($row["fk_report"]);
            $this->fk_report_status->setValue($row["fk_report_status"]);
            $this->nk_report_reissue_cause->setValue($row["nk_report_reissue_cause"]);
            $this->fk_user_status->setValue($row["fk_user_status"]);
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
                "id_report_status_log, " .
                "status_datetime, " .
                "status_notes, " .
                "reissue, " .
                "is_system, " .
                "is_deleted, " .
                "fk_report, " .
                "fk_report_status, " .
                "nk_report_reissue_cause, " .
                "fk_user_status, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":status_datetime, " .
                ":status_notes, " .
                ":reissue, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_report, " .
                ":fk_report_status, " .
                ":nk_report_reissue_cause, " .
                ":fk_user_status, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            throw new \Exception(__METHOD__ . ": " . FRegistry::ERR_MSG_REGISTRY_NON_UPDATABLE);
        }

        //$id_report_status_log = $this->id_report_status_log->getValue();
        $status_datetime = FLibUtils::formatStdDatetime($this->status_datetime->getValue());
        $status_notes = $this->status_notes->getValue();
        $reissue = $this->reissue->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_report = $this->fk_report->getValue();
        $fk_report_status = $this->fk_report_status->getValue();
        $nk_report_reissue_cause = $this->nk_report_reissue_cause->getValue();
        $fk_user_status = $this->fk_user_status->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_report_status_log", $id_report_status_log, \PDO::PARAM_INT);
        $statement->bindParam(":status_datetime", $status_datetime);
        $statement->bindParam(":status_notes", $status_notes);
        $statement->bindParam(":reissue", $reissue, \PDO::PARAM_INT);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_report", $fk_report, \PDO::PARAM_INT);
        $statement->bindParam(":fk_report_status", $fk_report_status, \PDO::PARAM_INT);
        if (empty($nk_report_reissue_cause)) {
            $statement->bindValue(":nk_report_reissue_cause", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":nk_report_reissue_cause", $nk_report_reissue_cause, \PDO::PARAM_INT);
        }
        $statement->bindParam(":fk_user_status", $fk_user_status, \PDO::PARAM_INT);
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
            $this->id_report_status_log->setValue($this->id);
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
