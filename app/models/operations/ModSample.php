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
    protected $sampling_datetime;
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
    protected $customer_name;
    protected $customer_street;
    protected $customer_district;
    protected $customer_postcode;
    protected $customer_reference;
    protected $customer_city;
    protected $customer_county;
    protected $customer_state_region;
    protected $customer_country;
    protected $recept_entry;
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

    function __construct()
    {
        parent::__construct(AppConsts::OC_SAMPLE, AppConsts::$tableIds[AppConsts::OC_SAMPLE]);

        $this->id_sample = new FItem(FItem::DATA_TYPE_INT, "id_sample", "ID muestra", "", false, true);
        $this->number = new FItem(FItem::DATA_TYPE_STRING, "number", "Número muestra", "", true);
        $this->name = new FItem(FItem::DATA_TYPE_STRING, "name", "Identificación muestra (nombre)", "", true);
        $this->lot = new FItem(FItem::DATA_TYPE_STRING, "lot", "Lote muestra", "", false);
        $this->date_manuf_n = new FItem(FItem::DATA_TYPE_DATE, "date_manuf_n", "Fecha producción", "", false);
        $this->date_sell_by_n = new FItem(FItem::DATA_TYPE_DATE, "date_sell_by_n", "Fecha caducidad", "", false);
        $this->quantity_original = new FItem(FItem::DATA_TYPE_FLOAT, "quantity_original", "Cantidad muestra original", "", true);
        $this->quantity = new FItem(FItem::DATA_TYPE_FLOAT, "quantity", "Cantidad muestra", "", true);
        $this->is_sampling_company = new FItem(FItem::DATA_TYPE_INT, "is_sampling_company", "Muestreo realizado por empresa", "", true);
        $this->sampling_guide = new FItem(FItem::DATA_TYPE_INT, "sampling_guide", "Número guía muestreo", "", true);
        $this->sampling_area = new FItem(FItem::DATA_TYPE_STRING, "sampling_area", "Área muestreo", "", true);
        $this->sampling_images = new FItem(FItem::DATA_TYPE_INT, "sampling_images", "Número imágenes muestreo", "", true);
        $this->sampling_datetime = new FItem(FItem::DATA_TYPE_DATETIME, "sampling_datetime", "Fecha-hora muestreo", "", true);
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
        $this->customer_name = new FItem(FItem::DATA_TYPE_STRING, "customer_name", "Nombre cliente", "", false);
        $this->customer_street = new FItem(FItem::DATA_TYPE_STRING, "customer_street", "Calle y número", "", false);
        $this->customer_district = new FItem(FItem::DATA_TYPE_STRING, "customer_district", "Colonia", "", false);
        $this->customer_postcode = new FItem(FItem::DATA_TYPE_STRING, "customer_postcode", "Código postal", "", false);
        $this->customer_reference = new FItem(FItem::DATA_TYPE_STRING, "customer_reference", "Referencia", "", false);
        $this->customer_city = new FItem(FItem::DATA_TYPE_STRING, "customer_city", "Localidad", "", false);
        $this->customer_county = new FItem(FItem::DATA_TYPE_STRING, "customer_county", "Municipio", "", true);
        $this->customer_state_region = new FItem(FItem::DATA_TYPE_STRING, "customer_state_region", "Estado", "", true);
        $this->customer_country = new FItem(FItem::DATA_TYPE_STRING, "customer_country", "País", "", true);
        $this->recept_entry = new FItem(FItem::DATA_TYPE_INT, "recept_entry", "Partida recepción", "", true);
        $this->ref_chain_custody = new FItem(FItem::DATA_TYPE_STRING, "ref_chain_custody", "Referencia cadena custodia", "", false);
        $this->ref_request = new FItem(FItem::DATA_TYPE_STRING, "ref_request", "Referencia solicitud ensayos", "", false);
        $this->ref_agreet = new FItem(FItem::DATA_TYPE_STRING, "ref_agreet", "Referencia convenio ensayos", "", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_company_branch = new FItem(FItem::DATA_TYPE_INT, "fk_company_branch", "Sucursal empresa", "", true);
        $this->fk_customer = new FItem(FItem::DATA_TYPE_INT, "fk_customer", "Cliente", "", true);
        $this->nk_customer_sample = new FItem(FItem::DATA_TYPE_INT, "nk_customer_sample", "Cliente muestra", "", false);
        $this->nk_customer_billing = new FItem(FItem::DATA_TYPE_INT, "nk_customer_billing", "Cliente facturación", "", false);
        $this->fk_report_contact = new FItem(FItem::DATA_TYPE_INT, "fk_report_contact", "Contacto informe resultados", "", true);
        $this->fk_report_delivery_opt = new FItem(FItem::DATA_TYPE_INT, "fk_report_delivery_opt", "Opción entrega informe resultados", "", true);
        $this->fk_sample_class = new FItem(FItem::DATA_TYPE_INT, "fk_sample_class", "Clase muestra", "", true);
        $this->fk_sample_type = new FItem(FItem::DATA_TYPE_INT, "fk_sample_type", "Tipo muestra", "", true);
        $this->fk_sample_status = new FItem(FItem::DATA_TYPE_INT, "fk_sample_status", "Estatus muestra", "", true);
        $this->nk_sample_parent = new FItem(FItem::DATA_TYPE_INT, "nk_sample_parent", "Muestra padre", "", false);
        $this->fk_container_type = new FItem(FItem::DATA_TYPE_INT, "fk_container_type", "Tipo presentación muestra (envase)", "", true);
        $this->fk_container_unit = new FItem(FItem::DATA_TYPE_INT, "fk_container_unit", "Unidad medida presentación muestra", "", true);
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
        $this->items["sampling_datetime"] = $this->sampling_datetime;
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
        $this->items["customer_name"] = $this->customer_name;
        $this->items["customer_street"] = $this->customer_street;
        $this->items["customer_district"] = $this->customer_district;
        $this->items["customer_postcode"] = $this->customer_postcode;
        $this->items["customer_reference"] = $this->customer_reference;
        $this->items["customer_city"] = $this->customer_city;
        $this->items["customer_county"] = $this->customer_county;
        $this->items["customer_state_region"] = $this->customer_state_region;
        $this->items["customer_country"] = $this->customer_country;
        $this->items["recept_entry"] = $this->recept_entry;
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
        $this->ref_chain_custody->setRangeLength(0, 25);
        $this->ref_request->setRangeLength(0, 25);
        $this->ref_agreet->setRangeLength(0, 25);

        $this->childProcessOpts = array();
    }

    public function getChildProcessOpts(): array
    {
        return $this->childProcessOpts;
    }

    public function addChildProcessOpt(ModTestProcessOpt $processOpt)
    {
        return $this->childProcessOpts[] = $processOpt;
    }

    public function validate(FUserSession $userSession)
    {
        // validate registry:

        parent::validate($userSession);

        foreach ($this->childProcessOpts as $processOpt) {
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
            $this->sampling_datetime->setValue($row["sampling_datetime"]);
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
            $this->customer_name->setValue($row["customer_name"]);
            $this->customer_street->setValue($row["customer_street"]);
            $this->customer_district->setValue($row["customer_district"]);
            $this->customer_postcode->setValue($row["customer_postcode"]);
            $this->customer_reference->setValue($row["customer_reference"]);
            $this->customer_city->setValue($row["customer_city"]);
            $this->customer_county->setValue($row["customer_county"]);
            $this->customer_state_region->setValue($row["customer_state_region"]);
            $this->customer_country->setValue($row["customer_country"]);
            $this->recept_entry->setValue($row["recept_entry"]);
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

            // read child process options:
            $sql = "SELECT id_test, id_entity FROM oc_test_process_opt WHERE id_test = $this->id ORDER BY id_test, id_entity;";
            $statement = $pdo->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $ids = array();
                $ids["id_test"] = intval($row["id_test"]);
                $ids["id_entity"] = intval($row["id_entity"]);

                $processOpt = new ModTestProcessOpt();
                $processOpt->retrieve($userSession, $ids, $mode);
                $this->childProcessOpts[] = $processOpt;
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
            $statement = $userSession->getPdo()->prepare("INSERT INTO oc_test (" .
                "id_test, " .
                "name, " .
                "code, " .
                "sample_quantity, " .
                "sample_directs, " .
                "is_system, " .
                "is_deleted, " .
                "fk_process_area, " .
                "fk_sample_class, " .
                "fk_testing_method, " .
                "fk_test_acredit_attrib, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":name, " .
                ":code, " .
                ":sample_quantity, " .
                ":sample_directs, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_process_area, " .
                ":fk_sample_class, " .
                ":fk_testing_method, " .
                ":fk_test_acredit_attrib, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE oc_test SET " .
                "name = :name, " .
                "code = :code, " .
                "sample_quantity = :sample_quantity, " .
                "sample_directs = :sample_directs, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_process_area = :fk_process_area, " .
                "fk_sample_class = :fk_sample_class, " .
                "fk_testing_method = :fk_testing_method, " .
                "fk_test_acredit_attrib = :fk_test_acredit_attrib, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_test = :id;");
        }

        //$id_test = $this->id_test->getValue();
        $name = $this->name->getValue();
        $code = $this->code->getValue();
        $sample_quantity = $this->sample_quantity->getValue();
        $sample_directs = $this->sample_directs->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_process_area = $this->fk_process_area->getValue();
        $fk_sample_class = $this->fk_sample_class->getValue();
        $fk_testing_method = $this->fk_testing_method->getValue();
        $fk_test_acredit_attrib = $this->fk_test_acredit_attrib->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_test", $id_test, \PDO::PARAM_INT);
        $statement->bindParam(":name", $name);
        $statement->bindParam(":code", $code);
        $statement->bindParam(":sample_quantity", $sample_quantity);
        $statement->bindParam(":sample_directs", $sample_directs);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_process_area", $fk_process_area, \PDO::PARAM_INT);
        $statement->bindParam(":fk_sample_class", $fk_sample_class, \PDO::PARAM_INT);
        $statement->bindParam(":fk_testing_method", $fk_testing_method, \PDO::PARAM_INT);
        $statement->bindParam(":fk_test_acredit_attrib", $fk_test_acredit_attrib, \PDO::PARAM_INT);
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

        // save child process options:
        foreach ($this->childProcessOpts as $processOpt) {
            $ids = array();
            $ids["id_test"] = $this->id;

            $processOpt->setIds($ids);
            $processOpt->save($userSession);
        }
    }

    public function delete(FUserSession $userSession)
    {

    }

    public function undelete(FUserSession $userSession)
    {

    }
}
