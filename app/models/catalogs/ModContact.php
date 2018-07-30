<?php
namespace app\models\catalogs;

use Fraphe\App\FUserSession;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class ModContact extends FRegistry
{
    protected $id_contact;
    protected $name;
    protected $prefix;
    protected $surname;
    protected $forename;
    protected $job;
    protected $mail;
    protected $phone;
    protected $mobile;
    protected $is_report;
    protected $is_system;
    protected $is_deleted;
    protected $fk_entity;
    protected $fk_entity_address;
    protected $fk_contact_type;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    function __construct()
    {
        parent::__construct(AppConsts::CC_CONTACT, "id_contact");

        $this->id_contact = new FItem(FItem::DATA_TYPE_INT, "id_contact", "ID contacto", false);
        $this->name = new FItem(FItem::DATA_TYPE_STRING, "name", "Nombre", true);
        $this->prefix = new FItem(FItem::DATA_TYPE_STRING, "prefix", "Prefijo", true);
        $this->surname = new FItem(FItem::DATA_TYPE_STRING, "surname", "Apellidos", true);
        $this->forename = new FItem(FItem::DATA_TYPE_STRING, "forename", "Nombres", true);
        $this->job = new FItem(FItem::DATA_TYPE_STRING, "job", "Puesto", true);
        $this->mail = new FItem(FItem::DATA_TYPE_STRING, "mail", "Mail", true);
        $this->phone = new FItem(FItem::DATA_TYPE_STRING, "phone", "Teléfono", true);
        $this->mobile = new FItem(FItem::DATA_TYPE_STRING, "mobile", "Móvil", true);
        $this->is_report = new FItem(FItem::DATA_TYPE_BOOL, "is_report", "Contacto informe resultados", true);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", true);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", true);
        $this->fk_entity = new FItem(FItem::DATA_TYPE_INT, "fk_entity", "Entidad", true);
        $this->fk_entity_address = new FItem(FItem::DATA_TYPE_INT, "fk_entity_address", "Domicilio", true);
        $this->fk_contact_type = new FItem(FItem::DATA_TYPE_INT, "fk_contact_type", "Tipo contacto", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", false);

        $this->items["id_contact"] = $this->id_contact;
        $this->items["name"] = $this->name;
        $this->items["prefix"] = $this->prefix;
        $this->items["surname"] = $this->surname;
        $this->items["forename"] = $this->forename;
        $this->items["job"] = $this->job;
        $this->items["mail"] = $this->mail;
        $this->items["phone"] = $this->phone;
        $this->items["mobile"] = $this->mobile;
        $this->items["is_report"] = $this->is_report;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_entity"] = $this->fk_entity;
        $this->items["fk_entity_address"] = $this->fk_entity_address;
        $this->items["fk_contact_type"] = $this->fk_contact_type;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->name->setRangeLength(1, 201);
        $this->prefix->setRangeLength(0, 25);
        $this->surname->setRangeLength(1, 100);
        $this->forename->setRangeLength(1, 100);
        $this->job->setRangeLength(0, 50);
        $this->mail->setRangeLength(0, 200);
        $this->phone->setRangeLength(0, 100);
        $this->mobile->setRangeLength(0, 100);
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM cc_contact WHERE id_contact = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = $row["id_contact"];

            $this->id_contact->setValue($row["id_contact"]);
            $this->name->setValue($row["name"]);
            $this->prefix->setValue($row["prefix"]);
            $this->surname->setValue($row["surname"]);
            $this->forename->setValue($row["forename"]);
            $this->job->setValue($row["job"]);
            $this->mail->setValue($row["mail"]);
            $this->phone->setValue($row["phone"]);
            $this->mobile->setValue($row["mobile"]);
            $this->is_report->setValue($row["is_report"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_entity->setValue($row["fk_entity"]);
            $this->fk_entity_address->setValue($row["fk_entity_address"]);
            $this->fk_contact_type->setValue($row["fk_contact_type"]);
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
            $statement = $userSession->getPdo()->prepare("INSERT INTO oc_test (" .
                "id_contact, " .
                "name, " .
                "prefix, " .
                "surname, " .
                "forename, " .
                "job, " .
                "mail, " .
                "phone, " .
                "mobile, " .
                "is_report, " .
                "is_system, " .
                "is_deleted, " .
                "fk_entity, " .
                "fk_entity_address, " .
                "fk_contact_type, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":name, " .
                ":prefix, " .
                ":surname, " .
                ":forename, " .
                ":job, " .
                ":mail, " .
                ":phone, " .
                ":mobile, " .
                ":is_report, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_entity, " .
                ":fk_entity_address, " .
                ":fk_contact_type, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE oc_test SET " .
                "name = :name, " .
                "prefix = :prefix, " .
                "surname = :surname, " .
                "forename = :forename, " .
                "job = :job, " .
                "mail = :mail, " .
                "phone = :phone, " .
                "mobile = :mobile, " .
                "is_report = :is_report, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_entity = :fk_entity, " .
                "fk_entity_address = :fk_entity_address, " .
                "fk_contact_type = :fk_contact_type, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_contact = :id;");
        }

        //$id_contact = $this->id_contact->getValue();
        $name = $this->name->getValue();
        $prefix = $this->prefix->getValue();
        $surname = $this->surname->getValue();
        $forename = $this->forename->getValue();
        $job = $this->job->getValue();
        $mail = $this->mail->getValue();
        $phone = $this->phone->getValue();
        $mobile = $this->mobile->getValue();
        $is_report = $this->is_report->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_entity = $this->fk_entity->getValue();
        $fk_entity_address = $this->fk_entity_address->getValue();
        $fk_contact_type = $this->fk_contact_type->getValue();
        //$fk_user_ins = $this->fk_user_ins->getValue();
        //$fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_contact", $id_contact);
        $statement->bindParam(":name", $name);
        $statement->bindParam(":prefix", $prefix);
        $statement->bindParam(":surname", $surname);
        $statement->bindParam(":forename", $forename);
        $statement->bindParam(":job", $job);
        $statement->bindParam(":mail", $mail);
        $statement->bindParam(":phone", $phone);
        $statement->bindParam(":mobile", $mobile);
        $statement->bindParam(":is_report", $is_report, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":fk_entity", $fk_entity);
        $statement->bindParam(":fk_entity_address", $fk_entity_address);
        $statement->bindParam(":fk_contact_type", $fk_contact_type);
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
            $this->id = intval($userSession->getPdo()->lastInsertId());
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
