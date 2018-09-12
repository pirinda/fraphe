<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\App\FGuiUtils;
use Fraphe\Lib\FLibUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;

class ModSample extends FRegistry
{
    public const PREFIX = "sample_";

    protected $id_sample;
    protected $sample_num;
    protected $sample_name;
    protected $sample_lot;
    protected $sample_date_mfg_n;
    protected $sample_date_sell_by_n;
    protected $sample_quantity;
    protected $sample_quantity_orig;
    protected $sample_child;
    protected $sample_released;
    protected $is_sampling_company;
    protected $sampling_datetime_n;
    protected $sampling_temperat_n;
    protected $sampling_area;
    protected $sampling_guide;
    protected $sampling_conditions;
    protected $sampling_deviations;
    protected $sampling_notes;
    protected $sampling_imgs;
    protected $recept_sample;
    protected $recept_datetime_n;
    protected $recept_temperat_n;
    protected $recept_deviations;
    protected $recept_notes;
    protected $service_type;
    protected $process_days;
    protected $process_start_date;
    protected $process_deadline;
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
    protected $fk_sample_class;
    protected $fk_sample_type;
    protected $fk_sample_status;
    protected $nk_sample_parent;
    protected $fk_container_type;
    protected $fk_container_unit;
    protected $fk_sampling_method;
    protected $nk_sampling_equipt_1;
    protected $nk_sampling_equipt_2;
    protected $nk_sampling_equipt_3;
    protected $nk_recept;
    protected $fk_user_sampler;
    protected $fk_user_receiver;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    protected $childSampleTests;        // array of ModSampleTest
    protected $childSampleStatusLogs;   // array of ModSampleStatusLog
    protected $childSamplingImages;     // array of ModSamplingImage

    protected $parentRecept;
    protected $auxChildJobs;
    protected $auxChildReport;

    protected $orig_fk_sample_status;

