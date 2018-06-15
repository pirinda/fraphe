<?php
namespace app\models\operations;

use Fraphe\App\FUserSession;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

class CatTest extends FRegistry
{
    protected $name;
    protected $code;

    function __construct(\PDO $connection)
    {
        parent::__construct($connection, AppConsts::OC_TEST);

        $name = new FItem(FItem::DATA_TYPE_STRING, "name", "Nombre", false);
        $code = new FItem(FItem::DATA_TYPE_STRING, "code", "Código", false);

        $name->setRangeLength(1, 200);
        $code->setRangeLength(1, 25);

        $this->items["name"] = $name;
        $this->items["code"] = $code;
    }

    public function read(FUserSession $session, int $id, int $mode)
    {
        initialize();

        $sql = "SELECT * FROM oc_test WHERE id_test = $int;";
        $statement = $connection->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->registryId = $row['id_test'];
            $this->name->setValue($row['name']);
            $this->code->setValue($row['code']);

            $this->isRegistryNew = false;
            $this->mode = $mode;
        }
    }

    public function save(FUserSession $session)
    {
        $statement;

        if ($this->isRegistryNew) {
            $statement = $connection->prepare("INSERT INTO oc_test (" .
            "name, code, sample_quantity, sample_directs, " .
            "is_system, is_deleted, " .
            "fk_process_area, fk_sample_class, fk_testing_method, fk_test_acredit_attrib, " .
            "fk_user_ins, fk_user_upd, ts_user_ins, ts_user_upd) " .
            "VALUES (" .
            ":name, :code, :sample_quantity, :sample_directs, " .
            ":is_system, :is_deleted, " .
            ":fk_process_area, :fk_sample_class, :fk_testing_method, :fk_test_acredit_attrib, " .
            ":fk_user, 1, NOW(), NOW());");
        }
        else {

        }

        $statement->bindParam(':name', $name->getValue());
        $statement->bindParam(':code', $code->getValue());
        $statement->bindParam(':sample_quantity', "");
        $statement->bindParam(':sample_directs', "");
        $statement->bindParam(':is_system', false);
        $statement->bindParam(':is_deleted', false);
        $statement->bindParam(':fk_process_area', 1);
        $statement->bindParam(':fk_sample_class', 1);
        $statement->bindParam(':fk_testing_method', 1);
        $statement->bindParam(':fk_test_acredit_attrib', 1);
        $statement->bindParam(':fi_user', $session->getCurUser()->getUserId());

        $statement->execute();

        $this->isRegistryModified = false;
        if ($this->isRegistryNew) {
            $this->registryId = $connection->lastInsertId();
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
