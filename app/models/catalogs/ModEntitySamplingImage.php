<?php
namespace app\models\catalogs;

use Fraphe\App\FGuiUtils;
use Fraphe\App\FUserSession;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\models\ModConsts;

class ModEntitySamplingImage extends FRegistry
{
    public const PREFIX = "entity_sampling_img_";
    public const PATH_IMG = "../../img/entity/";

    protected $id_entity_sampling_img;
    protected $sampling_img;
    protected $is_system;
    protected $is_deleted;
    protected $fk_entity;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    function __construct()
    {
        parent::__construct(AppConsts::CC_ENTITY_SAMPLING_IMG, AppConsts::$tables[AppConsts::CC_ENTITY_SAMPLING_IMG], AppConsts::$tableIds[AppConsts::CC_ENTITY_SAMPLING_IMG]);

        $this->id_entity_sampling_img = new FItem(FItem::DATA_TYPE_INT, "id_entity_sampling_img", "ID imagen entidad", "", false, true);
        $this->sampling_img = new FItem(FItem::DATA_TYPE_STRING, "sampling_img", "Imagen muestreo", "", true);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_entity = new FItem(FItem::DATA_TYPE_INT, "fk_entity", "Entidad", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_entity_sampling_img"] = $this->id_entity_sampling_img;
        $this->items["sampling_img"] = $this->sampling_img;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_entity"] = $this->fk_entity;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->sampling_img->setRangeLength(1, 250);
    }

    public function getTargetFile(): string
    {
        return self::PATH_IMG . $this->sampling_img->getValue();
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM $this->tableName WHERE id_entity_sampling_img = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_entity_sampling_img"]);

            $this->id_entity_sampling_img->setValue($row["id_entity_sampling_img"]);
            $this->sampling_img->setValue($row["sampling_img"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_entity->setValue($row["fk_entity"]);
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
                "id_entity_sampling_img, " .
                "sampling_img, " .
                "is_system, " .
                "is_deleted, " .
                "fk_entity, " .
                "fk_user_ins, " .
                "fk_user_upd, " .
                "ts_user_ins, " .
                "ts_user_upd) " .
                "VALUES (" .
                "0, " .
                ":sampling_img, " .
                ":is_system, " .
                ":is_deleted, " .
                ":fk_entity, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $userSession->getPdo()->prepare("UPDATE $this->tableName SET " .
                "sampling_img = :sampling_img, " .
                "is_system = :is_system, " .
                "is_deleted = :is_deleted, " .
                "fk_entity = :fk_entity, " .
                //"fk_user_ins = :fk_user_ins, " .
                "fk_user_upd = :fk_user, " .
                //"ts_user_ins = :ts_user_ins, " .
                "ts_user_upd = NOW() " .
                "WHERE id_entity_sampling_img = :id;");
        }

        //$id_entity_sampling_img = $this->id_entity_sampling_img->getValue();
        $sampling_img = $this->sampling_img->getValue();
        $is_system = $this->is_system->getValue();
        $is_deleted = $this->is_deleted->getValue();
        $fk_entity = $this->fk_entity->getValue();
        $fk_user_ins = $this->fk_user_ins->getValue();
        $fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $userSession->getCurUser()->getId();

        //$statement->bindParam(":id_entity_sampling_img", $id_entity_sampling_img, \PDO::PARAM_INT);
        $statement->bindParam(":sampling_img", $sampling_img);
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
            $this->id_entity_sampling_img->setValue($this->id);
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
