<?php
namespace Fraphe\Model;

use Fraphe\App\FUserSession;

abstract class FRegistry
{
    public const MODE_READ = 1;
    public const MODE_WRITE = 2;

    protected $connection;
    protected $registryType;
    protected $registryId;
    protected $isRegistryNew;
    protected $isRegistryModified;
    protected $mode;
    protected $lock;
    protected $items;

    public function __construct(\PDO $connection, int $registryType)
    {
        $this->connection = $connection;
        $this->registryType = $registryType;
        $this->items = array();

        $this->initialize();
    }

    private function validateKey($key) {
        if (empty($key)) {
            throw new Exception("La clave está vacía.");
        }
        else if (!is_string($key)) {
            throw new Exception("La clave '$key' debe ser texto.");
        }
        else if (!array_key_exists($key, $this->items)) {
            throw new Exception("La clave '$key' no existe.");
        }
    }

    /* Initializes registry data.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function initialize()
    {
        $this->registryId = 0;
        $this->isRegistryNew = true;
        $this->isRegistryModified = false;
        $this->mode = 0;
        $this->lock = null;

        foreach ($this->items as $item) {
            $item->reset();
        }
    }

    /* Validates registry data.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function validate()
    {
        foreach ($this->items as $item) {
            $item->validate();
        }
    }

    /* Sets registry data.
     * Param $array: associative array of registry data in the attribute=value form.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    public function setData(array $data)
    {
        foreach ($data as $key => $val) {
            if ($key === "id") {
                $this->registryId = $val;
                $this->isRegistryNew = false;
            }
            else {
                $this->validateKey($key);
                $this->items[$key]->setValue($val);
            }
            $this->isRegistryModified = true;
        }
    }

    /* Gets registry data.
     * Returns: associative array of registry data in the attribute=value form.
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
     * Returns: required data.
     * Throws: Exception if something fails.
     */
    public function getDatum($key)
    {
        $this->validateKey($key);
        return $this->items[$key]->getValue();
    }

    /* Gets registry item.
     * Returns: required item.
     * Throws: Exception if something fails.
     */
    public function getItem($key)
    {
        $this->validateKey($key);
        return $this->items[$key];
    }

    public function getRegistryType(): int
    {
        return $this->registryType;
    }

    public function getRegistryId(): int
    {
        return $this->registryId;
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

    abstract public function read(FUserSession $session, int $id, int $mode);

    public function canSave(): bool
    {
        return true;
    }

    abstract public function save(FUserSession $session);

    public function canDelete(): bool
    {
        return true;
    }

    abstract public function delete(FUserSession $session);

    public function canUndelete(): bool
    {
        return true;
    }

    abstract public function undelete(FUserSession $session);
}
