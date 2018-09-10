<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\App\FGuiUtils;
use Fraphe\Lib\FLibUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\models\catalogs\ModEntity;

class ModRecept extends FRegistry
{
    public const PREFIX = "recept_";
    public const SERVICE_ORDINARY = "O";
    public const SERVICE_URGENT = "U";

    protected $id_recept;
    protected $recept_num;
    protected $recept_datetime;
    protected $process_days;
    protected $process_start_date;
    protected $process_deadline;
    protected $recept_deadline;
    protected $recept_deviats;
    protected $recept_notes;
    protected $service_type;
    protected $ref_chain_custody;
    protected $ref_request;
    protected $ref_agreet;
    protected $is_system;
    protected $is_deleted;
    protected $fk_company_branch;
    protected $fk_customer;
    protected $fk_recept_status;
    protected $fk_user_receiver;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    protected $childSamples; // array of ModSample

    protected $orig_recept_datetime;
    protected $orig_fk_user_receiver;

    function __construct()
    {
        parent::__construct(AppConsts::O_RECEPT, AppConsts::$tables[AppConsts::O_RECEPT], AppConsts::$tableIds[AppConsts::O_RECEPT]);

        $this->id_recept = new FItem(FItem::DATA_TYPE_INT, "id_recept", "ID recepción", "", false, true);
        $this->recept_num = new FItem(FItem::DATA_TYPE_STRING, "recept_num", "Folio recepción", "", true);
        $this->recept_datetime = new FItem(FItem::DATA_TYPE_DATETIME, "recept_datetime", "Fecha-hr recepción", "aaaa-mm-ddTHH:mm", true);
        $this->process_days = new FItem(FItem::DATA_TYPE_INT, "process_days", "Días proceso", "", false);
        $this->process_start_date = new FItem(FItem::DATA_TYPE_DATE, "process_start_date", "Fecha inicio proceso", "", true);
        $this->process_deadline = new FItem(FItem::DATA_TYPE_DATE, "process_deadline", "Fecha límite proceso", "", true);
        $this->recept_deadline = new FItem(FItem::DATA_TYPE_DATE, "recept_deadline", "Fecha límite recepción", "", true);
        $this->recept_deviats = new FItem(FItem::DATA_TYPE_STRING, "recept_deviats", "Desviaciones recepción", "", false);
        $this->recept_notes = new FItem(FItem::DATA_TYPE_STRING, "recept_notes", "Observaciones recepción", "", false);
        $this->service_type = new FItem(FItem::DATA_TYPE_STRING, "service_type", "Tipo servicio", "", true);
        $this->ref_chain_custody = new FItem(FItem::DATA_TYPE_STRING, "ref_chain_custody", "Ref. cadena custodia", "", false);
        $this->ref_request = new FItem(FItem::DATA_TYPE_STRING, "ref_request", "Ref. solicitud ensayos", "", false);
        $this->ref_agreet = new FItem(FItem::DATA_TYPE_STRING, "ref_agreet", "Ref. convenio ensayos", "", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_company_branch = new FItem(FItem::DATA_TYPE_INT, "fk_company_branch", "Sucursal empresa", "", true);
        $this->fk_customer = new FItem(FItem::DATA_TYPE_INT, "fk_customer", "Cliente", "", true);
        $this->fk_recept_status = new FItem(FItem::DATA_TYPE_INT, "fk_recept_status", "Estatus recepción", "", true);
        $this->fk_user_receiver = new FItem(FItem::DATA_TYPE_INT, "fk_user_receiver", "Receptor", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_recept"] = $this->id_recept;
        $this->items["recept_num"] = $this->recept_num;
        $this->items["recept_datetime"] = $this->recept_datetime;
        $this->items["process_days"] = $this->process_days;
        $this->items["process_start_date"] = $this->process_start_date;
        $this->items["process_deadline"] = $this->process_deadline;
        $this->items["recept_deadline"] = $this->recept_deadline;
        $this->items["recept_deviats"] = $this->recept_deviats;
        $this->items["recept_notes"] = $this->recept_notes;
        $this->items["service_type"] = $this->service_type;
        $this->items["ref_chain_custody"] = $this->ref_chain_custody;
        $this->items["ref_request"] = $this->ref_request;
        $this->items["ref_agreet"] = $this->ref_agreet;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_company_branch"] = $this->fk_company_branch;
        $this->items["fk_customer"] = $this->fk_customer;
        $this->items["fk_recept_status"] = $this->fk_recept_status;
        $this->items["fk_user_receiver"] = $this->fk_user_receiver;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->recept_num->setRangeLength(1, 25);
        $this->recept_deviats->setRangeLength(0, 500);
        $this->recept_notes->setRangeLength(0, 500);
        $this->service_type->setRangeLength(1, 1);
        $this->ref_chain_custody->setRangeLength(0, 25);
        $this->ref_request->setRangeLength(0, 25);
        $this->ref_agreet->setRangeLength(0, 25);

        $this->clearChildSamples();

        $this->orig_recept_datetime = null;
        $this->orig_fk_user_receiver = null;
    }

    protected function generateNumber(FUserSession $userSession): string
    {
        // get count of receptions:
        $count = 0;
        $date = FLibUtils::formatStdDate($this->recept_datetime->getValue());
        $sql = "SELECT COUNT(*) AS _count FROM $this->tableName WHERE DATE(recept_datetime) = '$date';";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $count = intval($row["_count"]) + 1;
        }

        // create number formatter:
        $nf = new \NumberFormatter($userSession->getLocLang(), \NumberFormatter::PATTERN_DECIMAL);
        $nf->setAttribute(\NumberFormatter::MIN_INTEGER_DIGITS, 4); // TODO: paremeterize this formatting argument: 4!

        // generate number:
        return date("ymd", $this->recept_datetime->getValue()) . $nf->format($count); // TODO: parameterize this formatting argument: "ymd"!
    }

    /* Computes process start date.
     * NOTE: this method is called by computeProcessDays().
     */
    protected function computeStartDate()
    {
        // TODO: parameterize configurable variables of this method!

        // compute extra days:
        $extraDays = 0;
        $receptDate = FLibUtils::extractDate($this->recept_datetime->getValue());
        $date = getdate($receptDate);
        if ($date["wday"] == 0) { // Sunday
            $extraDays += 1;
        }
        else {
            if ($this->service_type->getValue() == self::SERVICE_ORDINARY) {
                if ($date["wday"] == 6) { // Saturday
                    $extraDays += 2;
                }
                else if ($date["hours"] >= 14) { // 14:00
                    $extraDays += 1;
                }
            }
        }

        // compute process start date:
        $startDate;
        if ($extraDays == 0) {
            $startDate = $receptDate;
        }
        else {
            $dt = new \DateTime();
            $dt->setTimestamp($receptDate);
            $dt->add(new \DateInterval("P" . $extraDays . "D"));
            $startDate = $dt->getTimestamp();
        }

        $this->process_start_date->setValue($startDate);

        // propagate process start date:
        foreach ($this->childSamples as $sample) {
            $sample->setProcessStartDate($startDate);
        }
    }

    protected function computeProcessDays()
    {
        $this->computeStartDate();

        $maxDays = 0;
        $minStartDate = $this->process_start_date->getValue();  // just computed
        $maxDeadline = $this->process_start_date->getValue();   // just computed

        foreach ($this->childSamples as $sample) {
            $sample->computeProcessDays();

            if ($sample->getDatum("process_days") > $maxDays) {
                $maxDays = $sample->getDatum("process_days");
            }

            if (!isset($minStartDate)) {
                $minStartDate = $sample->getDatum("process_start_date");
            }
            else {
                if ($sample->getDatum("process_start_date") < $minStartDate) {
                    $minStartDate = $sample->getDatum("process_start_date");
                }
            }

            if ($sample->getDatum("process_deadline") > $maxDeadline) {
                $maxDeadline = $sample->getDatum("process_deadline");
            }
        }

        $this->process_days->setValue($maxDays);
        $this->process_start_date->setValue($minStartDate);
        $this->process_deadline->setValue($maxDeadline);

        // add additional process days for reporting and report validation:
        $dt = new \DateTime(FLibUtils::formatStdDate($maxDeadline));
        $dt->add(new \DateInterval("P2D")); // TODO: parameterize this configurable variable!
        $this->recept_deadline->setValue($dt->getTimestamp());
    }

    public function &getChildSamples(): array
    {
        return $this->childSamples;
    }

    public function clearChildSamples()
    {
        $this->childSamples = array();
    }

    public function createSample(FUserSession $userSession): ModSample
    {
        $data = array();

        $data["recept_datetime_n"] = $this->recept_datetime->getValue();
        $data["recept_deviats"] = $this->recept_deviats->getValue();
        $data["recept_notes"] = $this->recept_notes->getValue();
        $data["service_type"] = $this->service_type->getValue();
        $data["process_start_date"] = $this->process_start_date->getValue();
        $data["ref_chain_custody"] = $this->ref_chain_custody->getValue();
        $data["ref_request"] = $this->ref_request->getValue();
        $data["ref_agreet"] = $this->ref_agreet->getValue();
        $data["nk_recept"] = $this->id;
        $data["fk_user_receiver"] = $this->fk_user_receiver->getValue();

        $customer = new ModEntity();
        $customer->read($userSession, $this->fk_customer->getValue(), FRegistry::MODE_READ);
        $contact = $customer->getChildEntityAddresses()[0]->getChildContactReport();

        $data["is_def_sampling_img"] = $customer->getDatum("is_def_sampling_img");
        $data["fk_customer"] = $customer->getId();
        $data["nk_customer_billing"] = $customer->getDatum("nk_entity_billing");
        $data["fk_report_contact"] = empty($contact) ? 0 : $contact->getId();
        $data["fk_report_delivery_type"] = $customer->getDatum("nk_report_delivery_type");

        $sample = new ModSample();
        $sample->setData($data);

        return $sample;
    }

    /** Overriden method.
     */
    public function validate(FUserSession $userSession)
    {
        // compute data:

        if ($this->isRegistryNew) {
            $this->recept_datetime->setValue(FLibUtils::getLocalDatetime()); // set datetime before than number!
            $this->recept_num->setValue($this->generateNumber($userSession));
        }

        foreach ($this->childSamples as $sample) {
            $sample->setParentRecept($this);
        }

        $this->computeProcessDays();

        // validate registry:

        parent::validate($userSession);

        foreach ($this->childSamples as $sample) {
            $sample->validate($userSession);
        }
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM $this->tableName WHERE id_recept = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_recept"]);

            $this->id_recept->setValue($row["id_recept"]);
            $this->recept_num->setValue($row["recept_num"]);
            $this->recept_datetime->setValue($row["recept_datetime"]);
            $this->process_days->setValue($row["process_days"]);
            $this->process_start_date->setValue($row["process_start_date"]);
            $this->process_deadline->setValue($row["process_deadline"]);
            $this->recept_deadline->setValue($row["recept_deadline"]);
            $this->recept_deviats->setValue($row["recept_deviats"]);
            $this->recept_notes->setValue($row["recept_notes"]);
            $this->service_type->setValue($row["service_type"]);
            $this->ref_chain_custody->setValue($row["ref_chain_custody"]);
            $this->ref_request->setValue($row["ref_request"]);
            $this->ref_agreet->setValue($row["ref_agreet"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_company_branch->setValue($row["fk_company_branch"]);
            $this->fk_customer->setValue($row["fk_customer"]);
            $this->fk_recept_status->setValue($row["fk_recept_status"]);
            $this->fk_user_receiver->setValue($row["fk_user_receiver"]);
            $this->fk_user_ins->setValue($row["fk_user_ins"]);
            $this->fk_user_upd->setValue($row["fk_user_upd"]);
            $this->ts_user_ins->setValue($row["ts_user_ins"]);
            $this->ts_user_upd->setValue($row["ts_user_upd"]);

            $this->isRegistryNew = false;
            $this->mode = $mode;

            $orig_recept_datetime = $this->recept_datetime->getValue();
            $orig_fk_user_receiver = $this->fk_user_receiver->getValue();

            // create PDO connection for reading children:
            $pdo = FGuiUtils::createPdo();

            // read child samples:
            $sql = "SELECT id_sample FROM o_sample WHERE nk_recept = $this->id ORDER BY id_sample;";
            $statement = $pdo->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $sample = new ModSample();
                $sample->read($userSession, intval($row["id_sample"]), $mode);
                $this->childSamples[] = $sample;
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
                "id_recept, " .
                "recept_num, " .
                "recept_datetime, " .
                "process_days, " .
                "process_start_date, " .
                "process_deadline, " .
                "recept_deadline, " .
                "recept_deviats, " .
                "recept_notes, " .
                "service_type, " .
                "ref_chain_custody, " .
                "ref_request, " .
                "ref_agreet, " .
                "is_system, " .
                "is_deleted, " .
                "fk_company_branch, " .
                "fk_customer, " .
                "fk_recept_status, " .
                "fk_user_receiver, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":recept_num, " .
                ":recept_datetime, " .
                ":process_days, " .
                ":process_start_date, " .
                ":process_deadline, " .
                ":recept_deadline, " .
                ":recept_deviats, " .
                ":recept_notes, " .
                ":service_type, " .
                ":ref_chain_custody, " .
                ":ref_request, " .
                ":ref_agreet, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_company_branch, " .
                ":fk_customer, " .
                ":fk_recept_status, " .
                ":fk_user_receiver, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE $this->tableName SET " .
                "recept_num = :recept_num, " .
                "recept_datetime = :recept_datetime, " .
                "process_days = :process_days, " .
                "process_start_date = :process_start_date, " .
                "process_deadline = :process_deadline, " .
                "recept_deadline = :recept_deadline, " .
                "recept_deviats = :recept_deviats, " .
                "recept_notes = :recept_notes, " .
                "service_type = :service_type, " .
                "ref_chain_custody = :ref_chain_custody, " .
                "ref_request = :ref_request, " .
                "ref_agreet = :ref_agreet, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_company_branch = :fk_company_branch, " .
                "fk_customer = :fk_customer, " .
                "fk_recept_status = :fk_recept_status, " .
                "fk_user_receiver = :fk_user_receiver, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_recept = :id;");
        }

        //$id_recept = $this->id_recept->getValue();
        $recept_num = $this->recept_num->getValue();
        $recept_datetime = FLibUtils::formatStdDatetime($this->recept_datetime->getValue());
        $process_days = $this->process_days->getValue();
        $process_start_date = FLibUtils::formatStdDate($this->process_start_date->getValue());
        $process_deadline = FLibUtils::formatStdDate($this->process_deadline->getValue());
        $recept_deadline = FLibUtils::formatStdDate($this->recept_deadline->getValue());
        $recept_deviats = $this->recept_deviats->getValue();
        $recept_notes = $this->recept_notes->getValue();
        $service_type = $this->service_type->getValue();
        $ref_chain_custody = $this->ref_chain_custody->getValue();
        $ref_request = $this->ref_request->getValue();
        $ref_agreet = $this->ref_agreet->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_company_branch = $this->fk_company_branch->getValue();
        $fk_customer = $this->fk_customer->getValue();
        $fk_recept_status = $this->fk_recept_status->getValue();
        $fk_user_receiver = $this->fk_user_receiver->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_recept", $id_recept, \PDO::PARAM_INT);
        $statement->bindParam(":recept_num", $recept_num);
        $statement->bindParam(":recept_datetime", $recept_datetime);
        $statement->bindParam(":process_days", $process_days, \PDO::PARAM_INT);
        $statement->bindParam(":process_start_date", $process_start_date);
        $statement->bindParam(":process_deadline", $process_deadline);
        $statement->bindParam(":recept_deadline", $recept_deadline);
        $statement->bindParam(":recept_deviats", $recept_deviats);
        $statement->bindParam(":recept_notes", $recept_notes);
        $statement->bindParam(":service_type", $service_type);
        $statement->bindParam(":ref_chain_custody", $ref_chain_custody);
        $statement->bindParam(":ref_request", $ref_request);
        $statement->bindParam(":ref_agreet", $ref_agreet);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_company_branch", $fk_company_branch, \PDO::PARAM_INT);
        $statement->bindParam(":fk_customer", $fk_customer, \PDO::PARAM_INT);
        $statement->bindParam(":fk_recept_status", $fk_recept_status, \PDO::PARAM_INT);
        $statement->bindParam(":fk_user_receiver", $fk_user_receiver, \PDO::PARAM_INT);
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
            $this->isRegistryNew = false;
        }

        // save child samples:
        $num = 0;
        foreach ($this->childSamples as $sample) {
            // ensure link to parent and set system data:
            $data = array();
            $data["nk_recept"] = $this->id;
            $data["recept_sample"] = ++$num;
            if ($this->isRegistryNew() || $sample->isRegistryNew()) {
                $data["recept_datetime_n"] = $this->recept_datetime->getValue();
                $data["fk_user_receiver"] = $this->fk_user_receiver->getValue();
            }
            else {
                if ($sample->getDatum("recept_datetime_n") == $this->orig_recept_datetime && $this->orig_recept_datetime != $this->recept_datetime->getValue()) {
                    $data["recept_datetime_n"] = $this->recept_datetime->getValue(); // sample should have same value as reception
                }
                if ($sample->getDatum("fk_user_receiver") == $this->orig_fk_user_receiver && $this->orig_fk_user_receiver != $this->fk_user_receiver->getValue()) {
                    $data["fk_user_receiver"] = $this->fk_user_receiver->getValue(); // sample should have same value as reception
                }
            }
            $sample->setData($data);

            // save child:
            $sample->save($userSession);
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

        if (count($this->childSamples) > 0) {
            $this->childSamples[0]->resetAutoIncrement($userSession);
        }
    }
}
