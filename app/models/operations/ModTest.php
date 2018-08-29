<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\App\FGuiUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class ModTest extends FRegistry
{
    protected $id_test;
    protected $name;
    protected $code;
    protected $sample_quantity;
    protected $sample_directs;
    protected $is_system;
    protected $is_deleted;
    protected $fk_process_area;
    protected $fk_sample_class;
    protected $fk_testing_method;
    protected $fk_test_acredit_attrib;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    protected $childProcessEntitys;

    function __construct()
    {
        parent::__construct(AppConsts::OC_TEST, AppConsts::$tableIds[AppConsts::OC_TEST]);

        $this->id_test = new FItem(FItem::DATA_TYPE_INT, "id_test", "ID ensayo", "", false, true);
        $this->name = new FItem(FItem::DATA_TYPE_STRING, "name", "Nombre", "", true);
        $this->code = new FItem(FItem::DATA_TYPE_STRING, "code", "Código", "", true);
        $this->sample_quantity = new FItem(FItem::DATA_TYPE_STRING, "sample_quantity", "Cantidad muestra", "", true);
        $this->sample_directs = new FItem(FItem::DATA_TYPE_STRING, "sample_directs", "Indicaciones muestra", "", true);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Registro sistema", "", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Registro eliminado", "", false);
        $this->fk_process_area = new FItem(FItem::DATA_TYPE_INT, "fk_process_area", "Área proceso", "", true);
        $this->fk_sample_class = new FItem(FItem::DATA_TYPE_INT, "fk_sample_class", "Clase muestra", "", true);
        $this->fk_testing_method = new FItem(FItem::DATA_TYPE_INT, "fk_testing_method", "Método analítico", "", true);
        $this->fk_test_acredit_attrib = new FItem(FItem::DATA_TYPE_INT, "fk_test_acredit_attrib", "Acreditación/ autorización", "", true);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", "", false);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", "", false);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", "", false);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", "", false);

        $this->items["id_test"] = $this->id_test;
        $this->items["name"] = $this->name;
        $this->items["code"] = $this->code;
        $this->items["sample_quantity"] = $this->sample_quantity;
        $this->items["sample_directs"] = $this->sample_directs;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_process_area"] = $this->fk_process_area;
        $this->items["fk_sample_class"] = $this->fk_sample_class;
        $this->items["fk_testing_method"] = $this->fk_testing_method;
        $this->items["fk_test_acredit_attrib"] = $this->fk_test_acredit_attrib;
        $this->items["fk_user_ins"] = $this->fk_user_ins;
        $this->items["fk_user_upd"] = $this->fk_user_upd;
        $this->items["ts_user_ins"] = $this->ts_user_ins;
        $this->items["ts_user_upd"] = $this->ts_user_upd;

        $this->name->setRangeLength(1, 200);
        $this->code->setRangeLength(1, 25);
        $this->sample_quantity->setRangeLength(1, 100);
        $this->sample_directs->setRangeLength(1, 500);

        $this->childProcessEntitys = array();
    }

    public function &getChildProcessEntitys(): array
    {
        return $this->childProcessEntitys;
    }

    public function clearChildProcessEntitys(): array
    {
        $this->childProcessEntitys = array();
    }

    public function setDefaultChildProcessEntity(ModTestProcessEntity $defaultProcessEntity)
    {
        // clear is default flag in children:
        foreach ($this->childProcessEntitys as $processEntity) {
            $processEntity->getItem("is_default")->setValue(false);
        }

        // assure is default flag in supplied entity:
        $defaultProcessEntity->getItem("is_default")->setValue(true);

        // set default child:
        $found = false;
        $len = count($this->childProcessEntitys);
        for ($idx = 0; $idx < $len; $idx++) {
            if ($this->childProcessEntitys[$idx]->getDatum("id_test") == $defaultProcessEntity->getDatum("id_test") &&
            $this->childProcessEntitys[$idx]->getDatum("id_entity") == $defaultProcessEntity->getDatum("id_entity")) {
                $this->childProcessEntitys[$idx] = $defaultProcessEntity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $this->childProcessEntitys[] = $defaultProcessEntity;
        }
    }

    public function getDefaultChildProcessEntity()
    {
        $defaultProcessEntity = null;

        foreach ($this->childProcessEntitys as $processEntity) {
            if ($processEntity->getDatum("is_default")) {
                $defaultProcessEntity = $processEntity;
                break;
            }
        }

        return $defaultProcessEntity;
    }

    public function validate(FUserSession $userSession)
    {
        // validate registry:

        parent::validate($userSession);

        foreach ($this->childProcessEntitys as $processEntity) {
            $ids = array();
            $ids["id_test"] = $this->isRegistryNew ? -1 : $this->id;    // bypass validation
            $processEntity->setIds($ids);
            $processEntity->validate($userSession);
        }
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM oc_test WHERE id_test = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->id = intval($row["id_test"]);

            $this->id_test->setValue($row["id_test"]);
            $this->name->setValue($row["name"]);
            $this->code->setValue($row["code"]);
            $this->sample_quantity->setValue($row["sample_quantity"]);
            $this->sample_directs->setValue($row["sample_directs"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_process_area->setValue($row["fk_process_area"]);
            $this->fk_sample_class->setValue($row["fk_sample_class"]);
            $this->fk_testing_method->setValue($row["fk_testing_method"]);
            $this->fk_test_acredit_attrib->setValue($row["fk_test_acredit_attrib"]);
            $this->fk_user_ins->setValue($row["fk_user_ins"]);
            $this->fk_user_upd->setValue($row["fk_user_upd"]);
            $this->ts_user_ins->setValue($row["ts_user_ins"]);
            $this->ts_user_upd->setValue($row["ts_user_upd"]);

            $this->isRegistryNew = false;
            $this->mode = $mode;

            // create PDO connection for reading children:
            $pdo = FGuiUtils::createPdo();

            // read child process options:
            $sql = "SELECT id_test, id_entity FROM oc_test_process_entity WHERE id_test = $this->id ORDER BY id_test, id_entity;";
            $statement = $pdo->query($sql);
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $ids = array();
                $ids["id_test"] = intval($row["id_test"]);
                $ids["id_entity"] = intval($row["id_entity"]);

                $processEntity = new ModTestProcessEntity();
                $processEntity->retrieve($userSession, $ids, $mode);
                $this->childProcessEntitys[] = $processEntity;
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
        foreach ($this->childProcessEntitys as $processEntity) {
            // assure link to parent:
            $ids = array();
            $ids["id_test"] = $this->id;
            $processEntity->setIds($ids);

            // save child:
            $processEntity->save($userSession);
        }
    }

    public function delete(FUserSession $userSession)
    {

    }

    public function undelete(FUserSession $userSession)
    {

    }
}
