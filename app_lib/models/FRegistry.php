<?php
namespace Fraphe\Models;

require_once "../../app/session/FSession.php";

use Fraphe\Session\FSession;

abstract class FRegistry
{
    public const READ = 1;
    public const WRITE = 2;

    protected $session;
    protected $registryType;
    protected $registryId;
    protected $isRegistryNew;
    protected $isRegistryModified;

    public function __construct(FSession $session, int $registryType)
    {
        $this->session = $session;
        $this->registryType = $registryType;
        $this->registryId = 0;
        $this->$isRegistryNew = true;
        $this->$isRegistryModified = false;
    }

    /* Initializes registry data.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    abstract public function initializeData();

    /* Validates registry data.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    abstract public function validateData();

    /* Sets registry data.
     * Param $array: associative array of registry data in the attribute=value form.
     * Returns: nothing.
     * Throws: Exception if something fails.
     */
    abstract public function setData(array $array);

    /* Gets registry data.
     * Returns: associative array of registry data in the attribute=value form.
     * Throws: Exception if something fails.
     */
    abstract public function getData(): array;

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

    public function lock()
    {

    }

    public function relock()
    {

    }

    public function unlock()
    {

    }

    public function checkLock()
    {

    }

    abstract public function read(int $id, int $case);

    public function canSave(): bool
    {
        return true;
    }

    abstract public function save();

    public function canDelete(): bool
    {
        return true;
    }

    abstract public function delete();

    public function canUndelete(): bool
    {
        return true;
    }

    abstract public function undelete();
}
