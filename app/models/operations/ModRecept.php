<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\App\FGuiUtils;
use Fraphe\Lib\FUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class ModRecept extends FRegistry
{
    protected $id_recept;
    protected $number;
    protected $recept_datetime;
    protected $recept_temperat_n;
    protected $process_days;
    protected $process_deadline;
    protected $recept_deadline;
    protected $recept_deviats;
    protected $recept_notes;
    protected $service_type;
    protected $is_customer_custom;
    protected $customer_name;
    protected $customer_street;
    protected $customer_district;
    protected $customer_postcode;
    protected $customer_reference;
    protected $customer_city;
    protected $customer_county;
    protected $customer_state_region;
    protected $customer_country;
    protected $customer_contact;
    protected $is_def_sampling_img;
    protected $ref_chain_custody;
    protected $ref_request;
    protected $ref_agreet;
    protected $is_system;
    protected $is_deleted;
    protected $fk_company_branch;
    protected $fk_customer;
    protected $nk_customer_sample;
    protected $nk_customer_billing;
    protected $fk_report_contact;
    protected $fk_report_delivery_type;
    protected $fk_recept_status;
    protected $fk_user_receiver;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    protected $childSamples;

    function __construct()
    {
        parent::__construct(AppConsts::O_RECEPT, AppConsts::$tableIds[AppConsts::O_RECEPT]);

        $this->id_recept = new FItem(FItem::DATA_TYPE_INT, "id_recept", "ID recepción", "", false, true);
        $this->number = new FItem(FItem::DATA_TYPE_STRING, "number", "Folio recepción", "", true);
        $this->recept_datetime = new FItem(FItem::DATA_TYPE_DATETIME, "recept_datetime", "Fecha-hora recepción", "", true);
        $this->recept_temperat_n = new FItem(FItem::DATA_TYPE_FLOAT, "recept_temperat_n", "Temp. recepción °C", "", false);
        $this->process_days = new FItem(FItem::DATA_TYPE_INT, "process_days", "Días proceso", "", true);
        $this->process_deadline = new FItem(FItem::DATA_TYPE_DATE, "process_deadline", "Fecha límite proceso", "", true);
        $this->recept_deadline = new FItem(FItem::DATA_TYPE_DATE, "recept_deadline", "Fecha límite recepción", "", true);
        $this->recept_deviats = new FItem(FItem::DATA_TYPE_STRING, "recept_deviats", "Desviaciones recepción", "", false);
        $this->recept_notes = new FItem(FItem::DATA_TYPE_STRING, "recept_notes", "Observaciones recepción", "", false);
        $this->service_type = new FItem(FItem::DATA_TYPE_STRING, "service_type", "Tipo servicio", "", true);
        $this->is_customer_custom = new FItem(FItem::DATA_TYPE_BOOL, "is_customer_custom", "Cliente personalizado", "", false);
        $this->customer_name = new FItem(FItem::DATA_TYPE_STRING, "customer_name", "Nombre cliente", "", false);
        $this->customer_street = new FItem(FItem::DATA_TYPE_STRING, "customer_street", "Calle y número", "", false);
        $this->customer_district = new FItem(FItem::DATA_TYPE_STRING, "customer_district", "Colonia", "", false);
        $this->customer_postcode = new FItem(FItem::DATA_TYPE_STRING, "customer_postcode", "Código postal", "", false);
        $this->customer_reference = new FItem(FItem::DATA_TYPE_STRING, "customer_reference", "Referencia", "", false);
        $this->customer_city = new FItem(FItem::DATA_TYPE_STRING, "customer_city", "Localidad", "", false);
        $this->customer_county = new FItem(FItem::DATA_TYPE_STRING, "customer_county", "Municipio", "", false);
        $this->customer_state_region = new FItem(FItem::DATA_TYPE_STRING, "customer_state_region", "Estado", "", false);
        $this->customer_country = new FItem(FItem::DATA_TYPE_STRING, "customer_country", "País", "", false);
        $this->customer_contact = new FItem(FItem::DATA_TYPE_STRING, "customer_contact", "Contacto", "", false);
        $this->is_def_sampling_img = new FItem(FItem::DATA_TYPE_BOOL, "is_def_sampling_img", "Aplica imagen muestreo x def.", "", false);
        $this->ref_chain_custody = new FItem(FItem::DATA_TYPE_STRING, "ref_chain_custody", "Ref. cadena custodia", "", false);
        $this->ref_request = new FItem(FItem::DATA_TYPE_STRING, "ref_request", "Ref. solicitud ensayos", "", false);
        $this->ref_agreet = new FItem(FItem::DATA_TYPE_STRING, "ref_agreet", "Ref. convenio ensayos", "", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_company_branch = new FItem(FItem::DATA_TYPE_INT, "fk_company_branch", "Sucursal empresa", "", true);
        $this->fk_customer = new FItem(FItem::DATA_TYPE_INT, "fk_customer", "Cliente", "", true);
        $this->nk_customer_sample = new FItem(FItem::DATA_TYPE_INT, "nk_customer_sample", "Cliente muestra", "", false);
        $this->nk_customer_billing = new FItem(FItem::DATA_TYPE_INT, "nk_customer_billing", "Cliente facturación", "", false);
        $this->fk_report_contact = new FItem(FItem::DATA_TYPE_INT, "fk_report_contact", "Contacto IR", "", true);
        $this->fk_report_delivery_type = new FItem(FItem::DATA_TYPE_INT, "fk_report_delivery_type", "Tipo entrega IR", "", true);
        $this->fk_recept_status = new FItem(FItem::DATA_TYPE_INT, "fk_recept_status", "Estatus recepción", "", true);
        $this->fk_user_receiver = new FItem(FItem::DATA_TYPE_INT, "fk_user_receiver", "Receptor", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_recept"] = $this->id_recept;
        $this->items["number"] = $this->number;
        $this->items["recept_datetime"] = $this->recept_datetime;
        $this->items["recept_temperat_n"] = $this->recept_temperat_n;
        $this->items["process_days"] = $this->process_days;
        $this->items["process_deadline"] = $this->process_deadline;
        $this->items["recept_deadline"] = $this->recept_deadline;
        $this->items["recept_deviats"] = $this->recept_deviats;
        $this->items["recept_notes"] = $this->recept_notes;
        $this->items["service_type"] = $this->service_type;
        $this->items["is_customer_custom"] = $this->is_customer_custom;
        $this->items["customer_name"] = $this->customer_name;
        $this->items["customer_street"] = $this->customer_street;
        $this->items["customer_district"] = $this->customer_district;
        $this->items["customer_postcode"] = $this->customer_postcode;
        $this->items["customer_reference"] = $this->customer_reference;
        $this->items["customer_city"] = $this->customer_city;
        $this->items["customer_county"] = $this->customer_county;
        $this->items["customer_state_region"] = $this->customer_state_region;
        $this->items["customer_country"] = $this->customer_country;
        $this->items["customer_contact"] = $this->customer_contact;
        $this->items["is_def_sampling_img"] = $this->is_def_sampling_img;
        $this->items["ref_chain_custody"] = $this->ref_chain_custody;
        $this->items["ref_request"] = $this->ref_request;
        $this->items["ref_agreet"] = $this->ref_agreet;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_company_branch"] = $this->fk_company_branch;
        $this->items["fk_customer"] = $this->fk_customer;
        $this->items["nk_customer_sample"] = $this->nk_customer_sample;
        $this->items["nk_customer_billing"] = $this->nk_customer_billing;
        $this->items["fk_report_contact"] = $this->fk_report_contact;
        $this->items["fk_report_delivery_type"] = $this->fk_report_delivery_type;
        $this->items["fk_recept_status"] = $this->fk_recept_status;
        $this->items["fk_user_receiver"] = $this->fk_user_receiver;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->number->setRangeLength(1, 25);
        $this->recept_deviats->setRangeLength(0, 500);
        $this->recept_notes->setRangeLength(0, 500);
        $this->service_type->setRangeLength(1, 1);
        $this->customer_name->setRangeLength(1, 201);
        $this->customer_street->setRangeLength(0, 200);
        $this->customer_district->setRangeLength(0, 100);
        $this->customer_postcode->setRangeLength(1, 15);
        $this->customer_reference->setRangeLength(0, 100);
        $this->customer_city->setRangeLength(1, 50);
        $this->customer_county->setRangeLength(1, 50);
        $this->customer_state_region->setRangeLength(1, 50);
        $this->customer_country->setRangeLength(1, 3);
        $this->customer_contact->setRangeLength(0, 250);
        $this->ref_chain_custody->setRangeLength(0, 25);
        $this->ref_request->setRangeLength(0, 25);
        $this->ref_agreet->setRangeLength(0, 25);

        $this->clearChildSamples();
    }

    private function generateNumber(FUserSession $userSession): string
    {
        $count = 0;
        $date = FUtils::formatDbmsDate($this->recept_datetime->getValue());
        $sql = "SELECT COUNT(*) AS _count FROM oc_recept WHERE DATE(recept_datetime) = '$date';";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $count = intval($row["_count"]) + 1;
        }

        $nf = new \NumberFormatter($userSession->getLocLang(), \NumberFormatter::PATTERN_DECIMAL);
        $nf->setAttribute(\NumberFormatter::MIN_INTEGER_DIGITS, 4); // TODO: paremeterize this formatting argument: 4!

        return date("ymd", $this->recept_datetime->getValue()) & $nf->format($count); // TODO: parameterize this formatting argument: "ymd"!
    }

    public function &getChildSamples(): array
    {
        return $this->childSamples;
    }

    public function clearChildSamples()
    {
        $this->childSamples = array();
    }

    public function validate(FUserSession $userSession)
    {
        // validate registry:

        parent::validate($userSession);

        foreach ($this->childSamples as $sample) {
            $sample->validate($userSession);
        }
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM oc_recept WHERE id_recept = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_recept"]);

            $this->id_recept->setValue($row["id_recept"]);
            $this->number->setValue($row["number"]);
            $this->recept_datetime->setValue($row["recept_datetime"]);
            $this->recept_temperat_n->setValue($row["recept_temperat_n"]);
            $this->process_days->setValue($row["process_days"]);
            $this->process_deadline->setValue($row["process_deadline"]);
            $this->recept_deadline->setValue($row["recept_deadline"]);
            $this->recept_deviats->setValue($row["recept_deviats"]);
            $this->recept_notes->setValue($row["recept_notes"]);
            $this->service_type->setValue($row["service_type"]);
            $this->is_customer_custom->setValue($row["is_customer_custom"]);
            $this->customer_name->setValue($row["customer_name"]);
            $this->customer_street->setValue($row["customer_street"]);
            $this->customer_district->setValue($row["customer_district"]);
            $this->customer_postcode->setValue($row["customer_postcode"]);
            $this->customer_reference->setValue($row["customer_reference"]);
            $this->customer_city->setValue($row["customer_city"]);
            $this->customer_county->setValue($row["customer_county"]);
            $this->customer_state_region->setValue($row["customer_state_region"]);
            $this->customer_country->setValue($row["customer_country"]);
            $this->customer_contact->setValue($row["customer_contact"]);
            $this->is_def_sampling_img->setValue($row["is_def_sampling_img"]);
            $this->ref_chain_custody->setValue($row["ref_chain_custody"]);
            $this->ref_request->setValue($row["ref_request"]);
            $this->ref_agreet->setValue($row["ref_agreet"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_company_branch->setValue($row["fk_company_branch"]);
            $this->fk_customer->setValue($row["fk_customer"]);
            $this->nk_customer_sample->setValue($row["nk_customer_sample"]);
            $this->nk_customer_billing->setValue($row["nk_customer_billing"]);
            $this->fk_report_contact->setValue($row["fk_report_contact"]);
            $this->fk_report_delivery_type->setValue($row["fk_report_delivery_type"]);
            $this->fk_recept_status->setValue($row["fk_recept_status"]);
            $this->fk_user_receiver->setValue($row["fk_user_receiver"]);
            $this->fk_user_ins->setValue($row["fk_user_ins"]);
            $this->fk_user_upd->setValue($row["fk_user_upd"]);
            $this->ts_user_ins->setValue($row["ts_user_ins"]);
            $this->ts_user_upd->setValue($row["ts_user_upd"]);

            $this->isRegistryNew = false;
            $this->mode = $mode;

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
            // set data by system:
            $this->recept_datetime->setValue(time());
            $this->number->setValue(self::generateNumber($userSession));

            $statement = $userSession->getPdo()->prepare("INSERT INTO o_recept (" .
                "id_recept, " .
                "number, " .
                "recept_datetime, " .
                "recept_temperat_n, " .
                "process_days, " .
                "process_deadline, " .
                "recept_deadline, " .
                "recept_deviats, " .
                "recept_notes, " .
                "service_type, " .
                "is_customer_custom, " .
                "customer_name, " .
                "customer_street, " .
                "customer_district, " .
                "customer_postcode, " .
                "customer_reference, " .
                "customer_city, " .
                "customer_county, " .
                "customer_state_region, " .
                "customer_country, " .
                "customer_contact, " .
                "is_def_sampling_img, " .
                "ref_chain_custody, " .
                "ref_request, " .
                "ref_agreet, " .
                "is_system, " .
                "is_deleted, " .
                "fk_company_branch, " .
                "fk_customer, " .
                "nk_customer_sample, " .
                "nk_customer_billing, " .
                "fk_report_contact, " .
                "fk_report_delivery_type, " .
                "fk_recept_status, " .
                "fk_user_receiver, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":number, " .
                ":recept_datetime, " .
                ":recept_temperat_n, " .
                ":process_days, " .
                ":process_deadline, " .
                ":recept_deadline, " .
                ":recept_deviats, " .
                ":recept_notes, " .
                ":service_type, " .
                ":is_customer_custom, " .
                ":customer_name, " .
                ":customer_street, " .
                ":customer_district, " .
                ":customer_postcode, " .
                ":customer_reference, " .
                ":customer_city, " .
                ":customer_county, " .
                ":customer_state_region, " .
                ":customer_country, " .
                ":customer_contact, " .
                ":is_def_sampling_img, " .
                ":ref_chain_custody, " .
                ":ref_request, " .
                ":ref_agreet, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_company_branch, " .
                ":fk_customer, " .
                ":nk_customer_sample, " .
                ":nk_customer_billing, " .
                ":fk_report_contact, " .
                ":fk_report_delivery_type, " .
                ":fk_recept_status, " .
                ":fk_user_receiver, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE o_recept SET " .
                "number = :number, " .
                "recept_datetime = :recept_datetime, " .
                "recept_temperat_n = :recept_temperat_n, " .
                "process_days = :process_days, " .
                "process_deadline = :process_deadline, " .
                "recept_deadline = :recept_deadline, " .
                "recept_deviats = :recept_deviats, " .
                "recept_notes = :recept_notes, " .
                "service_type = :service_type, " .
                "is_customer_custom = :is_customer_custom, " .
                "customer_name = :customer_name, " .
                "customer_street = :customer_street, " .
                "customer_district = :customer_district, " .
                "customer_postcode = :customer_postcode, " .
                "customer_reference = :customer_reference, " .
                "customer_city = :customer_city, " .
                "customer_county = :customer_county, " .
                "customer_state_region = :customer_state_region, " .
                "customer_country = :customer_country, " .
                "customer_contact = :customer_contact, " .
                "is_def_sampling_img = :is_def_sampling_img, " .
                "ref_chain_custody = :ref_chain_custody, " .
                "ref_request = :ref_request, " .
                "ref_agreet = :ref_agreet, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_company_branch = :fk_company_branch, " .
                "fk_customer = :fk_customer, " .
                "nk_customer_sample = :nk_customer_sample, " .
                "nk_customer_billing = :nk_customer_billing, " .
                "fk_report_contact = :fk_report_contact, " .
                "fk_report_delivery_type = :fk_report_delivery_type, " .
                "fk_recept_status = :fk_recept_status, " .
                "fk_user_receiver = :fk_user_receiver, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_recept = :id;");
        }

        //$id_recept = $this->id_recept->getValue();
        $number = $this->number->getValue();
        $recept_datetime = FUtils::formatDbmsDatetime($this->recept_datetime->getValue());
        $recept_temperat_n = $this->recept_temperat_n->getValue();
        $process_days = $this->process_days->getValue();
        $process_deadline = FUtils::formatDbmsDate($this->process_deadline->getValue());
        $recept_deadline = FUtils::formatDbmsDate($this->recept_deadline->getValue());
        $recept_deviats = $this->recept_deviats->getValue();
        $recept_notes = $this->recept_notes->getValue();
        $service_type = $this->service_type->getValue();
        $is_customer_custom = $this->is_customer_custom->getValue();
        $customer_name = $this->customer_name->getValue();
        $customer_street = $this->customer_street->getValue();
        $customer_district = $this->customer_district->getValue();
        $customer_postcode = $this->customer_postcode->getValue();
        $customer_reference = $this->customer_reference->getValue();
        $customer_city = $this->customer_city->getValue();
        $customer_county = $this->customer_county->getValue();
        $customer_state_region = $this->customer_state_region->getValue();
        $customer_country = $this->customer_country->getValue();
        $customer_contact = $this->customer_contact->getValue();
        $is_def_sampling_img = $this->is_def_sampling_img->getValue();
        $ref_chain_custody = $this->ref_chain_custody->getValue();
        $ref_request = $this->ref_request->getValue();
        $ref_agreet = $this->ref_agreet->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_company_branch = $this->fk_company_branch->getValue();
        $fk_customer = $this->fk_customer->getValue();
        $nk_customer_sample = $this->nk_customer_sample->getValue();
        $nk_customer_billing = $this->nk_customer_billing->getValue();
        $fk_report_contact = $this->fk_report_contact->getValue();
        $fk_report_delivery_type = $this->fk_report_delivery_type->getValue();
        $fk_recept_status = $this->fk_recept_status->getValue();
        $fk_user_receiver = $this->fk_user_receiver->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_recept", $id_recept, \PDO::PARAM_INT);
        $statement->bindParam(":number", $number);
        $statement->bindParam(":recept_datetime", $recept_datetime);
        if (empty($recept_temperat_n)) {
            $statement->bindValue(":recept_temperat_n", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":recept_temperat_n", $recept_temperat_n);
        }
        $statement->bindParam(":process_days", $process_days, \PDO::PARAM_INT);
        $statement->bindParam(":process_deadline", $process_deadline);
        $statement->bindParam(":recept_deadline", $recept_deadline);
        $statement->bindParam(":recept_deviats", $recept_deviats);
        $statement->bindParam(":recept_notes", $recept_notes);
        $statement->bindParam(":service_type", $service_type);
        $statement->bindParam(":is_customer_custom", $is_customer_custom, \PDO::PARAM_BOOL);
        $statement->bindParam(":customer_name", $customer_name);
        $statement->bindParam(":customer_street", $customer_street);
        $statement->bindParam(":customer_district", $customer_district);
        $statement->bindParam(":customer_postcode", $customer_postcode);
        $statement->bindParam(":customer_reference", $customer_reference);
        $statement->bindParam(":customer_city", $customer_city);
        $statement->bindParam(":customer_county", $customer_county);
        $statement->bindParam(":customer_state_region", $customer_state_region);
        $statement->bindParam(":customer_country", $customer_country);
        $statement->bindParam(":customer_contact", $customer_contact);
        $statement->bindParam(":is_def_sampling_img", $is_def_sampling_img, \PDO::PARAM_BOOL);
        $statement->bindParam(":ref_chain_custody", $ref_chain_custody);
        $statement->bindParam(":ref_request", $ref_request);
        $statement->bindParam(":ref_agreet", $ref_agreet);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_company_branch", $fk_company_branch, \PDO::PARAM_INT);
        $statement->bindParam(":fk_customer", $fk_customer, \PDO::PARAM_INT);
        if (empty($nk_customer_sample)) {
            $statement->bindValue(":nk_customer_sample", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":nk_customer_sample", $nk_customer_sample, \PDO::PARAM_INT);
        }
        if (empty($nk_customer_billing)) {
            $statement->bindParam(":nk_customer_billing", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":nk_customer_billing", $nk_customer_billing, \PDO::PARAM_INT);
        }
        $statement->bindParam(":fk_report_contact", $fk_report_contact, \PDO::PARAM_INT);
        $statement->bindParam(":fk_report_delivery_type", $fk_report_delivery_type, \PDO::PARAM_INT);
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
            // assure link to parent and set other data:
            $data = array();
            $data["nk_recept"] = $this->id;
            $data["recept_sample"] = ++$num;
            $data["recept_datetime_n"] = $this->recept_datetime;
            $data["fk_user_receiver"] = $this->fk_user_receiver;
            $sample->setData($data);
            $sample->setParentRecept($this);

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
}
