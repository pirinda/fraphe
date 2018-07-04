<?php
namespace Fraphe\Model;

use Fraphe\App\FUserSession;

abstract class FRelation extends FRegistry
{
    protected $relationIds; // associative array of int values

    /* Creates new base registry. Each field of registry must be contained in member array $items. Relation IDs are a subset of this registry items.
     */
    public function __construct(\PDO $connection, int $registryType)
    {
        $this->relationIds = array();

        parent::__construct($connection, $registryType);
    }

    /* Initializes registry data.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function initialize()
    {
        parent::initialize();

        foreach ($this->relationIds as $key => $id) {
            $this->relationIds[$key] = 0;
        }
    }

    /* Validates registry data.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function validate()
    {
        parent::validate();

        foreach ($this->relationIds as $key => $id) {
            if (!is_int($id)) {
                throw new \Exception(__METHOD__ . ": El ID '$key' debe ser número entero.");
            }
        }
    }

    /* Validates registry data on save.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    protected function validateOnSave()
    {
        $this->validate();

        foreach ($this->relationIds as $key => $id) {
            if ($id == 0) {
                throw new \Exception(__METHOD__ . ": El ID '$key' debe ser diferente de cero.");
            }
        }
    }

    protected function copyRelationIdsToItems()
    {
        $data = array();

        foreach ($this->relationIds as $key => $id) {
            $data[$key] = intval($id);
        }

        $this->setData($data);
    }

    protected function copyRelationIdsFromItems()
    {
        foreach ($this->relationIds as $key => $id) {
            $this->relationIds[$key] = $this->items[$key]->getValue();
        }
    }

    /* Sets registry data.
     * Param $array: associative array of registry data in the key=value format. Special case: key="id", that is used for setting ID of this registry.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function setData(array $data)
    {
        parent::setData($data);

        $this->copyRelationIdsFromItems();
    }

    /* Sets relation IDs.
     * Param $ids: associative array of relation IDs in the key=id format.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function setRelationIds(array $ids)
    {
        // validate keys:
        foreach ($ids as $key => $id) {
            $this->validateItemKey($key);
            if (!is_int($id)) {
                throw new \Exception(__METHOD__ . ": El ID '$key' debe ser número entero.");
            }
        }

        // set relation IDs:
        foreach ($ids as $key => $id) {
            $this->relationIds[$key] = $id;

            $this->isRegistryModified = true;
        }

        $this->copyRelationIdsToItems();
    }

    /* Gets relation IDs.
     * Returns: associative array of relation IDs in the key=id form.
     * Throws: Exception if something fails.
     */
    public function getRelationIds(): array
    {
        return $this->relationIds;
    }

    /* Gets relation ID.
     * Param $key: key of required ID.
     * Returns: required ID.
     * Throws: Exception if something fails.
     */
    public function getRelationId($key): int
    {
        $this->validateItemKey($key);
        return $this->relationIds[$key];
    }

    public function getRegistryId(): int
    {
        throw new \Exception(__METHOD__ . ": La relación no tiene ID, sino un conjunto de IDs.");
    }

    public function read(FUserSession $session, int $id, int $mode)
    {
        throw new \Exception(__METHOD__ . ": Método no disponible para relaciones.");
    }

    /* Similar to base method read(), but for relations.
     * Param $session: user session.
     * Param $ids: associative array of relation IDs in the key=id format.
     * Param $mode: read mode. Options declared in class Fraphe\Model\FRegistry.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    abstract public function retrieve(FUserSession $session, array $ids, int $mode);

    /* Checks if registry is new.
     * Returns: number of ocurrences found of registry.
     */
    public function checkRegistryNew(FUserSession $session, string $table): int
    {
        $sql = "SELECT COUNT(*) AS _count FROM $table WHERE ";
        $where = "";
        foreach ($this->relationIds as $key => $id) {
            $where .= ($where == "" ? "" : "AND ") . "$key = $id ";
        }
        $sql .= $where;

        $count = 0;
        $statement = $this->connection->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $count = $row["_count"];
        }
        else {
            throw new \Exception(__METHOD__ . ": " . FRegistry::ERR_MSG_REGISTRY_NOT_FOUND);
        }

        return $count;
    }

}
