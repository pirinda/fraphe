<?php
namespace app\models\catalogs;

use Fraphe\App\FUserSession;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class ModUser extends FRegistry
{
    public const PREFIX = "user_";

    protected $id_user;
    protected $name;
    protected $prefix;
    protected $surname;
    protected $forename;
    protected $initials;
    protected $mail;
    protected $user_name;
    protected $user_pswd;
    protected $is_system;
    protected $is_deleted;
    protected $nk_entity;
    protected $nk_user_job;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    function __construct()
    {
        parent::__construct(AppConsts::CC_USER, AppConsts::$tables[AppConsts::CC_USER], AppConsts::$tableIds[AppConsts::CC_USER]);

        $this->id_user = new FItem(FItem::DATA_TYPE_INT, "id_user", "ID usuario", "", false, true);
        $this->name = new FItem(FItem::DATA_TYPE_STRING, "name", "Nombre", "", true);
        $this->prefix = new FItem(FItem::DATA_TYPE_STRING, "prefix", "Prefijo", "Sr., Lic., Ing.", false);
        $this->surname = new FItem(FItem::DATA_TYPE_STRING, "surname", "Apellido(s)", "", true);
        $this->forename = new FItem(FItem::DATA_TYPE_STRING, "forename", "Nombre(s)", "", true);
        $this->initials = new FItem(FItem::DATA_TYPE_STRING, "initials", "Iniciales", "", true);
        $this->mail = new FItem(FItem::DATA_TYPE_STRING, "mail", "Mail", "", false);
        $this->user_name = new FItem(FItem::DATA_TYPE_STRING, "user_name", "Nombre usuario", "", true);
        $this->user_pswd = new FItem(FItem::DATA_TYPE_STRING, "user_pswd", "Contraseña usuario", "", true);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->nk_entity = new FItem(FItem::DATA_TYPE_INT, "nk_entity", "Entidad", "", false);
        $this->nk_user_job = new FItem(FItem::DATA_TYPE_INT, "nk_user_job", "Puesto", "", false);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", true);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", true);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", true);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", true);

        $this->items["id_user"] = $this->id_user;
        $this->items["name"] = $this->name;
        $this->items["prefix"] = $this->prefix;
        $this->items["surname"] = $this->surname;
        $this->items["forename"] = $this->forename;
        $this->items["initials"] = $this->initials;
        $this->items["mail"] = $this->mail;
        $this->items["user_name"] = $this->user_name;
        $this->items["user_pswd"] = $this->user_pswd;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["nk_entity"] = $this->nk_entity;
        $this->items["nk_user_job"] = $this->nk_user_job;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->name->setRangeLength(1, 201);
        $this->prefix->setRangeLength(0, 25);
        $this->surname->setRangeLength(1, 100);
        $this->forename->setRangeLength(1, 100);
        $this->initials->setRangeLength(1, 10);
        $this->mail->setRangeLength(0, 200);
        $this->user_name->setRangeLength(1, 50);
        $this->user_pswd->setRangeLength(1, 200);
    }

    /** Overriden method.
     */
    public function validate(FUserSession $userSession)
    {
        // compute data:

        $this->name->setValue(trim($this->surname->getValue() . ' ' . $this->forename->getValue()));

        // validate registry:

        parent::validate($userSession);
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM $this->tableName WHERE id_user = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_user"]);

            $this->id_user->setValue($row["id_user"]);
            $this->name->setValue($row["name"]);
            $this->prefix->setValue($row["prefix"]);
            $this->surname->setValue($row["surname"]);
            $this->forename->setValue($row["forename"]);
            $this->initials->setValue($row["initials"]);
            $this->mail->setValue($row["mail"]);
            $this->user_name->setValue($row["user_name"]);
            $this->user_pswd->setValue($row["user_pswd"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->nk_entity->setValue($row["nk_entity"]);
            $this->nk_user_job->setValue($row["nk_user_job"]);
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
            $statement = $userSession->getPdo()->prepare("INSERT INTO $this->tableName (" .
                "id_user, " .
                "name, " .
                "prefix, " .
                "surname, " .
                "forename, " .
                "initials, " .
                "mail, " .
                "user_name, " .
                "user_pswd, " .
                "is_system, " .
                "is_deleted, " .
                "nk_entity, " .
                "nk_user_job, " .
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
                ":initials, " .
                ":mail, " .
                ":user_name, " .
                ":user_pswd, " .
                ":is_system, " .
                ":is_deleted, " .
                ":nk_entity, " .
                ":nk_user_job, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE $this->tableName SET " .
                "name = :name, " .
                "prefix = :prefix, " .
                "surname = :surname, " .
                "forename = :forename, " .
                "initials = :initials, " .
                "mail = :mail, " .
                "user_name = :user_name, " .
                "user_pswd = :user_pswd, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "nk_entity = :nk_entity, " .
                "nk_user_job = :nk_user_job, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_user = :id;");
        }

        //$id_user = $this->id_user->getValue();
        $name = $this->name->getValue();
        $prefix = $this->prefix->getValue();
        $surname = $this->surname->getValue();
        $forename = $this->forename->getValue();
        $initials = $this->initials->getValue();
        $mail = $this->mail->getValue();
        $user_name = $this->user_name->getValue();
        $user_pswd = $this->user_pswd->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $nk_entity = $this->nk_entity->getValue();
        $nk_user_job = $this->nk_user_job->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_user", $id_user, \PDO::PARAM_INT);
        $statement->bindParam(":name", $name);
        $statement->bindParam(":prefix", $prefix);
        $statement->bindParam(":surname", $surname);
        $statement->bindParam(":forename", $forename);
        $statement->bindParam(":initials", $initials);
        $statement->bindParam(":mail", $mail);
        $statement->bindParam(":user_name", $user_name);
        $statement->bindParam(":user_pswd", $user_pswd);
        $statement->bindParam(":is_system", $is_system, \PDO::PARAM_BOOL);
        $statement->bindParam(":is_deleted", $is_deleted, \PDO::PARAM_BOOL);
        $statement->bindParam(":nk_entity", $nk_entity, \PDO::PARAM_INT);
        $statement->bindParam(":nk_user_job", $nk_user_job, \PDO::PARAM_INT);
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
            $this->id_user->setValue($this->id);
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
