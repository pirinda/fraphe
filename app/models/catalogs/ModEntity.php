<?php
namespace app\models\catalogs;

use Fraphe\App\FUserSession;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class ModEntity extends FRegistry
{
    protected $id_entity;
    protected $name;
    protected $code;
    protected $alias;
    protected $prefix;
    protected $surname;
    protected $forename;
    protected $fiscal_id;
    protected $is_person;
    protected $is_credit;
    protected $credit_days;
    protected $billing_prefs;
    protected $notes;
    protected $is_system;
    protected $is_deleted;
    protected $fk_entity_class;
    protected $nk_market_segment;
    protected $nk_entity_parent;
    protected $nk_entity_billing;
    protected $nk_entity_agent;
    protected $nk_user_agent;
    protected $nk_report_delivery_opt;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    protected $childEntityAddresses;

    function __construct(\PDO $connection)
    {
        parent::__construct($connection, AppConsts::CC_ENTITY);

        $this->id_entity = new FItem(FItem::DATA_TYPE_INT, "id_entity", "ID entidad", true);
        $this->name = new FItem(FItem::DATA_TYPE_STRING, "name", "Nombre", false);
        $this->code = new FItem(FItem::DATA_TYPE_STRING, "code", "Código", false);
        $this->alias = new FItem(FItem::DATA_TYPE_STRING, "alias", "Alias", false);
        $this->prefix = new FItem(FItem::DATA_TYPE_STRING, "prefix", "Prefijo", false);
        $this->surname = new FItem(FItem::DATA_TYPE_STRING, "surname", "Apellidos", false);
        $this->forename = new FItem(FItem::DATA_TYPE_STRING, "forename", "Nombres", false);
        $this->fiscal_id = new FItem(FItem::DATA_TYPE_STRING, "fiscal_id", "ID fiscal", false);
        $this->is_person = new FItem(FItem::DATA_TYPE_BOOL, "is_person", "Es persona", false);
        $this->is_credit = new FItem(FItem::DATA_TYPE_BOOL, "is_credit", "Tiene crédito", false);
        $this->credit_days = new FItem(FItem::DATA_TYPE_INT, "credit_days", "Días crédito", false);
        $this->billing_prefs = new FItem(FItem::DATA_TYPE_STRING, "billing_prefs", "Preferencias facturación", false);
        $this->notes = new FItem(FItem::DATA_TYPE_STRING, "notes", "Notas", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Es de sistema", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Está eliminado", false);
        $this->fk_entity_class = new FItem(FItem::DATA_TYPE_INT, "fk_entity_class", "Clase entidad", false);
        $this->nk_market_segment = new FItem(FItem::DATA_TYPE_INT, "nk_market_segment", "Segmento mercado", false);
        $this->nk_entity_parent = new FItem(FItem::DATA_TYPE_INT, "nk_entity_parent", "Entidad padre", false);
        $this->nk_entity_billing = new FItem(FItem::DATA_TYPE_INT, "nk_entity_billing", "Entidad facturación", false);
        $this->nk_entity_agent = new FItem(FItem::DATA_TYPE_INT, "nk_entity_agent", "Comisionista", false);
        $this->nk_user_agent = new FItem(FItem::DATA_TYPE_INT, "nk_user_agent", "Agente comercial", false);
        $this->nk_report_delivery_opt = new FItem(FItem::DATA_TYPE_INT, "nk_report_delivery_opt", "Opción entrega informes resultados", false);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", true);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", true);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", true);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", true);

        $this->items["id_entity"] = $this->id_entity;
        $this->items["name"] = $this->name;
        $this->items["code"] = $this->code;
        $this->items["alias"] = $this->alias;
        $this->items["prefix"] = $this->prefix;
        $this->items["surname"] = $this->surname;
        $this->items["forename"] = $this->forename;
        $this->items["fiscal_id"] = $this->fiscal_id;
        $this->items["is_person"] = $this->is_person;
        $this->items["is_credit"] = $this->is_credit;
        $this->items["credit_days"] = $this->credit_days;
        $this->items["billing_prefs"] = $this->billing_prefs;
        $this->items["notes"] = $this->notes;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_entity_class"] = $this->fk_entity_class;
        $this->items["nk_market_segment"] = $this->nk_market_segment;
        $this->items["nk_entity_parent"] = $this->nk_entity_parent;
        $this->items["nk_entity_billing"] = $this->nk_entity_billing;
        $this->items["nk_entity_agent"] = $this->nk_entity_agent;
        $this->items["nk_user_agent"] = $this->nk_user_agent;
        $this->items["nk_report_delivery_opt"] = $this->nk_report_delivery_opt;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->name->setRangeLength(1, 201);
        $this->code->setRangeLength(1, 25);
        $this->alias->setRangeLength(1, 100);
        $this->prefix->setRangeLength(0, 25);
        $this->surname->setRangeLength(1, 100);
        $this->forename->setRangeLength(1, 100);
        $this->fiscal_id->setRangeLength(1, 25);
        $this->billing_prefs->setRangeLength(0, 100);
        $this->notes->setRangeLength(0, 500);

        $this->childEntityAddresses = array();
    }

    public function read(FUserSession $session, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM cc_entity WHERE id_entity = $id;";
        $statement = $this->connection->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->registryId = $row["id_entity"];

            $this->id_entity->setValue($row["id_entity"]);
            $this->name->setValue($row["name"]);
            $this->code->setValue($row["code"]);
            $this->alias->setValue($row["alias"]);
            $this->prefix->setValue($row["prefix"]);
            $this->surname->setValue($row["surname"]);
            $this->forename->setValue($row["forename"]);
            $this->fiscal_id->setValue($row["fiscal_id"]);
            $this->is_person->setValue($row["is_person"]);
            $this->is_credit->setValue($row["is_credit"]);
            $this->credit_days->setValue($row["credit_days"]);
            $this->billing_prefs->setValue($row["billing_prefs"]);
            $this->notes->setValue($row["notes"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_entity_class->setValue($row["fk_entity_class"]);
            $this->nk_market_segment->setValue($row["nk_market_segment"]);
            $this->nk_entity_parent->setValue($row["nk_entity_parent"]);
            $this->nk_entity_billing->setValue($row["nk_entity_billing"]);
            $this->nk_entity_agent->setValue($row["nk_entity_agent"]);
            $this->nk_user_agent->setValue($row["nk_user_agent"]);
            $this->nk_report_delivery_opt->setValue($row["nk_report_delivery_opt"]);
            $this->fk_user_ins->setValue($row["fk_user_ins"]);
            $this->fk_user_upd->setValue($row["fk_user_upd"]);
            $this->ts_user_ins->setValue($row["ts_user_ins"]);
            $this->ts_user_upd->setValue($row["ts_user_upd"]);

            $this->isRegistryNew = false;
            $this->mode = $mode;

            // read child entity addresses:

            $connection = FGuiUtils::createConnection();

            $sql = "SELECT id_entity_address FROM cc_entity_address WHERE fk_entity = $this->registryId ORDER BY id_entity_address;";
            $statement = $this->connection->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $entityAddress = new ModEntityAddress($connection);
                $entityAddress->read($session, $row["id_entity_address"], $mode);
                $this->childEntityAddresses[] = $entityAddress;
            }
        }
    }

    public function save(FUserSession $session)
    {
        $this->validate();

        $statement;

        if ($this->isRegistryNew) {
            $statement = $this->connection->prepare("INSERT INTO oc_test (" .
                "id_entity, " .
                "name, " .
                "code, " .
                "alias, " .
                "prefix, " .
                "surname, " .
                "forename, " .
                "fiscal_id, " .
                "is_person, " .
                "is_credit, " .
                "credit_days, " .
                "billing_prefs, " .
                "notes, " .
                "is_system, " .
                "is_deleted, " .
                "fk_entity_class, " .
                "nk_market_segment, " .
                "nk_entity_parent, " .
                "nk_entity_billing, " .
                "nk_entity_agent, " .
                "nk_user_agent, " .
                "nk_report_delivery_opt, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":name, " .
                ":code, " .
                ":alias, " .
                ":prefix, " .
                ":surname, " .
                ":forename, " .
                ":fiscal_id, " .
                ":is_person, " .
                ":is_credit, " .
                ":credit_days, " .
                ":billing_prefs, " .
                ":notes, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_entity_class, " .
                ":nk_market_segment, " .
                ":nk_entity_parent, " .
                ":nk_entity_billing, " .
                ":nk_entity_agent, " .
                ":nk_user_agent, " .
                ":nk_report_delivery_opt, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $this->connection->prepare("UPDATE oc_test SET " .
                "name = :name, " .
                "code = :code, " .
                "alias = :alias, " .
                "prefix = :prefix, " .
                "surname = :surname, " .
                "forename = :forename, " .
                "fiscal_id = :fiscal_id, " .
                "is_person = :is_person, " .
                "is_credit = :is_credit, " .
                "credit_days = :credit_days, " .
                "billing_prefs = :billing_prefs, " .
                "notes = :notes, " .
                //"is_system = :is_system, " .
                //"is_deleted = :is_deleted, " .
                "fk_entity_class = :fk_entity_class, " .
                "nk_market_segment = :nk_market_segment, " .
                "nk_entity_parent = :nk_entity_parent, " .
                "nk_entity_billing = :nk_entity_billing, " .
                "nk_entity_agent = :nk_entity_agent, " .
                "nk_user_agent = :nk_user_agent, " .
                "nk_report_delivery_opt = :nk_report_delivery_opt, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_test = :id;");
        }

        //$id_entity = $this->id_entity->getValue();
        $name = $this->name->getValue();
        $code = $this->code->getValue();
        $alias = $this->alias->getValue();
        $prefix = $this->prefix->getValue();
        $surname = $this->surname->getValue();
        $forename = $this->forename->getValue();
        $fiscal_id = $this->fiscal_id->getValue();
        $is_person = $this->is_person->getValue();
        $is_credit = $this->is_credit->getValue();
        $credit_days = $this->credit_days->getValue();
        $billing_prefs = $this->billing_prefs->getValue();
        $notes = $this->notes->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_entity_class = $this->fk_entity_class->getValue();
        $nk_market_segment = $this->nk_market_segment->getValue();
        $nk_entity_parent = $this->nk_entity_parent->getValue();
        $nk_entity_billing = $this->nk_entity_billing->getValue();
        $nk_entity_agent = $this->nk_entity_agent->getValue();
        $nk_user_agent = $this->nk_user_agent->getValue();
        $nk_report_delivery_opt = $this->nk_report_delivery_opt->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $session->getCurUser()->getId();

        //$statement->bindParam(":id_entity", $id_entity);
        $statement->bindParam(":name", $name);
        $statement->bindParam(":code", $code);
        $statement->bindParam(":alias", $alias);
        $statement->bindParam(":prefix", $prefix);
        $statement->bindParam(":surname", $surname);
        $statement->bindParam(":forename", $forename);
        $statement->bindParam(":fiscal_id", $fiscal_id);
        $statement->bindParam(":is_person", $is_person);
        $statement->bindParam(":is_credit", $is_credit);
        $statement->bindParam(":credit_days", $credit_days);
        $statement->bindParam(":billing_prefs", $billing_prefs);
        $statement->bindParam(":notes", $notes);
        //$statement->bindParam(":is_system", $is_system);
        //$statement->bindParam(":is_deleted", $is_deleted);
        $statement->bindParam(":fk_entity_class", $fk_entity_class);
        $statement->bindParam(":nk_market_segment", $nk_market_segment);
        $statement->bindParam(":nk_entity_parent", $nk_entity_parent);
        $statement->bindParam(":nk_entity_billing", $nk_entity_billing);
        $statement->bindParam(":nk_entity_agent", $nk_entity_agent);
        $statement->bindParam(":nk_user_agent", $nk_user_agent);
        $statement->bindParam(":nk_report_delivery_opt", $nk_report_delivery_opt);
        //$statement->bindParam(":fk_user_ins", $fk_user_ins);
        //$statement->bindParam(":fk_user_upd", $fk_user_upd);
        //$statement->bindParam(":ts_user_ins", $ts_user_ins);
        //$statement->bindParam(":ts_user_upd", $ts_user_upd);

        $statement->bindParam(":fk_user", $fk_user);

        if (!$this->isRegistryNew) {
            $statement->bindParam(":id", $this->registryId);
        }

        $statement->execute();

        $this->isRegistryModified = false;
        if ($this->isRegistryNew) {
            $this->registryId = $this->connection->lastInsertId();
            $this->isRegistryNew = false;
        }

        // save child entity addresses:

        foreach ($this->childEntityAddresses as $entityAddress) {
            $data = array();
            $data["fk_entity"] = $this->registryId;
            $entityAddress->setData($data);
            $entityAddress->save($session);
        }
    }

    public function delete(FUserSession $session)
    {

    }

    public function undelete(FUserSession $session)
    {

    }
}
