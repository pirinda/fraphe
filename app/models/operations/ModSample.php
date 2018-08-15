<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\App\FGuiUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class ModSample extends FRegistry
{
    protected $id_sample;
    protected $number;
    protected $name;
    protected $lot;
    protected $date_manuf_n;
    protected $date_sell_by_n;
    protected $quantity_original;
    protected $quantity;
    protected $is_sampling_company;
    protected $sampling_guide;
    protected $sampling_area;
    protected $sampling_images;
    protected $sampling_datetime_n;
    protected $sampling_temperat_n;
    protected $sampling_notes;
    protected $sampling_deviats;
    protected $recept_datetime;
    protected $recept_temperat;
    protected $recept_notes;
    protected $recept_deviats;
    protected $sample_child;
    protected $sample_released;
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
    protected $is_def_report_images;
    protected $ref_chain_custody;
    protected $ref_request;
    protected $ref_agreet;
    protected $recept_entry;
    protected $is_system;
    protected $is_deleted;
    protected $fk_company_branch;
    protected $fk_customer;
    protected $nk_customer_sample;
    protected $nk_customer_billing;
    protected $fk_report_contact;
    protected $fk_report_delivery_opt;
    protected $fk_sample_class;
    protected $fk_sample_type;
    protected $fk_sample_status;
    protected $nk_sample_parent;
    protected $fk_container_type;
    protected $fk_container_unit;
    protected $fk_sampling_method;
    protected $nk_sampling_equipt;
    protected $nk_recept;
    protected $fk_user_sampler;
    protected $fk_user_receiver;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    protected $childSampleTests;
    protected $childSampleStatusLogs;

    function __construct()
    {
        parent::__construct(AppConsts::O_SAMPLE, AppConsts::$tableIds[AppConsts::O_SAMPLE]);

        $this->id_sample = new FItem(FItem::DATA_TYPE_INT, "id_sample", "ID muestra", "", false, true);
        $this->number = new FItem(FItem::DATA_TYPE_STRING, "number", "Folio muestra", "", true);
        $this->name = new FItem(FItem::DATA_TYPE_STRING, "name", "Nombre muestra", "", true);
        $this->lot = new FItem(FItem::DATA_TYPE_STRING, "lot", "Lote muestra", "", false);
        $this->date_manuf_n = new FItem(FItem::DATA_TYPE_DATE, "date_manuf_n", "Fecha producción", "", false);
        $this->date_sell_by_n = new FItem(FItem::DATA_TYPE_DATE, "date_sell_by_n", "Fecha caducidad", "", false);
        $this->quantity_original = new FItem(FItem::DATA_TYPE_FLOAT, "quantity_original", "Cantidad muestra original", "", true);
        $this->quantity = new FItem(FItem::DATA_TYPE_FLOAT, "quantity", "Cantidad muestra", "", true);
        $this->is_sampling_company = new FItem(FItem::DATA_TYPE_INT, "is_sampling_company", "Muestreo propio", "", true);
        $this->sampling_guide = new FItem(FItem::DATA_TYPE_INT, "sampling_guide", "Número guía muestreo", "", true);
        $this->sampling_area = new FItem(FItem::DATA_TYPE_STRING, "sampling_area", "Área muestreo", "", true);
        $this->sampling_images = new FItem(FItem::DATA_TYPE_INT, "sampling_images", "Imágenes muestreo", "", true);
        $this->sampling_datetime_n = new FItem(FItem::DATA_TYPE_DATETIME, "sampling_datetime_n", "Fecha-hora muestreo", "", false);
        $this->sampling_temperat_n = new FItem(FItem::DATA_TYPE_FLOAT, "sampling_temperat_n", "Temperatura muestreo", "", false);
        $this->sampling_notes = new FItem(FItem::DATA_TYPE_STRING, "sampling_notes", "Observaciones muestreo", "", false);
        $this->sampling_deviats = new FItem(FItem::DATA_TYPE_STRING, "sampling_deviats", "Desviaciones muestreo", "", false);
        $this->recept_datetime = new FItem(FItem::DATA_TYPE_DATETIME, "recept_datetime", "Fecha-hora recepción", "", true);
        $this->recept_temperat = new FItem(FItem::DATA_TYPE_FLOAT, "recept_temperat", "Temperatura recepción", "", true);
        $this->recept_notes = new FItem(FItem::DATA_TYPE_STRING, "recept_notes", "Observaciones recepción", "", false);
        $this->recept_deviats = new FItem(FItem::DATA_TYPE_STRING, "recept_deviats", "Desviaciones recepción", "", false);
        $this->sample_child = new FItem(FItem::DATA_TYPE_INT, "sample_child", "Muestra hijo número", "", true);
        $this->sample_released = new FItem(FItem::DATA_TYPE_STRING, "sample_released", "Muestra liberada", "", false);
        $this->service_type = new FItem(FItem::DATA_TYPE_STRING, "service_type", "Tipo servicio", "", true);
        $this->is_customer_custom = new FItem(FItem::DATA_TYPE_BOOL, "is_customer_custom", "Cliente personalizado", "", false);
        $this->customer_name = new FItem(FItem::DATA_TYPE_STRING, "customer_name", "Nombre cliente", "", false);
        $this->customer_street = new FItem(FItem::DATA_TYPE_STRING, "customer_street", "Calle y número", "", false);
        $this->customer_district = new FItem(FItem::DATA_TYPE_STRING, "customer_district", "Colonia", "", false);
        $this->customer_postcode = new FItem(FItem::DATA_TYPE_STRING, "customer_postcode", "Código postal", "", false);
        $this->customer_reference = new FItem(FItem::DATA_TYPE_STRING, "customer_reference", "Referencia", "", false);
        $this->customer_city = new FItem(FItem::DATA_TYPE_STRING, "customer_city", "Localidad", "", false);
        $this->customer_county = new FItem(FItem::DATA_TYPE_STRING, "customer_county", "Municipio", "", true);
        $this->customer_state_region = new FItem(FItem::DATA_TYPE_STRING, "customer_state_region", "Estado", "", true);
        $this->customer_country = new FItem(FItem::DATA_TYPE_STRING, "customer_country", "País", "", true);
        $this->customer_contact = new FItem(FItem::DATA_TYPE_STRING, "customer_contact", "Contacto", "", false);
        $this->is_def_report_images = new FItem(FItem::DATA_TYPE_BOOL, "is_def_report_images", "Imágenes IR por defecto", "", false);
        $this->ref_chain_custody = new FItem(FItem::DATA_TYPE_STRING, "ref_chain_custody", "Ref. cadena custodia", "", false);
        $this->ref_request = new FItem(FItem::DATA_TYPE_STRING, "ref_request", "Ref. solicitud ensayos", "", false);
        $this->ref_agreet = new FItem(FItem::DATA_TYPE_STRING, "ref_agreet", "Ref. convenio ensayos", "", false);
        $this->recept_entry = new FItem(FItem::DATA_TYPE_INT, "recept_entry", "Partida recepción", "", true);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_company_branch = new FItem(FItem::DATA_TYPE_INT, "fk_company_branch", "Sucursal empresa", "", true);
        $this->fk_customer = new FItem(FItem::DATA_TYPE_INT, "fk_customer", "Cliente", "", true);
        $this->nk_customer_sample = new FItem(FItem::DATA_TYPE_INT, "nk_customer_sample", "Cliente muestra", "", false);
        $this->nk_customer_billing = new FItem(FItem::DATA_TYPE_INT, "nk_customer_billing", "Cliente facturación", "", false);
        $this->fk_report_contact = new FItem(FItem::DATA_TYPE_INT, "fk_report_contact", "Contacto IR", "", true);
        $this->fk_report_delivery_opt = new FItem(FItem::DATA_TYPE_INT, "fk_report_delivery_opt", "Opción entrega IR", "", true);
        $this->fk_sample_class = new FItem(FItem::DATA_TYPE_INT, "fk_sample_class", "Clase muestra", "", true);
        $this->fk_sample_type = new FItem(FItem::DATA_TYPE_INT, "fk_sample_type", "Tipo muestra", "", true);
        $this->fk_sample_status = new FItem(FItem::DATA_TYPE_INT, "fk_sample_status", "Estatus muestra", "", true);
        $this->nk_sample_parent = new FItem(FItem::DATA_TYPE_INT, "nk_sample_parent", "Muestra padre", "", false);
        $this->fk_container_type = new FItem(FItem::DATA_TYPE_INT, "fk_container_type", "Tipo envase", "", true);
        $this->fk_container_unit = new FItem(FItem::DATA_TYPE_INT, "fk_container_unit", "Unidad medida envase", "", true);
        $this->fk_sampling_method = new FItem(FItem::DATA_TYPE_INT, "fk_sampling_method", "Método muestreo", "", true);
        $this->nk_sampling_equipt = new FItem(FItem::DATA_TYPE_INT, "nk_sampling_equipt", "Equipo muestreo", "", false);
        $this->nk_recept = new FItem(FItem::DATA_TYPE_INT, "nk_recept", "Recepción", "", false);
        $this->fk_user_sampler = new FItem(FItem::DATA_TYPE_INT, "fk_user_sampler", "Muestreador", "", true);
        $this->fk_user_receiver = new FItem(FItem::DATA_TYPE_INT, "fk_user_receiver", "Receptor", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_sample"] = $this->id_sample;
        $this->items["number"] = $this->number;
        $this->items["name"] = $this->name;
        $this->items["lot"] = $this->lot;
        $this->items["date_manuf_n"] = $this->date_manuf_n;
        $this->items["date_sell_by_n"] = $this->date_sell_by_n;
        $this->items["quantity_original"] = $this->quantity_original;
        $this->items["quantity"] = $this->quantity;
        $this->items["is_sampling_company"] = $this->is_sampling_company;
        $this->items["sampling_guide"] = $this->sampling_guide;
        $this->items["sampling_area"] = $this->sampling_area;
        $this->items["sampling_images"] = $this->sampling_images;
        $this->items["sampling_datetime_n"] = $this->sampling_datetime_n;
        $this->items["sampling_temperat_n"] = $this->sampling_temperat_n;
        $this->items["sampling_notes"] = $this->sampling_notes;
        $this->items["sampling_deviats"] = $this->sampling_deviats;
        $this->items["recept_datetime"] = $this->recept_datetime;
        $this->items["recept_temperat"] = $this->recept_temperat;
        $this->items["recept_notes"] = $this->recept_notes;
        $this->items["recept_deviats"] = $this->recept_deviats;
        $this->items["sample_child"] = $this->sample_child;
        $this->items["sample_released"] = $this->sample_released;
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
        $this->items["is_def_report_images"] = $this->is_def_report_images;
        $this->items["ref_chain_custody"] = $this->ref_chain_custody;
        $this->items["ref_request"] = $this->ref_request;
        $this->items["ref_agreet"] = $this->ref_agreet;
        $this->items["recept_entry"] = $this->recept_entry;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_company_branch"] = $this->fk_company_branch;
        $this->items["fk_customer"] = $this->fk_customer;
        $this->items["nk_customer_sample"] = $this->nk_customer_sample;
        $this->items["nk_customer_billing"] = $this->nk_customer_billing;
        $this->items["fk_report_contact"] = $this->fk_report_contact;
        $this->items["fk_report_delivery_opt"] = $this->fk_report_delivery_opt;
        $this->items["fk_sample_class"] = $this->fk_sample_class;
        $this->items["fk_sample_type"] = $this->fk_sample_type;
        $this->items["fk_sample_status"] = $this->fk_sample_status;
        $this->items["nk_sample_parent"] = $this->nk_sample_parent;
        $this->items["fk_container_type"] = $this->fk_container_type;
        $this->items["fk_container_unit"] = $this->fk_container_unit;
        $this->items["fk_sampling_method"] = $this->fk_sampling_method;
        $this->items["nk_sampling_equipt"] = $this->nk_sampling_equipt;
        $this->items["nk_recept"] = $this->nk_recept;
        $this->items["fk_user_sampler"] = $this->fk_user_sampler;
        $this->items["fk_user_receiver"] = $this->fk_user_receiver;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->number->setRangeLength(1, 25);
        $this->name->setRangeLength(1, 100);
        $this->lot->setRangeLength(0, 50);
        $this->sampling_area->setRangeLength(1, 100);
        $this->sampling_notes->setRangeLength(0, 500);
        $this->sampling_deviats->setRangeLength(0, 500);
        $this->recept_notes->setRangeLength(0, 500);
        $this->recept_deviats->setRangeLength(0, 500);
        $this->sample_released->setRangeLength(0, 1);
        $this->service_type->setRangeLength(1, 1);
        $this->customer_name->setRangeLength(0, 201);
        $this->customer_street->setRangeLength(0, 200);
        $this->customer_district->setRangeLength(0, 100);
        $this->customer_postcode->setRangeLength(0, 15);
        $this->customer_reference->setRangeLength(0, 100);
        $this->customer_city->setRangeLength(0, 100);
        $this->customer_county->setRangeLength(1, 50);
        $this->customer_state_region->setRangeLength(1, 50);
        $this->customer_country->setRangeLength(1, 3);
        $this->customer_contact->setRangeLength(0, 250);
        $this->ref_chain_custody->setRangeLength(0, 25);
        $this->ref_request->setRangeLength(0, 25);
        $this->ref_agreet->setRangeLength(0, 25);

        $this->clearChildSampleTests();
        $this->clearChildSampleStatusLogs();
    }

    public function &getChildSampleTests(): array
    {
        return $this->childSampleTests;
    }

    public function &getChildSampleStatusLogs(): array
    {
        return $this->$childSampleStatusLogs;
    }

    public function clearChildSampleTests()
    {
        $this->childSampleTests = array();
    }

    public function clearChildSampleStatusLogs()
    {
        $this->childSampleStatusLogs = array();
    }

    public function validate(FUserSession $userSession)
    {
        // validate registry:

        parent::validate($userSession);

        foreach ($this->childSampleTests as $sampleTest) {
            $ids = array();
            $ids["id_sample"] = $this->isRegistryNew ? -1 : $this->id;    // bypass validation
            $processOpt->setIds($ids);
            $processOpt->validate($userSession);
        }
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM oc_sample WHERE id_sample = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_sample"]);

            $this->id_sample->setValue($row["id_sample"]);
            $this->number->setValue($row["number"]);
            $this->name->setValue($row["name"]);
            $this->lot->setValue($row["lot"]);
            $this->date_manuf_n->setValue($row["date_manuf_n"]);
            $this->date_sell_by_n->setValue($row["date_sell_by_n"]);
            $this->quantity_original->setValue($row["quantity_original"]);
            $this->quantity->setValue($row["quantity"]);
            $this->is_sampling_company->setValue($row["is_sampling_company"]);
            $this->sampling_guide->setValue($row["sampling_guide"]);
            $this->sampling_area->setValue($row["sampling_area"]);
            $this->sampling_images->setValue($row["sampling_images"]);
            $this->sampling_datetime_n->setValue($row["sampling_datetime_n"]);
            $this->sampling_temperat_n->setValue($row["sampling_temperat_n"]);
            $this->sampling_notes->setValue($row["sampling_notes"]);
            $this->sampling_deviats->setValue($row["sampling_deviats"]);
            $this->recept_datetime->setValue($row["recept_datetime"]);
            $this->recept_temperat->setValue($row["recept_temperat"]);
            $this->recept_notes->setValue($row["recept_notes"]);
            $this->recept_deviats->setValue($row["recept_deviats"]);
            $this->sample_child->setValue($row["sample_child"]);
            $this->sample_released->setValue($row["sample_released"]);
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
            $this->is_def_report_images->setValue($row["is_def_report_images"]);
            $this->ref_chain_custody->setValue($row["ref_chain_custody"]);
            $this->ref_request->setValue($row["ref_request"]);
            $this->ref_agreet->setValue($row["ref_agreet"]);
            $this->recept_entry->setValue($row["recept_entry"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_company_branch->setValue($row["fk_company_branch"]);
            $this->fk_customer->setValue($row["fk_customer"]);
            $this->nk_customer_sample->setValue($row["nk_customer_sample"]);
            $this->nk_customer_billing->setValue($row["nk_customer_billing"]);
            $this->fk_report_contact->setValue($row["fk_report_contact"]);
            $this->fk_report_delivery_opt->setValue($row["fk_report_delivery_opt"]);
            $this->fk_sample_class->setValue($row["fk_sample_class"]);
            $this->fk_sample_type->setValue($row["fk_sample_type"]);
            $this->fk_sample_status->setValue($row["fk_sample_status"]);
            $this->nk_sample_parent->setValue($row["nk_sample_parent"]);
            $this->fk_container_type->setValue($row["fk_container_type"]);
            $this->fk_container_unit->setValue($row["fk_container_unit"]);
            $this->fk_sampling_method->setValue($row["fk_sampling_method"]);
            $this->nk_sampling_equipt->setValue($row["nk_sampling_equipt"]);
            $this->nk_recept->setValue($row["nk_recept"]);
            $this->fk_user_sampler->setValue($row["fk_user_sampler"]);
            $this->fk_user_receiver->setValue($row["fk_user_receiver"]);
            $this->fk_user_ins->setValue($row["fk_user_ins"]);
            $this->fk_user_upd->setValue($row["fk_user_upd"]);
            $this->ts_user_ins->setValue($row["ts_user_ins"]);
            $this->ts_user_upd->setValue($row["ts_user_upd"]);

            $this->isRegistryNew = false;
            $this->mode = $mode;

            // create PDO connection for reading children:
            $pdo = FGuiUtils::createPdo();

            // read child sample tests:
            $sql = "SELECT id_sample, id_test, id_entity FROM o_sample_testt WHERE id_sample = $this->id ORDER BY id_sample, id_test, id_entity;";
            $statement = $pdo->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $ids = array();
                $ids["id_sample"] = intval($row["id_sample"]);
                $ids["id_test"] = intval($row["id_test"]);
                $ids["id_entity"] = intval($row["id_entity"]);

                $sampleTest = new ModSampleTest();
                $sampleTest->retrieve($userSession, $ids, $mode);
                $this->childSampleTests[] = $sampleTest;
            }

            // read child sample status log entries:
            $sql = "SELECT id_sample_status_log FROM o_sample_status_log WHERE fk_sample = $this->id ORDER BY id_sample_status_log;";
            $statement = $pdo->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $sampleStatusLog = new ModSampleStatusLog();
                $sampleStatusLog->read($userSession, intval($row["id_sample_status_log"]), $mode);
                $this->childSampleStatusLogs[] = $sampleStatusLog;
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
            $statement = $userSession->getPdo()->prepare("INSERT INTO o_sample (" .
                "id_sample, " .
                "number, " .
                "name, " .
                "lot, " .
                "date_manuf_n, " .
                "date_sell_by_n, " .
                "quantity_original, " .
                "quantity, " .
                "is_sampling_company, " .
                "sampling_guide, " .
                "sampling_area, " .
                "sampling_images, " .
                "sampling_datetime_n, " .
                "sampling_temperat_n, " .
                "sampling_notes, " .
                "sampling_deviats, " .
                "recept_datetime, " .
                "recept_temperat, " .
                "recept_notes, " .
                "recept_deviats, " .
                "sample_child, " .
                "sample_released, " .
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
                "is_def_report_images, " .
                "ref_chain_custody, " .
                "ref_request, " .
                "ref_agreet, " .
                "recept_entry, " .
                "is_system, " .
                "is_deleted, " .
                "fk_company_branch, " .
                "fk_customer, " .
                "nk_customer_sample, " .
                "nk_customer_billing, " .
                "fk_report_contact, " .
                "fk_report_delivery_opt, " .
                "fk_sample_class, " .
                "fk_sample_type, " .
                "fk_sample_status, " .
                "nk_sample_parent, " .
                "fk_container_type, " .
                "fk_container_unit, " .
                "fk_sampling_method, " .
                "nk_sampling_equipt, " .
                "nk_recept, " .
                "fk_user_sampler, " .
                "fk_user_receiver, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":number, " .
                ":name, " .
                ":lot, " .
                ":date_manuf_n, " .
                ":date_sell_by_n, " .
                ":quantity_original, " .
                ":quantity, " .
                ":is_sampling_company, " .
                ":sampling_guide, " .
                ":sampling_area, " .
                ":sampling_images, " .
                ":sampling_datetime_n, " .
                ":sampling_temperat_n, " .
                ":sampling_notes, " .
                ":sampling_deviats, " .
                ":recept_datetime, " .
                ":recept_temperat, " .
                ":recept_notes, " .
                ":recept_deviats, " .
                ":sample_child, " .
                ":sample_released, " .
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
                ":is_def_report_images, " .
                ":ref_chain_custody, " .
                ":ref_request, " .
                ":ref_agreet, " .
                ":recept_entry, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_company_branch, " .
                ":fk_customer, " .
                ":nk_customer_sample, " .
                ":nk_customer_billing, " .
                ":fk_report_contact, " .
                ":fk_report_delivery_opt, " .
                ":fk_sample_class, " .
                ":fk_sample_type, " .
                ":fk_sample_status, " .
                ":nk_sample_parent, " .
                ":fk_container_type, " .
                ":fk_container_unit, " .
                ":fk_sampling_method, " .
                ":nk_sampling_equipt, " .
                ":nk_recept, " .
                ":fk_user_sampler, " .
                ":fk_user_receiver, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE o_sample SET " .
                "number = :number, " .
                "name = :name, " .
                "lot = :lot, " .
                "date_manuf_n = :date_manuf_n, " .
                "date_sell_by_n = :date_sell_by_n, " .
                "quantity_original = :quantity_original, " .
                "quantity = :quantity, " .
                "is_sampling_company = :is_sampling_company, " .
                "sampling_guide = :sampling_guide, " .
                "sampling_area = :sampling_area, " .
                "sampling_images = :sampling_images, " .
                "sampling_datetime_n = :sampling_datetime_n, " .
                "sampling_temperat_n = :sampling_temperat_n, " .
                "sampling_notes = :sampling_notes, " .
                "sampling_deviats = :sampling_deviats, " .
                "recept_datetime = :recept_datetime, " .
                "recept_temperat = :recept_temperat, " .
                "recept_notes = :recept_notes, " .
                "recept_deviats = :recept_deviats, " .
                "sample_child = :sample_child, " .
                "sample_released = :sample_released, " .
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
                "is_def_report_images = :is_def_report_images, " .
                "ref_chain_custody = :ref_chain_custody, " .
                "ref_request = :ref_request, " .
                "ref_agreet = :ref_agreet, " .
                "recept_entry = :recept_entry, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_company_branch = :fk_company_branch, " .
                "fk_customer = :fk_customer, " .
                "nk_customer_sample = :nk_customer_sample, " .
                "nk_customer_billing = :nk_customer_billing, " .
                "fk_report_contact = :fk_report_contact, " .
                "fk_report_delivery_opt = :fk_report_delivery_opt, " .
                "fk_sample_class = :fk_sample_class, " .
                "fk_sample_type = :fk_sample_type, " .
                "fk_sample_status = :fk_sample_status, " .
                "nk_sample_parent = :nk_sample_parent, " .
                "fk_container_type = :fk_container_type, " .
                "fk_container_unit = :fk_container_unit, " .
                "fk_sampling_method = :fk_sampling_method, " .
                "nk_sampling_equipt = :nk_sampling_equipt, " .
                "nk_recept = :nk_recept, " .
                "fk_user_sampler = :fk_user_sampler, " .
                "fk_user_receiver = :fk_user_receiver, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_sample = :id;");
        }

        //$id_sample = $this->id_sample->getValue();
        $number = $this->number->getValue();
        $name = $this->name->getValue();
        $lot = $this->lot->getValue();
        $date_manuf_n = $this->date_manuf_n->getValue();
        $date_sell_by_n = $this->date_sell_by_n->getValue();
        $quantity_original = $this->quantity_original->getValue();
        $quantity = $this->quantity->getValue();
        $is_sampling_company = $this->is_sampling_company->getValue();
        $sampling_guide = $this->sampling_guide->getValue();
        $sampling_area = $this->sampling_area->getValue();
        $sampling_images = $this->sampling_images->getValue();
        $sampling_datetime_n = $this->sampling_datetime_n->getValue();
        $sampling_temperat_n = $this->sampling_temperat_n->getValue();
        $sampling_notes = $this->sampling_notes->getValue();
        $sampling_deviats = $this->sampling_deviats->getValue();
        $recept_datetime = $this->recept_datetime->getValue();
        $recept_temperat = $this->recept_temperat->getValue();
        $recept_notes = $this->recept_notes->getValue();
        $recept_deviats = $this->recept_deviats->getValue();
        $sample_child = $this->sample_child->getValue();
        $sample_released = $this->sample_released->getValue();
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
        $is_def_report_images = $this->is_def_report_images->getValue();
        $ref_chain_custody = $this->ref_chain_custody->getValue();
        $ref_request = $this->ref_request->getValue();
        $ref_agreet = $this->ref_agreet->getValue();
        $recept_entry = $this->recept_entry->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_company_branch = $this->fk_company_branch->getValue();
        $fk_customer = $this->fk_customer->getValue();
        $nk_customer_sample = $this->nk_customer_sample->getValue();
        $nk_customer_billing = $this->nk_customer_billing->getValue();
        $fk_report_contact = $this->fk_report_contact->getValue();
        $fk_report_delivery_opt = $this->fk_report_delivery_opt->getValue();
        $fk_sample_class = $this->fk_sample_class->getValue();
        $fk_sample_type = $this->fk_sample_type->getValue();
        $fk_sample_status = $this->fk_sample_status->getValue();
        $nk_sample_parent = $this->nk_sample_parent->getValue();
        $fk_container_type = $this->fk_container_type->getValue();
        $fk_container_unit = $this->fk_container_unit->getValue();
        $fk_sampling_method = $this->fk_sampling_method->getValue();
        $nk_sampling_equipt = $this->nk_sampling_equipt->getValue();
        $nk_recept = $this->nk_recept->getValue();
        $fk_user_sampler = $this->fk_user_sampler->getValue();
        $fk_user_receiver = $this->fk_user_receiver->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_sample", $id_sample, \PDO::PARAM_INT);
        $statement->bindParam(":number", $number);
        $statement->bindParam(":name", $name);
        $statement->bindParam(":lot", $lot);
        $statement->bindParam(":date_manuf_n", $date_manuf_n);
        $statement->bindParam(":date_sell_by_n", $date_sell_by_n);
        $statement->bindParam(":quantity_original", $quantity_original);
        $statement->bindParam(":quantity", $quantity);
        $statement->bindParam(":is_sampling_company", $is_sampling_company, \PDO::PARAM_INT);
        $statement->bindParam(":sampling_guide", $sampling_guide, \PDO::PARAM_INT);
        $statement->bindParam(":sampling_area", $sampling_area);
        $statement->bindParam(":sampling_images", $sampling_images, \PDO::PARAM_INT);
        $statement->bindParam(":sampling_datetime_n", $sampling_datetime_n);
        $statement->bindParam(":sampling_temperat_n", $sampling_temperat_n);
        $statement->bindParam(":sampling_notes", $sampling_notes);
        $statement->bindParam(":sampling_deviats", $sampling_deviats);
        $statement->bindParam(":recept_datetime", $recept_datetime);
        $statement->bindParam(":recept_temperat", $recept_temperat);
        $statement->bindParam(":recept_notes", $recept_notes);
        $statement->bindParam(":recept_deviats", $recept_deviats);
        $statement->bindParam(":sample_child", $sample_child, \PDO::PARAM_INT);
        $statement->bindParam(":sample_released", $sample_released);
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
        $statement->bindParam(":is_def_report_images", $is_def_report_images, \PDO::PARAM_BOOL);
        $statement->bindParam(":ref_chain_custody", $ref_chain_custody);
        $statement->bindParam(":ref_request", $ref_request);
        $statement->bindParam(":ref_agreet", $ref_agreet);
        $statement->bindParam(":recept_entry", $recept_entry, \PDO::PARAM_INT);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_company_branch", $fk_company_branch, \PDO::PARAM_INT);
        $statement->bindParam(":fk_customer", $fk_customer, \PDO::PARAM_INT);
        $statement->bindParam(":nk_customer_sample", $nk_customer_sample, \PDO::PARAM_INT);
        $statement->bindParam(":nk_customer_billing", $nk_customer_billing, \PDO::PARAM_INT);
        $statement->bindParam(":fk_report_contact", $fk_report_contact, \PDO::PARAM_INT);
        $statement->bindParam(":fk_report_delivery_opt", $fk_report_delivery_opt, \PDO::PARAM_INT);
        $statement->bindParam(":fk_sample_class", $fk_sample_class, \PDO::PARAM_INT);
        $statement->bindParam(":fk_sample_type", $fk_sample_type, \PDO::PARAM_INT);
        $statement->bindParam(":fk_sample_status", $fk_sample_status, \PDO::PARAM_INT);
        $statement->bindParam(":nk_sample_parent", $nk_sample_parent, \PDO::PARAM_INT);
        $statement->bindParam(":fk_container_type", $fk_container_type, \PDO::PARAM_INT);
        $statement->bindParam(":fk_container_unit", $fk_container_unit, \PDO::PARAM_INT);
        $statement->bindParam(":fk_sampling_method", $fk_sampling_method, \PDO::PARAM_INT);
        $statement->bindParam(":nk_sampling_equipt", $nk_sampling_equipt, \PDO::PARAM_INT);
        $statement->bindParam(":nk_recept", $nk_recept, \PDO::PARAM_INT);
        $statement->bindParam(":fk_user_sampler", $fk_user_sampler, \PDO::PARAM_INT);
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

        // save child sample tests:
        $userSession->getPdo()->exec("DELETE FROM o_sample_test WHERE id_sample = $this->id;"); // pure relations
        foreach ($this->childSampleTests as $sampleTest) {
            $ids = array();
            $ids["id_sample"] = $this->id;

            $sampleTest->setIds($ids);
            $sampleTest->forceRegistryNew();    // it is a pure relation
            $sampleTest->save($userSession);
        }

        // save child sample status log entries:
        foreach ($this->childSampleStatusLogs as $sampleStatusLog) {
            // assure link to parent:
            $data = array();
            $data["fk_sample"] = $this->id;
            $sampleStatusLog->setData($data);

            // save child:
            $sampleStatusLog->save($userSession);
        }
    }

    public function delete(FUserSession $userSession)
    {

    }

    public function undelete(FUserSession $userSession)
    {

    }
}
