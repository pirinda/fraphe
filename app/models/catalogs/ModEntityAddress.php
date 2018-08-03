<?php
namespace app\models\catalogs;

use Fraphe\App\FUserSession;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\models\ModConsts;

class ModEntityAddress extends FRegistry
{
    public const PREFIX = "address_";

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
    protected $business_hr;
    protected $notes;
    protected $is_main;
    protected $is_recept;
    protected $is_process;
    protected $is_system;
    protected $is_deleted;
    protected $fk_entity;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    protected $childContacts;

    function __construct()
    {
        parent::__construct(AppConsts::CC_ENTITY_ADDRESS, AppConsts::$tableIds[AppConsts::CC_ENTITY_ADDRESS]);

        $this->id_entity_address = new FItem(FItem::DATA_TYPE_INT, "id_entity_address", "ID domicilio", "", false, true);
        $this->name = new FItem(FItem::DATA_TYPE_STRING, "name", "Nombre", "", true);
        $this->street = new FItem(FItem::DATA_TYPE_STRING, "street", "Calle y número", "", false);
        $this->district = new FItem(FItem::DATA_TYPE_STRING, "district", "Colonia", "", false);
        $this->postcode = new FItem(FItem::DATA_TYPE_STRING, "postcode", "Código postal", "", true);
        $this->reference = new FItem(FItem::DATA_TYPE_STRING, "reference", "Referencia", "", false);
        $this->city = new FItem(FItem::DATA_TYPE_STRING, "city", "Localidad", "", true);
        $this->county = new FItem(FItem::DATA_TYPE_STRING, "county", "Municipio", "", true);
        $this->state_region = new FItem(FItem::DATA_TYPE_STRING, "state_region", "Estado", "", true);
        $this->country = new FItem(FItem::DATA_TYPE_STRING, "country", "País", "", true);
        $this->location = new FItem(FItem::DATA_TYPE_STRING, "location", "Ubicación", "latitud, longitud", false);
        $this->business_hr = new FItem(FItem::DATA_TYPE_STRING, "business_hr", "Horario atención", "", false);
        $this->notes = new FItem(FItem::DATA_TYPE_STRING, "notes", "Notas", "", false);
        $this->is_main = new FItem(FItem::DATA_TYPE_BOOL, "is_main", "Sucursal principal", "", false);
        $this->is_recept = new FItem(FItem::DATA_TYPE_BOOL, "is_recept", "Sucursal recepción muestras", "", false);
        $this->is_process = new FItem(FItem::DATA_TYPE_BOOL, "is_process", "Sucursal proceso muestras", "", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_entity = new FItem(FItem::DATA_TYPE_INT, "fk_entity", "Entidad", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

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
        $this->items["business_hr"] = $this->business_hr;
        $this->items["notes"] = $this->notes;
        $this->items["is_main"] = $this->is_main;
        $this->items["is_recept"] = $this->is_recept;
        $this->items["is_process"] = $this->is_process;
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
        $this->business_hr->setRangeLength(0, 100);
        $this->notes->setRangeLength(0, 500);

        $this->clearChildContacts();
    }

    public function getChildContacts(): array
    {
        return $this->childContacts;
    }

    public function clearChildContacts()
    {
        $this->childContacts = array();
    }

    public function isChildContact(int $contactType): bool
    {
        $exists = false;

        foreach ($this->childContacts as $child) {
            if ($child->getDatum("fk_contact_type") == $contactType) {
                $exists = true;
                break;
            }
        }

        return $exists;
    }

    public function getChildContact($contactType): ModContact
    {
        $contact = null;

        foreach ($this->childContacts as $child) {
            if ($child->getDatum("fk_contactType") == $contactType) {
                $contact = $child;
                break;
            }
        }

        return $contact;
    }

    public function validate(FUserSession $userSession)
    {
        parent::validate($userSession);

        foreach ($this->childContacts as $contact) {
            $contact->validate($userSession);
        }
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM cc_entity_address WHERE id_entity_address = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_entity_address"]);

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
            $this->business_hr->setValue($row["business_hr"]);
            $this->notes->setValue($row["notes"]);
            $this->is_main->setValue($row["is_main"]);
            $this->is_recept->setValue($row["is_recept"]);
            $this->is_process->setValue($row["is_process"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_entity->setValue($row["fk_entity"]);
            $this->fk_user_ins->setValue($row["fk_user_ins"]);
            $this->fk_user_upd->setValue($row["fk_user_upd"]);
            $this->ts_user_ins->setValue($row["ts_user_ins"]);
            $this->ts_user_upd->setValue($row["ts_user_upd"]);

            $this->isRegistryNew = false;
            $this->mode = $mode;

            // create PDO connection for reading children:
            $pdo = FGuiUtils::createPdo();

            // read child contacts:
            $sql = "SELECT id_contact FROM cc_contact WHERE fk_entity_address = $this->id ORDER BY id_contact;";
            $statement = $pdo->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $contact = new ModContact();
                $contact->read($userSession, intval($row["id_contact"]), $mode);
                $this->childContacts[] = $contact;
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
            $statement = $userSession->getPdo()->prepare("INSERT INTO cc_entity_address (" .
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
                "business_hr, " .
                "notes, " .
                "is_main, " .
                "is_recept, " .
                "is_process, " .
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
                ":business_hr, " .
                ":notes, " .
                ":is_main, " .
                ":is_recept, " .
                ":is_process, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_entity, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE cc_entity_address SET " .
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
                "business_hr = :business_hr, " .
                "notes = :notes, " .
                "is_main = :is_main, " .
                "is_recept = :is_recept, " .
                "is_process = :is_process, " .
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
        $business_hr = $this->business_hr->getValue();
        $notes = $this->notes->getValue();
        $is_main = $this->is_main->getValue();
        $is_recept = $this->is_recept->getValue();
        $is_process = $this->is_process->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_entity = $this->fk_entity->getValue();
        //$fk_user_ins = $this->fk_user_ins->getValue();
        //$fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_entity_address", $id_entity_address, \PDO::PARAM_INT);
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
        $statement->bindParam(":business_hr", $business_hr);
        $statement->bindParam(":notes", $notes);
        $statement->bindParam(":is_main", $is_main, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_recept", $is_recept, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_process", $is_process, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_entity", $fk_entity, \PDO::PARAM_INT);
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

        // save child contacts:

        foreach ($this->childContacts as $contact) {
            // assure link to parent:
            $data = array();
            $data["fk_entity"] = $fk_entity;
            $data["fk_entity_address"] = $this->id;
            $contact->setData($data);

            // save child:
            $contact->save($userSession);
        }
    }

    public function delete(FUserSession $userSession)
    {

    }

    public function undelete(FUserSession $userSession)
    {

    }

    public static function createContactTypes(): array
    {
        return array(
            ModConsts::CC_CONTACT_TYPE_MAIN, ModConsts::CC_CONTACT_TYPE_TECH, ModConsts::CC_CONTACT_TYPE_RECEPT,
            ModConsts::CC_CONTACT_TYPE_SAMPLING, ModConsts::CC_CONTACT_TYPE_PROCESS, ModConsts::CC_CONTACT_TYPE_RESULT,
            ModConsts::CC_CONTACT_TYPE_QLT, ModConsts::CC_CONTACT_TYPE_MKT, ModConsts::CC_CONTACT_TYPE_BILL, ModConsts::CC_CONTACT_TYPE_COLL);
    }
}
