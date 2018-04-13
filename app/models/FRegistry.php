<?php
require_once "../../app/session/FSession.php";

namespace Fraphe\Models;

abstract class FRegistry
{
    protected $session;
    protected $registryType;
    protected $registryKey;
    protected $isRegistryNew;
    protected $isRegistryModified;

    public function __construct($session, $registryType)
    {
        $this->session = $session;
        $this->registryType = $registryType;
        $this->registryKey = null;
        $this->$isRegistryNew = false;
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
    abstract public function setData($array);

    /* Gets registry data.
     * Returns: associative array of registry data in the attribute=value form.
     * Throws: Exception if something fails.
     */
    abstract public function setData($array);
}