    function __construct()
    {
        parent::__construct(AppConsts::O_SAMPLE, AppConsts::$tables[AppConsts::O_SAMPLE], AppConsts::$tableIds[AppConsts::O_SAMPLE]);

        $this->id_sample = new FItem(FItem::DATA_TYPE_INT, "id_sample", "ID muestra", "", false, true);
        $this->sample_num = new FItem(FItem::DATA_TYPE_STRING, "sample_num", "Folio muestra", "", true);
        $this->sample_name = new FItem(FItem::DATA_TYPE_STRING, "sample_name", "Nombre muestra", "", true);
        $this->sample_lot = new FItem(FItem::DATA_TYPE_STRING, "sample_lot", "Lote muestra", "", false);
        $this->sample_date_mfg_n = new FItem(FItem::DATA_TYPE_DATE, "sample_date_mfg_n", "Fecha elaboración", "", false);
        $this->sample_date_sell_by_n = new FItem(FItem::DATA_TYPE_DATE, "sample_date_sell_by_n", "Fecha caducidad", "", false);
        $this->sample_quantity = new FItem(FItem::DATA_TYPE_FLOAT, "sample_quantity", "Cantidad muestra", "", true);
        $this->sample_quantity_orig = new FItem(FItem::DATA_TYPE_FLOAT, "sample_quantity_orig", "Cantidad muestra original", "", true);
        $this->sample_child = new FItem(FItem::DATA_TYPE_INT, "sample_child", "Núm. muestra hijo", "", false);
        $this->sample_released = new FItem(FItem::DATA_TYPE_STRING, "sample_released", "Muestra liberada", "", false);
        $this->is_sampling_company = new FItem(FItem::DATA_TYPE_BOOL, "is_sampling_company", "Muestreo propio", "", false);
        $this->sampling_datetime_n = new FItem(FItem::DATA_TYPE_DATETIME, "sampling_datetime_n", "Fecha-hr muestreo", "aaaa-mm-ddTHH:mm", false);
        $this->sampling_temperat_n = new FItem(FItem::DATA_TYPE_FLOAT, "sampling_temperat_n", "Temp. muestreo °C", "", false);
        $this->sampling_area = new FItem(FItem::DATA_TYPE_STRING, "sampling_area", "Área muestreo", "", true);
        $this->sampling_guide = new FItem(FItem::DATA_TYPE_INT, "sampling_guide", "Núm. guía muestreo", "", false);
        $this->sampling_conditions = new FItem(FItem::DATA_TYPE_STRING, "sampling_conditions", "Condiciones muestreo", "", false);
        $this->sampling_deviations = new FItem(FItem::DATA_TYPE_STRING, "sampling_deviations", "Desviaciones muestreo", "", false);
        $this->sampling_notes = new FItem(FItem::DATA_TYPE_STRING, "sampling_notes", "Observaciones muestreo", "", false);
        $this->sampling_imgs = new FItem(FItem::DATA_TYPE_INT, "sampling_imgs", "Imágenes muestreo", "", false);
        $this->recept_sample = new FItem(FItem::DATA_TYPE_INT, "recept_sample", "Núm. muestra recepción", "", false);
        $this->recept_datetime_n = new FItem(FItem::DATA_TYPE_DATETIME, "recept_datetime_n", "Fecha-hr recepción", "aaaa-mm-ddTHH:mm", false);
        $this->recept_temperat_n = new FItem(FItem::DATA_TYPE_FLOAT, "recept_temperat_n", "Temp. recepción °C", "", false);
        $this->recept_deviations = new FItem(FItem::DATA_TYPE_STRING, "recept_deviations", "Desviaciones recepción", "", false);
        $this->recept_notes = new FItem(FItem::DATA_TYPE_STRING, "recept_notes", "Observaciones recepción", "", false);
        $this->service_type = new FItem(FItem::DATA_TYPE_STRING, "service_type", "Tipo servicio", "", true);
        $this->process_days = new FItem(FItem::DATA_TYPE_INT, "process_days", "Días proceso", "", false);
        $this->process_start_date = new FItem(FItem::DATA_TYPE_DATE, "process_start_date", "Fecha inicio proceso", "", true);
        $this->process_deadline = new FItem(FItem::DATA_TYPE_DATE, "process_deadline", "Fecha límite proceso", "", true);
        $this->is_customer_custom = new FItem(FItem::DATA_TYPE_BOOL, "is_customer_custom", "Cliente personalizado", "", false);
        $this->customer_name = new FItem(FItem::DATA_TYPE_STRING, "customer_name", "Nombre cliente", "", false);
        $this->customer_street = new FItem(FItem::DATA_TYPE_STRING, "customer_street", "Calle y número", "", false);
        $this->customer_district = new FItem(FItem::DATA_TYPE_STRING, "customer_district", "Colonia", "", false);
        $this->customer_postcode = new FItem(FItem::DATA_TYPE_STRING, "customer_postcode", "Código postal", "", false);
        $this->customer_reference = new FItem(FItem::DATA_TYPE_STRING, "customer_reference", "Referencia", "", false);
        $this->customer_city = new FItem(FItem::DATA_TYPE_STRING, "customer_city", "Localidad", "", false);
        $this->customer_county = new FItem(FItem::DATA_TYPE_STRING, "customer_county", "Municipio", "", false);
        $this->customer_state_region = new FItem(FItem::DATA_TYPE_STRING, "customer_state_region", "Estado", "", false);
        $this->customer_country = new FItem(FItem::DATA_TYPE_STRING, "customer_country", "País", "ISO 3166", false);
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
        $this->fk_sample_class = new FItem(FItem::DATA_TYPE_INT, "fk_sample_class", "Clase muestra", "", true);
        $this->fk_sample_type = new FItem(FItem::DATA_TYPE_INT, "fk_sample_type", "Tipo muestra", "", true);
        $this->fk_sample_status = new FItem(FItem::DATA_TYPE_INT, "fk_sample_status", "Estatus muestra", "", true);
        $this->nk_sample_parent = new FItem(FItem::DATA_TYPE_INT, "nk_sample_parent", "Muestra padre", "", false);
        $this->fk_container_type = new FItem(FItem::DATA_TYPE_INT, "fk_container_type", "Tipo envase", "", true);
        $this->fk_container_unit = new FItem(FItem::DATA_TYPE_INT, "fk_container_unit", "Unidad medida envase", "", true);
        $this->fk_sampling_method = new FItem(FItem::DATA_TYPE_INT, "fk_sampling_method", "Método muestreo", "", true);
        $this->nk_sampling_equipt_1 = new FItem(FItem::DATA_TYPE_INT, "nk_sampling_equipt_1", "Equipo muestreo 1", "", false);
        $this->nk_sampling_equipt_2 = new FItem(FItem::DATA_TYPE_INT, "nk_sampling_equipt_2", "Equipo muestreo 2", "", false);
        $this->nk_sampling_equipt_3 = new FItem(FItem::DATA_TYPE_INT, "nk_sampling_equipt_3", "Equipo muestreo 3", "", false);
        $this->nk_recept = new FItem(FItem::DATA_TYPE_INT, "nk_recept", "Recepción", "", false);
        $this->fk_user_sampler = new FItem(FItem::DATA_TYPE_INT, "fk_user_sampler", "Realizador muestreo", "", true);
        $this->fk_user_receiver = new FItem(FItem::DATA_TYPE_INT, "fk_user_receiver", "Receptor", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_sample"] = $this->id_sample;
        $this->items["sample_num"] = $this->sample_num;
        $this->items["sample_name"] = $this->sample_name;
        $this->items["sample_lot"] = $this->sample_lot;
        $this->items["sample_date_mfg_n"] = $this->sample_date_mfg_n;
        $this->items["sample_date_sell_by_n"] = $this->sample_date_sell_by_n;
        $this->items["sample_quantity"] = $this->sample_quantity;
        $this->items["sample_quantity_orig"] = $this->sample_quantity_orig;
        $this->items["sample_child"] = $this->sample_child;
        $this->items["sample_released"] = $this->sample_released;
        $this->items["is_sampling_company"] = $this->is_sampling_company;
        $this->items["sampling_datetime_n"] = $this->sampling_datetime_n;
        $this->items["sampling_temperat_n"] = $this->sampling_temperat_n;
        $this->items["sampling_area"] = $this->sampling_area;
        $this->items["sampling_guide"] = $this->sampling_guide;
        $this->items["sampling_conditions"] = $this->sampling_conditions;
        $this->items["sampling_deviations"] = $this->sampling_deviations;
        $this->items["sampling_notes"] = $this->sampling_notes;
        $this->items["sampling_imgs"] = $this->sampling_imgs;
        $this->items["recept_sample"] = $this->recept_sample;
        $this->items["recept_datetime_n"] = $this->recept_datetime_n;
        $this->items["recept_temperat_n"] = $this->recept_temperat_n;
        $this->items["recept_deviations"] = $this->recept_deviations;
        $this->items["recept_notes"] = $this->recept_notes;
        $this->items["service_type"] = $this->service_type;
        $this->items["process_days"] = $this->process_days;
        $this->items["process_start_date"] = $this->process_start_date;
        $this->items["process_deadline"] = $this->process_deadline;
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
        $this->items["fk_sample_class"] = $this->fk_sample_class;
        $this->items["fk_sample_type"] = $this->fk_sample_type;
        $this->items["fk_sample_status"] = $this->fk_sample_status;
        $this->items["nk_sample_parent"] = $this->nk_sample_parent;
        $this->items["fk_container_type"] = $this->fk_container_type;
        $this->items["fk_container_unit"] = $this->fk_container_unit;
        $this->items["fk_sampling_method"] = $this->fk_sampling_method;
        $this->items["nk_sampling_equipt_1"] = $this->nk_sampling_equipt_1;
        $this->items["nk_sampling_equipt_2"] = $this->nk_sampling_equipt_2;
        $this->items["nk_sampling_equipt_3"] = $this->nk_sampling_equipt_3;
        $this->items["nk_recept"] = $this->nk_recept;
        $this->items["fk_user_sampler"] = $this->fk_user_sampler;
        $this->items["fk_user_receiver"] = $this->fk_user_receiver;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->sample_num->setRangeLength(1, 25);
        $this->sample_name->setRangeLength(1, 100);
        $this->sample_lot->setRangeLength(0, 50);
        $this->sample_released->setRangeLength(0, 1);
        $this->sampling_area->setRangeLength(1, 100);
        $this->sampling_conditions->setRangeLength(0, 100);
        $this->sampling_deviations->setRangeLength(0, 500);
        $this->sampling_notes->setRangeLength(0, 500);
        $this->recept_deviations->setRangeLength(0, 500);
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

        $this->clearChildSampleTests();
        $this->clearChildSampleStatusLogs();
        $this->clearChildSamplingImages();

        $this->parentRecept = null;
        $this->auxChildJobs = array();
        $this->auxChildReport = null;

        $this->orig_fk_sample_status = null;
    }

    public function setParentRecept(ModRecept $recept)
    {
        $this->parentRecept = $recept;
    }

    protected function validateParentRecept() {
        if (!isset($this->parentRecept)) {
            throw new \Exception("El objeto 'recepción' no ha sido asignado.");
        }
    }

    protected function generateNumber(FUserSession $userSession): string
    {
        $this->validateParentRecept();

        $nf = new \NumberFormatter($userSession->getLocLang(), \NumberFormatter::PATTERN_DECIMAL);
        $nf->setAttribute(\NumberFormatter::MIN_INTEGER_DIGITS, 3); // TODO: paremeterize this formatting argument: 4!

        return $this->parentRecept->getDatum("recept_num") . "-" . $nf->format($this->recept_sample->getValue());
    }

    protected function generateReceptSample(FUserSession $userSession)
    {
        $this->validateParentRecept();

        $sql = "SELECT COALESCE(MAX(recept_sample), 0) AS _max_recept_sample FROM $this->tableName WHERE nk_recept = " . $this->parentRecept->getId() . ";";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->recept_sample->setValue(intval($row["_max_recept_sample"]) + 1);
        }
    }

