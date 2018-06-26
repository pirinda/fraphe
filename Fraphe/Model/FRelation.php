<?php
namespace Fraphe\Model;

use Fraphe\App\FUserSession;

abstract class FRelation
{
    protected $connection;
    protected $registryType;
    protected $ids;     // associative array of integers

    public function __construct(\PDO $connection, int $registryType)
    {
        $this->connection = $connection;
        $this->registryType = $registryType;
        $this->ids = array();

        $this->initialize();
    }

    private function validateKey($key) {
        if (empty($key)) {
            throw new Exception("La clave está vacía.");
        }
        else if (!is_string($key)) {
            throw new Exception("La clave '$key' debe ser texto.");
        }
        else if (!array_key_exists($key, $this->ids)) {
            throw new Exception("La clave '$key' no existe.");
        }
    }

    /* Initializes relation data.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function initialize()
    {
        foreach ($this->ids as $key => $id) {
            $this->ids[$key] = 0;
        }
    }

    /* Validates relation IDs.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function validate()
    {
        foreach ($this->ids as $key => $id) {
            if (empty($id)) {
                throw new \Exception("El ID '$key' no tiene valor.");
            }
        }
    }

    /* Sets relation IDs.
     * Param $array: associative array of registry IDs in the key=id form.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function setIds(array $ids)
    {
        foreach ($ids as $key => $id) {
            $this->validateKey($key);
            $this->ids[$key] = $id;
        }
    }

    /* Gets relation IDs.
     * Returns: associative array of registry IDs in the key=id form.
     * Throws: Exception if something fails.
     */
    public function getIds(): array
    {
        $ids = array();

        foreach ($this->ids as $key => $id) {
            $ids[$key] = $id;
        }

        return $ids;
    }

    /* Gets registry ID.
     * Returns: required ID.
     * Throws: Exception if something fails.
     */
    public function getId($key): int
    {
        $this->validateKey($key);
        return $this->ids[$key];
    }

    public function getRegistryType(): int
    {
        return $this->registryType;
    }

    public function canSave(): bool
    {
        return true;
    }

    abstract public function save(FUserSession $session);
}
