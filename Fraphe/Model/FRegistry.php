<?php
namespace Fraphe\Model;

use Fraphe\App\FUserSession;

abstract class FRegistry
{
    public const MODE_READ = 1;
    public const MODE_WRITE = 2;
    public const ID = "id";
    public const ERR_MSG_REGISTRY_NOT_FOUND = "El registro no fue encontrado.";
    public const ERR_MSG_REGISTRY_DEP_NOT_FOUND = "El registro dependiente no fue encontrado.";
    public const ERR_MSG_REGISTRY_NON_UPDATABLE = "El registro no se puede modificar.";
    public const ERR_MSG_REGISTRY_NON_DELETABLE = "El registro no se puede eliminar.";

    protected $registryType;
    protected $tableName;
    protected $idName;
    protected $items;   // associative array of FItem objects

    protected $id;
    protected $isRegistryNew;
    protected $isRegistryModified;
    protected $mode;
    protected $lock;

    /* Creates new base registry. Each field of registry must be contained in member array $items.
     */
    public function __construct(int $registryType, string $tableName, string $idName)
    {
        $this->registryType = $registryType;
        $this->tableName = $tableName;
        $this->idName = $idName;
        $this->items = array();

        $this->initialize();
    }

    /* Initializes registry data.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function initialize()
    {
        $this->id = 0;
        $this->isRegistryNew = true;
        $this->isRegistryModified = false;
        $this->mode = 0;
        $this->lock = null;

        foreach ($this->items as $item) {
            $item->reset();
        }
    }

    /* Tailors registry data items to full fill specific needs, according to its current state and values.
     * Returns: nothing.
     * Throws: Exception if expected data are not available.
     */
     public function tailorMembers()
     {

     }

    /* Validates registry data.
     * Must be called at the begining of method save().
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function validate(FUserSession $userSession)
    {
        $this->tailorMembers(); // tailor registry members according to current data status and values

        foreach ($this->items as $item) {
            $item->validate();
        }
    }

    protected function validateItemKey($key)
    {
        if (empty($key)) {
            throw new \Exception(__METHOD__ . ": La clave está vacía.");
        }
        else if (!is_string($key)) {
            throw new \Exception(__METHOD__ . ": La clave '$key' debe ser texto.");
        }
        else if (!array_key_exists($key, $this->items)) {
            throw new \Exception(__METHOD__ . ": La clave '$key' no existe.");
        }
    }

    /* Forces member flag as if this registry were new.
     */
    public function forceRegistryNew()
    {
        //$this->id = 0;
        $this->isRegistryNew = true;
    }

    /* Sets registry data.
     * Param $array: associative array of registry data in the key=value format. Special case: key="id", that is used for setting ID of this registry.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function setData(array $data)
    {
        // validate keys:
        foreach ($data as $key => $value) {
            $this->validateItemKey($key);
        }

        // set data:
        foreach ($data as $key => $value) {
            if ($key == $this->idName) {
                if (!is_int($value)) {
                    throw new \Exception(__METHOD__ . ": El dato '$this->idName' debe ser número entero.");
                }
                $this->id = intval($value);
                $this->isRegistryNew = $this->id == 0;
            }

            $this->items[$key]->setValue($value);
        }

        if (count($data) > 0) {
            $this->isRegistryModified = true;
        }

        $this->tailorMembers(); // tailor registry members according to current data status and values
    }

    /* Gets registry data.
     * Returns: associative array of registry data in the key=value format.
     * Throws: Exception if something fails.
     */
    public function getData(): array
    {
        $data = array();

        foreach ($this->items as $key => $item) {
            $data[$key] = $item->getValue();
        }

        return $data;
    }

    /* Gets registry datum.
     * Param $key: key of required datum.
     * Returns: required data.
     * Throws: Exception if something fails.
     */
    public function getDatum($key)
    {
        $this->validateItemKey($key);
        return $this->items[$key]->getValue();
    }

    /* Gets registry item.
     * Param $key: key of required item.
     * Returns: required item.
     * Throws: Exception if something fails.
     */
    public function getItem($key): FItem
    {
        $this->validateItemKey($key);
        return $this->items[$key];
    }

    public function getRegistryType(): int
    {
        return $this->registryType;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getIdName(): string
    {
        return $this->idName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isRegistryNew(): bool
    {
        return $this->isRegistryNew;
    }

    public function isRegistryModified(): bool
    {
        return $this->isRegistryModified;
    }

    public function getMode(): int
    {
        return $this->mode;
    }

    public function lock()
    {

    }

    public function unlock()
    {

    }

    private function isLocked(): bool
    {

    }

    public function checkLock()
    {

    }

    abstract public function read(FUserSession $userSession, int $id, int $mode);

    public function canSave(): bool
    {
        return true;
    }

    abstract public function save(FUserSession $userSession);

    public function canDelete(): bool
    {
        return true;
    }

    abstract public function delete(FUserSession $userSession);

    public function canUndelete(): bool
    {
        return true;
    }

    abstract public function undelete(FUserSession $userSession);

    public function resetAutoIncrement(FUserSession $userSession)
    {
        $sql = "ALTER TABLE $this->tableName AUTO_INCREMENT = 1;";
        $userSession->getPdo()->exec($sql);
    }
}
