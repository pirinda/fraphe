<?php
namespace app\models\catalogs;

use Fraphe\App\FUserSession;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class ModEntityAddress extends FRegistry
{
    protected $id_entity_address;
    protected $name;
    protected $street;
    protected $district;
    protected $postcode;
    protected $reference;
    protected $city;
    protected $county;
    protected $state_region;
    protected $country;
    protected $location;
    protected $notes;
    protected $is_main;
    protected $is_system;
    protected $is_deleted;
    protected $fk_entity;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    protected $childContacts;

    function __construct(\PDO $connection)
    {
        parent::__construct($connection, AppConsts::CC_ENTITY_ADDRESS, "id_entity_address");

        $this->id_entity_address = new FItem(FItem::DATA_TYPE_INT, "id_entity_address", "ID domicilio", false);
        $this->name = new FItem(FItem::DATA_TYPE_STRING, "name", "Nombre", true);
        $this->street = new FItem(FItem::DATA_TYPE_STRING, "street", "Calle y número", true);
        $this->district = new FItem(FItem::DATA_TYPE_STRING, "district", "Colonia", true);
        $this->postcode = new FItem(FItem::DATA_TYPE_STRING, "postcode", "Código postal", true);
        $this->reference = new FItem(FItem::DATA_TYPE_STRING, "reference", "Referencia", true);
        $this->city = new FItem(FItem::DATA_TYPE_STRING, "city", "Localidad", true);
        $this->county = new FItem(FItem::DATA_TYPE_STRING, "county", "Municipio", true);
        $this->state_region = new FItem(FItem::DATA_TYPE_STRING, "state_region", "Estado", true);
        $this->country = new FItem(FItem::DATA_TYPE_STRING, "country", "País", true);
        $this->location = new FItem(FItem::DATA_TYPE_STRING, "location", "Ubicación", true);
        $this->notes = new FItem(FItem::DATA_TYPE_STRING, "notes", "Notas", true);
        $this->is_main = new FItem(FItem::DATA_TYPE_BOOL, "is_main", "Principal", true);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", true);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", true);
        $this->fk_entity = new FItem(FItem::DATA_TYPE_INT, "fk_entity", "Entidad", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", false);

        $this->items["id_entity_address"] = $this->id_entity_address;
        $this->items["name"] = $this->name;
        $this->items["street"] = $this->street;
        $this->items["district"] = $this->district;
        $this->items["postcode"] = $this->postcode;
        $this->items["reference"] = $this->reference;
        $this->items["city"] = $this->city;
        $this->items["county"] = $this->county;
        $this->items["state_region"] = $this->state_region;
        $this->items["country"] = $this->country;
        $this->items["location"] = $this->location;
        $this->items["notes"] = $this->notes;
        $this->items["is_main"] = $this->is_main;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_entity"] = $this->fk_entity;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->name->setRangeLength(1, 50);
        $this->street->setRangeLength(0, 200);
        $this->district->setRangeLength(0, 100);
        $this->postcode->setRangeLength(1, 15);
        $this->reference->setRangeLength(0, 100);
        $this->city->setRangeLength(1, 50);
        $this->county->setRangeLength(1, 50);
        $this->state_region->setRangeLength(1, 50);
        $this->country->setRangeLength(1, 3);
        $this->location->setRangeLength(0, 25);
        $this->notes->setRangeLength(0, 500);

        $this->childContacts = array();
    }

    public function getChildContacts(): array
    {
        return $this->childContacts;
    }

    public function validate()
    {
        parent::validate();

        foreach ($this->childContacts as $contact) {
            $contact->validate();
        }
    }

    public function read(FUserSession $session, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM cc_entity_address WHERE id_entity_address = $id;";
        $statement = $this->connection->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = $row["id_entity_address"];

            $this->id_entity_address->setValue($row["id_entity_address"]);
            $this->name->setValue($row["name"]);
            $this->street->setValue($row["street"]);
            $this->district->setValue($row["district"]);
            $this->postcode->setValue($row["postcode"]);
            $this->reference->setValue($row["reference"]);
            $this->city->setValue($row["city"]);
            $this->county->setValue($row["county"]);
            $this->state_region->setValue($row["state_region"]);
            $this->country->setValue($row["country"]);
            $this->location->setValue($row["location"]);
            $this->notes->setValue($row["notes"]);
            $this->is_main->setValue($row["is_main"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_entity->setValue($row["fk_entity"]);
            $this->fk_user_ins->setValue($row["fk_user_ins"]);
            $this->fk_user_upd->setValue($row["fk_user_upd"]);
            $this->ts_user_ins->setValue($row["ts_user_ins"]);
            $this->ts_user_upd->setValue($row["ts_user_upd"]);

            $this->isRegistryNew = false;
            $this->mode = $mode;

            // create connection for reading children:
            $connection = FGuiUtils::createConnection();

            // read child contacts:
            $sql = "SELECT id_contact FROM cc_contact WHERE fk_entity_address = $this->id ORDER BY id_contact;";
            $statement = $this->connection->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $contact = new ModContact($connection);
                $contact->read($session, $row["id_contact"], $mode);
                $this->childContacts[] = $contact;
            }
        }
        else {
            throw new \Exception(__METHOD__ . ": " . FRegistry::ERR_MSG_REGISTRY_NOT_FOUND);
        }
    }

    public function save(FUserSession $session)
    {
        $this->validate();

        $statement;

        if ($this->isRegistryNew) {
            $statement = $this->connection->prepare("INSERT INTO cc_entity_address (" .
                "id_entity_address, " .
                "name, " .
                "street, " .
                "district, " .
                "postcode, " .
                "reference, " .
                "city, " .
                "county, " .
                "state_region, " .
                "country, " .
                "location, " .
                "notes, " .
                "is_main, " .
                "is_system, " .
                "is_deleted, " .
                "fk_entity, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":name, " .
                ":street, " .
                ":district, " .
                ":postcode, " .
                ":reference, " .
                ":city, " .
                ":county, " .
                ":state_region, " .
                ":country, " .
                ":location, " .
                ":notes, " .
                ":is_main, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_entity, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $this->connection->prepare("UPDATE cc_entity_address SET " .
                "name = :name, " .
                "street = :street, " .
                "district = :district, " .
                "postcode = :postcode, " .
                "reference = :reference, " .
                "city = :city, " .
                "county = :county, " .
                "state_region = :state_region, " .
                "country = :country, " .
                "location = :location, " .
                "notes = :notes, " .
                "is_main = :is_main, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_entity = :fk_entity, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_entity_address = :id;");
        }

        //$id_entity_address = $this->id_entity_address->getValue();
        $name = $this->name->getValue();
        $street = $this->street->getValue();
        $district = $this->district->getValue();
        $postcode = $this->postcode->getValue();
        $reference = $this->reference->getValue();
        $city = $this->city->getValue();
        $county = $this->county->getValue();
        $state_region = $this->state_region->getValue();
        $country = $this->country->getValue();
        $location = $this->location->getValue();
        $notes = $this->notes->getValue();
        $is_main = $this->is_main->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_entity = $this->fk_entity->getValue();
        //$fk_user_ins = $this->fk_user_ins->getValue();
        //$fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $session->getCurUser()->getId();

        //$statement->bindParam(":id_entity_address", $id_entity_address);
        $statement->bindParam(":name", $name);
        $statement->bindParam(":street", $street);
        $statement->bindParam(":district", $district);
        $statement->bindParam(":postcode", $postcode);
        $statement->bindParam(":reference", $reference);
        $statement->bindParam(":city", $city);
        $statement->bindParam(":county", $county);
        $statement->bindParam(":state_region", $state_region);
        $statement->bindParam(":country", $country);
        $statement->bindParam(":location", $location);
        $statement->bindParam(":notes", $notes);
        $statement->bindParam(":is_main", $is_main, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_entity", $fk_entity);
        //$statement->bindParam(":fk_user_ins", $fk_user_ins);
        //$statement->bindParam(":fk_user_upd", $fk_user_upd);
        //$statement->bindParam(":ts_user_ins", $ts_user_ins);
        //$statement->bindParam(":ts_user_upd", $ts_user_upd);

        $statement->bindParam(":fk_user", $fk_user);

        if (!$this->isRegistryNew) {
            $statement->bindParam(":id", $this->id);
        }

        $statement->execute();

        $this->isRegistryModified = false;
        if ($this->isRegistryNew) {
            $this->id = intval($this->connection->lastInsertId());
            $this->isRegistryNew = false;
        }

        // save child contacts:

        foreach ($this->childContacts as $contact) {
            // assure link to parent:
            $data = array();
            $data["fk_entity"] = $fk_entity;
            $data["fk_entity_address"] = $this->id;
            $contact->setData($data);

            // save child:
            $contact->save($session);
        }
    }

    public function delete(FUserSession $session)
    {

    }

    public function undelete(FUserSession $session)
    {

    }
}
