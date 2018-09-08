<?php
namespace Fraphe\Model;

use Fraphe\App\FUserSession;

abstract class FRelation extends FRegistry
{
    protected $ids; // associative array of relation IDs in the key=id format

    /* Creates new base registry. Each field of registry must be contained in member array $items. Relation IDs are a "subset" of this registry items.
     */
    public function __construct(int $registryType)
    {
        $this->ids = array();

        parent::__construct($registryType, "");
    }

    /* Initializes registry data.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function initialize()
    {
        parent::initialize();

        foreach ($this->ids as $key => $id) {
            $this->ids[$key] = 0;
        }
    }

    /* Validates registry data.
     * Must be called at the begining of method save().
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function validate(FUserSession $userSession)
    {
        parent::validate($userSession);

        foreach ($this->ids as $key => $id) {
            if (!is_int($id)) {
                throw new \Exception(__METHOD__ . ": El ID '$key' debe ser número entero.");
            }
            if ($id == 0) {
                throw new \Exception(__METHOD__ . ": El ID '$key' debe ser diferente de cero.");
            }
        }
    }

    protected function copyIdsFromItems()
    {
        foreach ($this->ids as $key => $id) {
            $this->ids[$key] = $this->items[$key]->getValue();
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

        $this->copyIdsFromItems();
    }

    /* Gets relation IDs.
     * Returns: associative array of relation IDs in the key=id form.
     * Throws: Exception if something fails.
     */
    public function getIds(): array
    {
        return $this->ids;
    }

    public function getId(): int
    {
        throw new \Exception(__METHOD__ . ": La relación no tiene ID, sino un conjunto de IDs.");
    }

    /* Compare relation IDs with supplied ones.
     * Param $ids: associative array of relation IDs in the key=id format to be compared.
     * Returns: true if supplied relation IDs are equal to this registry's.
     * Throws: Exception if something fails.
     */
    public function compareIds(array $ids): bool
    {
        // validate keys:
        foreach ($ids as $key => $id) {
            $this->validateItemKey($key);
            if (!is_int($id)) {
                throw new \Exception(__METHOD__ . ": El ID '$key' debe ser número entero: $id es '" . gettype($id) . "'.");
            }
        }

        if (count($this->ids) != count($ids)) {
            throw new \Exception(__METHOD__ . ": El número de ID es distinta: este objeto tiene " . count($this->ids) . ", los suministrados son " . count($ids) . ".");
        }

        return empty(array_diff_assoc($this->ids, $ids)); // true when empty, that is, when no differences are found
    }

    public function read(FUserSession $userSession, int $id, int $mode)
    {
        throw new \Exception(__METHOD__ . ": Método no disponible para relaciones.");
    }

    /* Similar to base method read(), but for relations.
     * Param $userSession: user session.
     * Param $ids: associative array of relation IDs in the key=id format.
     * Param $mode: read mode. Options declared in class Fraphe\Model\FRegistry.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    abstract public function retrieve(FUserSession $userSession, array $ids, int $mode);

    /* Checks if registry is new.
     * Returns: number of ocurrences found of registry.
     */
    public function checkRegistryNew(FUserSession $userSession, string $table): int
    {
        $sql = "SELECT COUNT(*) AS _count FROM $table WHERE ";
        $where = "";
        foreach ($this->ids as $key => $id) {
            $where .= ($where == "" ? "" : "AND ") . "$key = $id ";
        }
        $sql .= $where;

        $count = 0;
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $count = $row["_count"];
        }
        else {
            throw new \Exception(__METHOD__ . ": " . FRegistry::ERR_MSG_REGISTRY_NOT_FOUND);
        }

        return $count;
    }
}
