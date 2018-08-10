<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\App\FGuiUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class ModRecept extends FRegistry
{
    protected $id_recept;
    protected $number;
    protected $recept_datetime;
    protected $recept_notes;
    protected $recept_deviats;
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
    protected $ref_chain_custody;
    protected $ref_request;
    protected $ref_agreet;
    protected $is_def_report_images;
    protected $is_annulled;
    protected $is_system;
    protected $is_deleted;
    protected $fk_company_branch;
    protected $fk_customer;
    protected $nk_customer_sample;
    protected $nk_customer_billing;
    protected $fk_report_contact;
    protected $fk_report_delivery_opt;
    protected $fk_user_receiver;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    protected $childSamples;

    function __construct()
    {
        parent::__construct(AppConsts::OC_RECEPT, AppConsts::$tableIds[AppConsts::OC_RECEPT]);

        $this->id_recept = new FItem(FItem::DATA_TYPE_INT, "id_recept", "ID recepción", "", false, true);
        $this->number = new FItem(FItem::DATA_TYPE_STRING, "number", "Número recepción", "", true);
        $this->recept_datetime = new FItem(FItem::DATA_TYPE_DATETIME, "recept_datetime", "Fecha-hora recepción", "", true);
        $this->recept_notes = new FItem(FItem::DATA_TYPE_STRING, "recept_notes", "Observaciones recepción", "", false);
        $this->recept_deviats = new FItem(FItem::DATA_TYPE_STRING, "recept_deviats", "Desviaciones recepción", "", false);
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
        $this->ref_chain_custody = new FItem(FItem::DATA_TYPE_STRING, "ref_chain_custody", "Referencia cadena custodia", "", false);
        $this->ref_request = new FItem(FItem::DATA_TYPE_STRING, "ref_request", "Referencia solicitud ensayos", "", false);
        $this->ref_agreet = new FItem(FItem::DATA_TYPE_STRING, "ref_agreet", "Referencia convenio ensayos", "", false);
        $this->is_def_report_images = new FItem(FItem::DATA_TYPE_BOOL, "is_def_report_images", "Imágenes IR por defecto", "", false);
        $this->is_annulled = new FItem(FItem::DATA_TYPE_BOOL, "is_annulled", "Registro anulado", "", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_company_branch = new FItem(FItem::DATA_TYPE_INT, "fk_company_branch", "Sucursal empresa", "", true);
        $this->fk_customer = new FItem(FItem::DATA_TYPE_INT, "fk_customer", "Cliente", "", true);
        $this->nk_customer_sample = new FItem(FItem::DATA_TYPE_INT, "nk_customer_sample", "Cliente muestra", "", false);
        $this->nk_customer_billing = new FItem(FItem::DATA_TYPE_INT, "nk_customer_billing", "Cliente facturación", "", false);
        $this->fk_report_contact = new FItem(FItem::DATA_TYPE_INT, "fk_report_contact", "Contacto IR", "", true);
        $this->fk_report_delivery_opt = new FItem(FItem::DATA_TYPE_INT, "fk_report_delivery_opt", "Opción entrega IR", "", true);
        $this->fk_user_receiver = new FItem(FItem::DATA_TYPE_INT, "fk_user_receiver", "Receptor", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_recept"] = $this->id_recept;
        $this->items["number"] = $this->number;
        $this->items["recept_datetime"] = $this->recept_datetime;
        $this->items["recept_notes"] = $this->recept_notes;
        $this->items["recept_deviats"] = $this->recept_deviats;
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
        $this->items["ref_chain_custody"] = $this->ref_chain_custody;
        $this->items["ref_request"] = $this->ref_request;
        $this->items["ref_agreet"] = $this->ref_agreet;
        $this->items["is_def_report_images"] = $this->is_def_report_images;
        $this->items["is_annulled"] = $this->is_annulled;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_company_branch"] = $this->fk_company_branch;
        $this->items["fk_customer"] = $this->fk_customer;
        $this->items["nk_customer_sample"] = $this->nk_customer_sample;
        $this->items["nk_customer_billing"] = $this->nk_customer_billing;
        $this->items["fk_report_contact"] = $this->fk_report_contact;
        $this->items["fk_report_delivery_opt"] = $this->fk_report_delivery_opt;
        $this->items["fk_user_receiver"] = $this->fk_user_receiver;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->number->setRangeLength(1, 25);
        $this->recept_notes->setRangeLength(0, 500);
        $this->recept_deviats->setRangeLength(0, 500);
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

        $this->childSamples = array();
    }

    public function &getChildSamples(): array
    {
        return $this->childSamples;
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
            $this->recept_notes->setValue($row["recept_notes"]);
            $this->recept_deviats->setValue($row["recept_deviats"]);
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
            $this->ref_chain_custody->setValue($row["ref_chain_custody"]);
            $this->ref_request->setValue($row["ref_request"]);
            $this->ref_agreet->setValue($row["ref_agreet"]);
            $this->is_def_report_images->setValue($row["is_def_report_images"]);
            $this->is_annulled->setValue($row["is_annulled"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_company_branch->setValue($row["fk_company_branch"]);
            $this->fk_customer->setValue($row["fk_customer"]);
            $this->nk_customer_sample->setValue($row["nk_customer_sample"]);
            $this->nk_customer_billing->setValue($row["nk_customer_billing"]);
            $this->fk_report_contact->setValue($row["fk_report_contact"]);
            $this->fk_report_delivery_opt->setValue($row["fk_report_delivery_opt"]);
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
