<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
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
    protected $fk_sample_category;
    protected $fk_testing_method;
    protected $fk_test_acredit_attrib;
    protected $fk_user_ins;
    protected $fk_user_upd;
    protected $ts_user_ins;
    protected $ts_user_upd;

    function __construct(\PDO $connection)
    {
        parent::__construct($connection, AppConsts::OC_TEST);

        $this->id_test = new FItem(FItem::DATA_TYPE_INT, "id_test", "ID ensayo", true);
        $this->name = new FItem(FItem::DATA_TYPE_STRING, "name", "Nombre", false);
        $this->code = new FItem(FItem::DATA_TYPE_STRING, "code", "Código", false);
        $this->sample_quantity = new FItem(FItem::DATA_TYPE_STRING, "sample_quantity", "Cantidad muestra", false);
        $this->sample_directs = new FItem(FItem::DATA_TYPE_STRING, "sample_directs", "Indicaciones muestra", false);
        $this->is_system = new FItem(FItem::DATA_TYPE_BOOL, "is_system", "Es de sistema", false);
        $this->is_deleted = new FItem(FItem::DATA_TYPE_BOOL, "is_deleted", "Está eliminado", false);
        $this->fk_process_area = new FItem(FItem::DATA_TYPE_INT, "fk_process_area", "Área de proceso", false);
        $this->fk_sample_category = new FItem(FItem::DATA_TYPE_INT, "fk_sample_category", "Clase de muestra", false);
        $this->fk_testing_method = new FItem(FItem::DATA_TYPE_INT, "fk_testing_method", "Método analítico", false);
        $this->fk_test_acredit_attrib = new FItem(FItem::DATA_TYPE_INT, "fk_test_acredit_attrib", "Acreditación, autorización", false);
        $this->fk_user_ins = new FItem(FItem::DATA_TYPE_INT, "fk_user_ins", "Creador", true);
        $this->fk_user_upd = new FItem(FItem::DATA_TYPE_INT, "fk_user_upd", "Modificador", true);
        $this->ts_user_ins = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_ins", "Creado", true);
        $this->ts_user_upd = new FItem(FItem::DATA_TYPE_TIMESTAMP, "ts_user_upd", "Modificado", true);

        $this->items["id_test"] = $this->id_test;
        $this->items["name"] = $this->name;
        $this->items["code"] = $this->code;
        $this->items["sample_quantity"] = $this->sample_quantity;
        $this->items["sample_directs"] = $this->sample_directs;
        $this->items["is_system"] = $this->is_system;
        $this->items["is_deleted"] = $this->is_deleted;
        $this->items["fk_process_area"] = $this->fk_process_area;
        $this->items["fk_sample_category"] = $this->fk_sample_category;
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
    }

    public function read(FUserSession $session, int $id, int $mode)
    {
        $this->initialize();

        $sql = "SELECT * FROM oc_test WHERE id_test = $id;";
        $statement = $this->connection->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->registryId = $row["id_test"];

            $this->id_test->setValue($row["id_test"]);
            $this->name->setValue($row["name"]);
            $this->code->setValue($row["code"]);
            $this->sample_quantity->setValue($row["sample_quantity"]);
            $this->sample_directs->setValue($row["sample_directs"]);
            $this->is_system->setValue($row["is_system"]);
            $this->is_deleted->setValue($row["is_deleted"]);
            $this->fk_process_area->setValue($row["fk_process_area"]);
            $this->fk_sample_category->setValue($row["fk_sample_category"]);
            $this->fk_testing_method->setValue($row["fk_testing_method"]);
            $this->fk_test_acredit_attrib->setValue($row["fk_test_acredit_attrib"]);
            $this->fk_user_ins->setValue($row["fk_user_ins"]);
            $this->fk_user_upd->setValue($row["fk_user_upd"]);
            $this->ts_user_ins->setValue($row["ts_user_ins"]);
            $this->ts_user_upd->setValue($row["ts_user_upd"]);

            $this->isRegistryNew = false;
            $this->mode = $mode;
        }
    }

    public function save(FUserSession $session)
    {
        $this->validate();

        $statement;

        if ($this->isRegistryNew) {
            $statement = $this->connection->prepare("INSERT INTO oc_test (" .
                "id_test, " .
                "name, " .
                "code, " .
                "sample_quantity, " .
                "sample_directs, " .
                "is_system, " .
                "is_deleted, " .
                "fk_process_area, " .
                "fk_sample_category, " .
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
                "true, " .
                "false, " .
                ":fk_process_area, " .
                ":fk_sample_category, " .
                ":fk_testing_method, " .
                ":fk_test_acredit_attrib, " .
                ":fk_user, " .
                "1, " .
                "NOW(), " .
                "NOW());");
        }
        else {
            $statement = $this->connection->prepare("UPDATE oc_test SET " .
                "name = :name, " .
                "code = :code, " .
                "sample_quantity = :sample_quantity, " .
                "sample_directs = :sample_directs, " .
                //"is_system = :is_system, " .
                //"is_deleted = :is_deleted, " .
                "fk_process_area = :fk_process_area, " .
                "fk_sample_category = :fk_sample_category, " .
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
        $fk_sample_category = $this->fk_sample_category->getValue();
        $fk_testing_method = $this->fk_testing_method->getValue();
        $fk_test_acredit_attrib = $this->fk_test_acredit_attrib->getValue();
        //$fk_user_ins = $this->fk_user_ins->getValue();
        //$fk_user_upd = $this->fk_user_upd->getValue();
        //$ts_user_ins = $this->ts_user_ins->getValue();
        //$ts_user_upd = $this->ts_user_upd->getValue();

        $fk_user = $session->getCurUser()->getId();

        //$statement->bindParam(":id_test", $id_test);
        $statement->bindParam(":name", $name);
        $statement->bindParam(":code", $code);
        $statement->bindParam(":sample_quantity", $sample_quantity);
        $statement->bindParam(":sample_directs", $sample_directs);
        //$statement->bindParam(":is_system", $is_system);
        //$statement->bindParam(":is_deleted", $is_deleted);
        $statement->bindParam(":fk_process_area", $fk_process_area);
        $statement->bindParam(":fk_sample_category", $fk_sample_category);
        $statement->bindParam(":fk_testing_method", $fk_testing_method);
        $statement->bindParam(":fk_test_acredit_attrib", $fk_test_acredit_attrib);
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
    }

    public function delete(FUserSession $session)
    {

    }

    public function undelete(FUserSession $session)
    {

    }
}
