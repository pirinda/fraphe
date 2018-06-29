<?php
namespace Fraphe\Model;

use Fraphe\App\FUserSession;

abstract class FRelation extends FRegisistry
{
    protected $relationIds; // associative array of int values

    /* Creates new base registry. Each field of registry must be contained in member array $items. Relation IDs are a subset of this registry items.
     */
    public function __construct(\PDO $connection, int $registryType)
    {
        $this->relationIds = array();

        parent::__construct($connection, $registryType);
    }

    protected function validateRelationIds(array $ids)
    {
        foreach ($ids as $key => $id) {
            $this->validateKey($key);
        }
    }

    protected function copyRelationIdsFromItems() {
        foreach ($this->relationIds as $key => $id) {
            $this->relationIds[$key] = $this->items[$key]->getValue();
        }
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
                throw new \Exception("El ID '$key' debe ser número entero.");
            }
            else if ($id == 0) {
                throw new \Exception("El ID '$key' debe ser diferente de cero.");
            }
        }
    }

    /* Sets relation IDs.
     * Param $ids: associative array of relation IDs in the key=id format.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function setRelationIds(array $ids)
    {
        $this->validateRelationIds($ids);

        foreach ($ids as $key => $id) {
            $this->relationIds[$key] = $id;

            $this->isRegistryModified = true;
        }
    }

    /* Gets relation IDs.
     * Returns: associative array of relation IDs in the key=id form.
     * Throws: Exception if something fails.
     */
    public function getRelationIds(): array
    {
        $ids = array();

        foreach ($this->relationIds as $key => $id) {
            $data[$key] = $item->getValue();
        }

        return $data;
    }

    /* Gets relation ID.
     * Param $key: key of required ID.
     * Returns: required ID.
     * Throws: Exception if something fails.
     */
    public function getRelationId($key): int
    {
        $this->validateKey($key);
        return $this->relationIds[$key];
    }

    public function getRegistryId(): int
    {
        throw new \Exception("La relación no tiene ID, sino un conjunto de IDs.");
    }

    public function read(FUserSession $session, int $id, int $mode)
    {
        throw new \Exception("Método no disponible para relaciones.");
    }

    /* Similar to base method read(), but for relations.
     * Param $session: user session.
     * Param $ids: associative array of relation IDs in the key=id format.
     * Param $mode: read mode. Options declared in class Fraphe\Model\FRegistry.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    abstract public function retrieve(FUserSession $session, array $ids, int $mode);
}