    protected function updateSampleClass(FUserSession $userSession)
    {
        $sql = "SELECT fk_sample_class FROM oc_sample_type WHERE id_sample_type = " . $this->fk_sample_type->getValue() . ";";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->fk_sample_class->setValue(intval($row["fk_sample_class"]));
        }
    }

    public function setProcessStartDate(int $startDate)
    {
        $this->process_start_date->setValue($startDate);

        // propagate process start date:
        foreach ($this->childSampleTests as $test) {
            $test->getItem("process_start_date")->setValue($startDate);
        }
    }

    public function computeProcessDays()
    {
        $maxDays = 0;
        $minStartDate = $this->process_start_date->getValue();  // must be already set
        $maxDeadline = $this->process_start_date->getValue();   // must be already set

        foreach ($this->childSampleTests as $test) {
            $test->computeProcessDays();

            if ($test->getDatum("process_days") > $maxDays) {
                $maxDays = $test->getDatum("process_days");
            }

            if (!isset($minStartDate)) {
                $minStartDate = $test->getDatum("process_start_date");
            }
            else {
                if ($test->getDatum("process_start_date") < $minStartDate) {
                    $minStartDate = $test->getDatum("process_start_date");
                }
            }

            if ($test->getDatum("process_deadline") > $maxDeadline) {
                $maxDeadline = $test->getDatum("process_deadline");
            }
        }

        $this->process_days->setValue($maxDays);
        $this->process_start_date->setValue($minStartDate);
        $this->process_deadline->setValue($maxDeadline);
    }

    public function &getChildSampleTests(): array
    {
        return $this->childSampleTests;
    }

    public function &getChildSampleStatusLogs(): array
    {
        return $this->childSampleStatusLogs;
    }

    public function &getChildSamplingImages(): array
    {
        return $this->childSamplingImages;
    }

    public function clearChildSampleTests()
    {
        $this->childSampleTests = array();
    }

    public function clearChildSampleStatusLogs()
    {
        $this->childSampleStatusLogs = array();
    }

    public function clearChildSamplingImages()
    {
        $this->childSamplingImages = array();
    }

    public function &getAuxChildJobs(): array
    {
        return $this->auxChildJobs;
    }

    public function setAuxChildReport(ModReport $report)
    {
        $this->auxChildReport = $report;
    }

    /** Overriden method.
     */
    public function forceRegistryNew()
    {
        parent::forceRegistryNew();

        $this->sample_num->reset();
        $this->recept_sample->reset();

        foreach ($this->childSampleTests as $child) {
            $child->forceRegistryNew();
        }

        $this->clearChildSampleStatusLogs();

        $this->clearChildSamplingImages();
    }

    /** Overriden method.
     */
    public function tailorMembers()
    {
        if (!$this->is_sampling_company->getValue()) {
            // sampling by customer:
            $this->sampling_datetime_n->setMandatory(false);
            $this->sampling_area->setMandatory(false);
            $this->sampling_area->setLengthMin(0);
            $this->sampling_guide->setMandatory(false);
            $this->fk_user_sampler->setMandatory(false);
        }
        else {
            // sampling by company:
            $this->sampling_datetime_n->setMandatory(true);
            $this->sampling_area->setMandatory(true);
            $this->sampling_area->setLengthMin(1);
            $this->sampling_guide->setMandatory(true);
            $this->fk_user_sampler->setMandatory(true);
        }

        $items = array($this->customer_name, $this->customer_postcode,
            $this->customer_city, $this->customer_county, $this->customer_state_region, $this->customer_country);

        if (!$this->is_customer_custom->getValue()) {
            foreach($items as $item) {
                $item->setMandatory(false);
                $item->setLengthMin(0);
            }
        }
        else {
            foreach($items as $item) {
                $item->setMandatory(true);
                $item->setLengthMin(1);
            }
        }
    }

    /** Overriden method.
     */
    public function validate(FUserSession $userSession)
    {
        // compute data:

        $this->validateParentRecept();

        if (empty($this->sample_quantity_orig->getValue())) {
            $this->sample_quantity_orig->setValue($this->sample_quantity->getValue());
        }

        if (empty($this->recept_sample->getValue())) {
            $this->generateReceptSample($userSession); // will be used by generation of sample number
        }

        if (empty($this->recept_datetime_n->getValue())) {
            $this->recept_datetime_n->setValue($this->parentRecept->getDatum("recept_datetime"));
        }

        $this->updateSampleClass($userSession);

        if ($this->isRegistryNew) {
            $this->sample_num->setValue($this->generateNumber($userSession)); // recept sample must have been set already
        }

        $this->computeProcessDays();

        // validate registry:

        parent::validate($userSession);

        foreach ($this->childSampleTests as $child) {
            $data = array();
            $data["fk_sample"] = $this->isRegistryNew ? -1 : $this->id;  // bypass validation
            $child->setData($data);
            $child->validate($userSession);
        }

        foreach ($this->childSampleStatusLogs as $child) {
            $data = array();
            $data["fk_sample"] = $this->isRegistryNew ? -1 : $this->id;  // bypass validation
            $child->setData($data);
            $child->validate($userSession);
        }

        foreach ($this->childSamplingImages as $child) {
            $data = array();
            $data["fk_sample"] = $this->isRegistryNew ? -1 : $this->id;  // bypass validation
            $child->setData($data);
            $child->setParentSample($this);
            $child->validate($userSession);
        }
    }

    /** Implemented method.
     */
    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM $this->tableName WHERE id_sample = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_sample"]);

            $this->id_sample->setValue($row["id_sample"]);
            $this->sample_num->setValue($row["sample_num"]);
            $this->sample_name->setValue($row["sample_name"]);
            $this->sample_lot->setValue($row["sample_lot"]);
            $this->sample_date_mfg_n->setValue($row["sample_date_mfg_n"]);
            $this->sample_date_sell_by_n->setValue($row["sample_date_sell_by_n"]);
            $this->sample_quantity->setValue($row["sample_quantity"]);
            $this->sample_quantity_orig->setValue($row["sample_quantity_orig"]);
            $this->sample_child->setValue($row["sample_child"]);
            $this->sample_released->setValue($row["sample_released"]);
            $this->is_sampling_company->setValue($row["is_sampling_company"]);
            $this->sampling_datetime_n->setValue($row["sampling_datetime_n"]);
            $this->sampling_temperat_n->setValue($row["sampling_temperat_n"]);
            $this->sampling_area->setValue($row["sampling_area"]);
            $this->sampling_guide->setValue($row["sampling_guide"]);
            $this->sampling_conditions->setValue($row["sampling_conditions"]);
            $this->sampling_deviations->setValue($row["sampling_deviations"]);
            $this->sampling_notes->setValue($row["sampling_notes"]);
            $this->sampling_imgs->setValue($row["sampling_imgs"]);
            $this->recept_sample->setValue($row["recept_sample"]);
            $this->recept_datetime_n->setValue($row["recept_datetime_n"]);
            $this->recept_temperat_n->setValue($row["recept_temperat_n"]);
            $this->recept_deviations->setValue($row["recept_deviations"]);
            $this->recept_notes->setValue($row["recept_notes"]);
            $this->service_type->setValue($row["service_type"]);
            $this->process_days->setValue($row["process_days"]);
            $this->process_start_date->setValue($row["process_start_date"]);
            $this->process_deadline->setValue($row["process_deadline"]);
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
            $this->fk_sample_class->setValue($row["fk_sample_class"]);
            $this->fk_sample_type->setValue($row["fk_sample_type"]);
            $this->fk_sample_status->setValue($row["fk_sample_status"]);
            $this->nk_sample_parent->setValue($row["nk_sample_parent"]);
            $this->fk_container_type->setValue($row["fk_container_type"]);
            $this->fk_container_unit->setValue($row["fk_container_unit"]);
            $this->fk_sampling_method->setValue($row["fk_sampling_method"]);
            $this->nk_sampling_equipt_1->setValue($row["nk_sampling_equipt_1"]);
            $this->nk_sampling_equipt_2->setValue($row["nk_sampling_equipt_2"]);
            $this->nk_sampling_equipt_3->setValue($row["nk_sampling_equipt_3"]);
            $this->nk_recept->setValue($row["nk_recept"]);
            $this->fk_user_sampler->setValue($row["fk_user_sampler"]);
            $this->fk_user_receiver->setValue($row["fk_user_receiver"]);
            $this->fk_user_ins->setValue($row["fk_user_ins"]);
            $this->fk_user_upd->setValue($row["fk_user_upd"]);
            $this->ts_user_ins->setValue($row["ts_user_ins"]);
            $this->ts_user_upd->setValue($row["ts_user_upd"]);

            $this->isRegistryNew = false;
            $this->mode = $mode;

            $this->orig_fk_sample_status = $this->fk_sample_status->getValue();

            // create PDO connection for reading children:
            $pdo = FGuiUtils::createPdo();

            // read child sample tests:
            $sql = "SELECT id_sample_test FROM o_sample_test WHERE fk_sample = $this->id ORDER BY id_sample_test;";
            $statement = $pdo->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $child = new ModSampleTest();
                $child->read($userSession, intval($row["id_sample_test"]), $mode);
                $this->childSampleTests[] = $child;
            }

            // read child sample status log entries:
            $sql = "SELECT id_sample_status_log FROM o_sample_status_log WHERE fk_sample = $this->id ORDER BY id_sample_status_log;";
            $statement = $pdo->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $child = new ModSampleStatusLog();
                $child->read($userSession, intval($row["id_sample_status_log"]), $mode);
                $this->childSampleStatusLogs[] = $child;
            }

            // read child sample images:
            $sql = "SELECT id_sampling_img FROM o_sampling_img WHERE fk_sample = $this->id ORDER BY id_sampling_img;";
            $statement = $pdo->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $child = new ModSamplingImage();
                $child->read($userSession, intval($row["id_sampling_img"]), $mode);
                $this->childSamplingImages[] = $child;
            }
        }
        else {
            throw new \Exception(__METHOD__ . ": " . FRegistry::ERR_MSG_REGISTRY_NOT_FOUND);
        }
    }

    /** Implemented method.
     */
    public function save(FUserSession $userSession)
    {
        $this->validate($userSession);

        $statement;

        if ($this->isRegistryNew) {
            $statement = $userSession->getPdo()->prepare("INSERT INTO $this->tableName (" .
                "id_sample, " .
                "sample_num, " .
                "sample_name, " .
                "sample_lot, " .
                "sample_date_mfg_n, " .
                "sample_date_sell_by_n, " .
                "sample_quantity, " .
                "sample_quantity_orig, " .
                "sample_child, " .
                "sample_released, " .
                "is_sampling_company, " .
                "sampling_datetime_n, " .
                "sampling_temperat_n, " .
                "sampling_area, " .
                "sampling_guide, " .
                "sampling_conditions, " .
                "sampling_deviations, " .
                "sampling_notes, " .
                "sampling_imgs, " .
                "recept_sample, " .
                "recept_datetime_n, " .
                "recept_temperat_n, " .
                "recept_deviations, " .
                "recept_notes, " .
                "service_type, " .
                "process_days, " .
                "process_start_date, " .
                "process_deadline, " .
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
                "fk_sample_class, " .
                "fk_sample_type, " .
                "fk_sample_status, " .
                "nk_sample_parent, " .
                "fk_container_type, " .
                "fk_container_unit, " .
                "fk_sampling_method, " .
                "nk_sampling_equipt_1, " .
                "nk_sampling_equipt_2, " .
                "nk_sampling_equipt_3, " .
                "nk_recept, " .
                "fk_user_sampler, " .
                "fk_user_receiver, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":sample_num, " .
                ":sample_name, " .
                ":sample_lot, " .
                ":sample_date_mfg_n, " .
                ":sample_date_sell_by_n, " .
                ":sample_quantity, " .
                ":sample_quantity_orig, " .
                ":sample_child, " .
                ":sample_released, " .
                ":is_sampling_company, " .
                ":sampling_datetime_n, " .
                ":sampling_temperat_n, " .
                ":sampling_area, " .
                ":sampling_guide, " .
                ":sampling_conditions, " .
                ":sampling_deviations, " .
                ":sampling_notes, " .
                ":sampling_imgs, " .
                ":recept_sample, " .
                ":recept_datetime_n, " .
                ":recept_temperat_n, " .
                ":recept_deviations, " .
                ":recept_notes, " .
                ":service_type, " .
                ":process_days, " .
                ":process_start_date, " .
                ":process_deadline, " .
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
                ":fk_sample_class, " .
                ":fk_sample_type, " .
                ":fk_sample_status, " .
                ":nk_sample_parent, " .
                ":fk_container_type, " .
                ":fk_container_unit, " .
                ":fk_sampling_method, " .
                ":nk_sampling_equipt_1, " .
                ":nk_sampling_equipt_2, " .
                ":nk_sampling_equipt_3, " .
                ":nk_recept, " .
                ":fk_user_sampler, " .
                ":fk_user_receiver, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE $this->tableName SET " .
                "sample_num = :sample_num, " .
                "sample_name = :sample_name, " .
                "sample_lot = :sample_lot, " .
                "sample_date_mfg_n = :sample_date_mfg_n, " .
                "sample_date_sell_by_n = :sample_date_sell_by_n, " .
                "sample_quantity = :sample_quantity, " .
                "sample_quantity_orig = :sample_quantity_orig, " .
                "sample_child = :sample_child, " .
                "sample_released = :sample_released, " .
                "is_sampling_company = :is_sampling_company, " .
                "sampling_datetime_n = :sampling_datetime_n, " .
                "sampling_temperat_n = :sampling_temperat_n, " .
                "sampling_area = :sampling_area, " .
                "sampling_guide = :sampling_guide, " .
                "sampling_conditions = :sampling_conditions, " .
                "sampling_deviations = :sampling_deviations, " .
                "sampling_notes = :sampling_notes, " .
                "sampling_imgs = :sampling_imgs, " .
                "recept_sample = :recept_sample, " .
                "recept_datetime_n = :recept_datetime_n, " .
                "recept_temperat_n = :recept_temperat_n, " .
                "recept_deviations = :recept_deviations, " .
                "recept_notes = :recept_notes, " .
                "service_type = :service_type, " .
                "process_days = :process_days, " .
                "process_start_date = :process_start_date, " .
                "process_deadline = :process_deadline, " .
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
                "fk_sample_class = :fk_sample_class, " .
                "fk_sample_type = :fk_sample_type, " .
                "fk_sample_status = :fk_sample_status, " .
                "nk_sample_parent = :nk_sample_parent, " .
                "fk_container_type = :fk_container_type, " .
                "fk_container_unit = :fk_container_unit, " .
                "fk_sampling_method = :fk_sampling_method, " .
                "nk_sampling_equipt_1 = :nk_sampling_equipt_1, " .
                "nk_sampling_equipt_2 = :nk_sampling_equipt_2, " .
                "nk_sampling_equipt_3 = :nk_sampling_equipt_3, " .
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
        $sample_num = $this->sample_num->getValue();
        $sample_name = $this->sample_name->getValue();
        $sample_lot = $this->sample_lot->getValue();
        $sample_date_mfg_n = empty($this->sample_date_mfg_n->getValue()) ? "" : FLibUtils::formatStdDate($this->sample_date_mfg_n->getValue());
        $sample_date_sell_by_n = empty($this->sample_date_sell_by_n->getValue()) ? "" : FLibUtils::formatStdDate($this->sample_date_sell_by_n->getValue());
        $sample_quantity = $this->sample_quantity->getValue();
        $sample_quantity_orig = $this->sample_quantity_orig->getValue();
        $sample_child = $this->sample_child->getValue();
        $sample_released = $this->sample_released->getValue();
        $is_sampling_company = $this->is_sampling_company->getValue();
        $sampling_datetime_n = empty($this->sampling_datetime_n->getValue()) ? "" : FLibUtils::formatStdDatetime($this->sampling_datetime_n->getValue());
        $sampling_temperat_n = $this->sampling_temperat_n->getValue();
        $sampling_area = $this->sampling_area->getValue();
        $sampling_guide = $this->sampling_guide->getValue();
        $sampling_conditions = $this->sampling_conditions->getValue();
        $sampling_deviations = $this->sampling_deviations->getValue();
        $sampling_notes = $this->sampling_notes->getValue();
        $sampling_imgs = $this->sampling_imgs->getValue();
        $recept_sample = $this->recept_sample->getValue();
        $recept_datetime_n = empty($this->recept_datetime_n->getValue()) ? "" : FLibUtils::formatStdDatetime($this->recept_datetime_n->getValue());
        $recept_temperat_n = $this->recept_temperat_n->getValue();
        $recept_deviations = $this->recept_deviations->getValue();
        $recept_notes = $this->recept_notes->getValue();
        $service_type = $this->service_type->getValue();
        $process_days = $this->process_days->getValue();
        $process_start_date = FLibUtils::formatStdDate($this->process_start_date->getValue());
        $process_deadline = FLibUtils::formatStdDate($this->process_deadline->getValue());
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
        $fk_sample_class = $this->fk_sample_class->getValue();
        $fk_sample_type = $this->fk_sample_type->getValue();
        $fk_sample_status = $this->fk_sample_status->getValue();
        $nk_sample_parent = $this->nk_sample_parent->getValue();
        $fk_container_type = $this->fk_container_type->getValue();
        $fk_container_unit = $this->fk_container_unit->getValue();
        $fk_sampling_method = $this->fk_sampling_method->getValue();
        $nk_sampling_equipt_1 = $this->nk_sampling_equipt_1->getValue();
        $nk_sampling_equipt_2 = $this->nk_sampling_equipt_2->getValue();
        $nk_sampling_equipt_3 = $this->nk_sampling_equipt_3->getValue();
        $nk_recept = $this->nk_recept->getValue();
        $fk_user_sampler = $this->fk_user_sampler->getValue();
        $fk_user_receiver = $this->fk_user_receiver->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_sample", $id_sample, \PDO::PARAM_INT);
        $statement->bindParam(":sample_num", $sample_num);
        $statement->bindParam(":sample_name", $sample_name);
        $statement->bindParam(":sample_lot", $sample_lot);
        if (empty($sample_date_mfg_n)) {
            $statement->bindValue(":sample_date_mfg_n", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":sample_date_mfg_n", $sample_date_mfg_n);
        }
        if (empty($sample_date_sell_by_n)) {
            $statement->bindValue(":sample_date_sell_by_n", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":sample_date_sell_by_n", $sample_date_sell_by_n);
        }
        $statement->bindParam(":sample_quantity", $sample_quantity);
        $statement->bindParam(":sample_quantity_orig", $sample_quantity_orig);
        $statement->bindParam(":sample_child", $sample_child, \PDO::PARAM_INT);
        $statement->bindParam(":sample_released", $sample_released);
        $statement->bindParam(":is_sampling_company", $is_sampling_company, \PDO::PARAM_BOOL);
        if (empty($sampling_datetime_n)) {
            $statement->bindValue(":sampling_datetime_n", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":sampling_datetime_n", $sampling_datetime_n);
        }
        if (empty($sampling_temperat_n)) {
            $statement->bindValue(":sampling_temperat_n", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":sampling_temperat_n", $sampling_temperat_n);
        }
        $statement->bindParam(":sampling_area", $sampling_area);
        $statement->bindParam(":sampling_guide", $sampling_guide, \PDO::PARAM_INT);
        $statement->bindParam(":sampling_conditions", $sampling_conditions);
        $statement->bindParam(":sampling_deviations", $sampling_deviations);
        $statement->bindParam(":sampling_notes", $sampling_notes);
        $statement->bindParam(":sampling_imgs", $sampling_imgs, \PDO::PARAM_INT);
        $statement->bindParam(":recept_sample", $recept_sample, \PDO::PARAM_INT);
        if (empty($recept_datetime_n)) {
            $statement->bindValue(":recept_datetime_n", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":recept_datetime_n", $recept_datetime_n);
        }
        if (empty($recept_temperat_n)) {
            $statement->bindValue(":recept_temperat_n", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":recept_temperat_n", $recept_temperat_n);
        }
        $statement->bindParam(":recept_deviations", $recept_deviations);
        $statement->bindParam(":recept_notes", $recept_notes);
        $statement->bindParam(":service_type", $service_type);
        $statement->bindParam(":process_days", $process_days, \PDO::PARAM_INT);
        $statement->bindParam(":process_start_date", $process_start_date);
        $statement->bindParam(":process_deadline", $process_deadline);
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
            $statement->bindValue(":nk_customer_billing", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":nk_customer_billing", $nk_customer_billing, \PDO::PARAM_INT);
        }
        $statement->bindParam(":fk_report_contact", $fk_report_contact, \PDO::PARAM_INT);
        $statement->bindParam(":fk_report_delivery_type", $fk_report_delivery_type, \PDO::PARAM_INT);
        $statement->bindParam(":fk_sample_class", $fk_sample_class, \PDO::PARAM_INT);
        $statement->bindParam(":fk_sample_type", $fk_sample_type, \PDO::PARAM_INT);
        $statement->bindParam(":fk_sample_status", $fk_sample_status, \PDO::PARAM_INT);
        if (empty($nk_sample_parent)) {
            $statement->bindValue(":nk_sample_parent", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":nk_sample_parent", $nk_sample_parent, \PDO::PARAM_INT);
        }
        $statement->bindParam(":fk_container_type", $fk_container_type, \PDO::PARAM_INT);
        $statement->bindParam(":fk_container_unit", $fk_container_unit, \PDO::PARAM_INT);
        $statement->bindParam(":fk_sampling_method", $fk_sampling_method, \PDO::PARAM_INT);
        if (empty($nk_sampling_equipt_1)) {
            $statement->bindValue(":nk_sampling_equipt_1", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":nk_sampling_equipt_1", $nk_sampling_equipt_1, \PDO::PARAM_INT);
        }
        if (empty($nk_sampling_equipt_2)) {
            $statement->bindValue(":nk_sampling_equipt_2", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":nk_sampling_equipt_2", $nk_sampling_equipt_2, \PDO::PARAM_INT);
        }
        if (empty($nk_sampling_equipt_3)) {
            $statement->bindValue(":nk_sampling_equipt_3", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":nk_sampling_equipt_3", $nk_sampling_equipt_3, \PDO::PARAM_INT);
        }
        if (empty($nk_recept)) {
            $statement->bindValue(":nk_recept", null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam(":nk_recept", $nk_recept, \PDO::PARAM_INT);
        }
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
            $this->id_sample->setValue($this->id);
            $this->isRegistryNew = false;
        }

        // save child sample tests:
        $test = 0;
        foreach ($this->childSampleTests as $child) {
            // ensure link to parent and set other data:
            $ids = array();
            $data["fk_sample"] = $this->id;
            $data["sample_test"] = ++$test;

            // save child:
            $child->setData($data);
            $child->save($userSession);
        }

        // save child sample status log entries:
        foreach ($this->childSampleStatusLogs as $child) {
            if ($child->isRegistryNew()) {
                // ensure link to parent:
                $data = array();
                $data["fk_sample"] = $this->id;

                // save child:
                $child->setData($data);
                $child->save($userSession);
            }
        }

        // save child sample images:
        foreach ($this->childSamplingImages as $child) {
            // ensure link to parent:
            $data = array();
            $data["fk_sample"] = $this->id;

            // save child:
            $child->setParentSample($this);
            $child->setData($data);
            $child->save($userSession);
        }

        // save aswell auxiliar child jobs:
        foreach ($this->auxChildJobs as $auxChild) {
            // ensure link to parent:
            $data = array();
            $data["fk_sample"] = $this->id;

            // save child:
            $auxChild->setData($data);
            $auxChild->save($userSession);
        }

        // save aswell auxiliar child report:
        if (isset($this->auxChildReport)) {
            // ensure link to parent:
            $data = array();
            $data["fk_sample"] = $this->id;

            // save child:
            $this->auxChildReport->setData($data);
            $this->auxChildReport->save($userSession);
        }

        // create status-log entry, if needed:
        if ($this->orig_fk_sample_status != $this->fk_sample_status->getValue()) {
            $data = array();
            $data["status_datetime"] = time();
            $data["status_temperat_n"] = !isset($this->status_temperat_n) ? null : $this->status_temperat_n->getValue();
            $data["status_notes"] = "";
            $data["is_system"] = true;
            //$data["is_deleted"] = ?;
            $data["fk_sample"] = $this->id;
            $data["fk_sample_status"] = $this->fk_sample_status->getValue();
            $data["fk_user_status"] = $fk_user;

            $entry = new ModSampleStatusLog();
            $this->childSampleStatusLogs[] = $entry; // append entry
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

        if (count($this->childSampleTests) > 0) {
            $this->childSampleTests[0]->resetAutoIncrement($userSession);
        }

        if (count($this->childSampleStatusLogs) > 0) {
            $this->childSampleStatusLogs[0]->resetAutoIncrement($userSession);
        }

        if (count($this->childSamplingImages) > 0) {
            $this->childSamplingImages[0]->resetAutoIncrement($userSession);
        }

        if (count($this->auxChildJobs) > 0) {
            $this->auxChildJobs[0]->resetAutoIncrement($userSession);
        }

        if (isset($this->auxChildReport)) {
            $this->auxChildReport->resetAutoIncrement($userSession);
        }
    }
}
