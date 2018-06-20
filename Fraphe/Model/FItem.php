<?php
namespace Fraphe\Model;

class FItem
{
    public const DATA_TYPE_BOOL = 1;
    public const DATA_TYPE_INT = 2;
    public const DATA_TYPE_FLOAT = 3;
    public const DATA_TYPE_STRING = 4;
    public const DATA_TYPE_DATE = 11;
    public const DATA_TYPE_DATETIME = 12;
    public const DATA_TYPE_TIME = 13;
    public const DATA_TYPE_TIMESTAMP = 15;

    protected $dataType;
    protected $key;
    protected $name;
    protected $value;
    protected $default;
    protected $canBeNull;
    protected $canBeEmpty;
    protected $description;
    protected $valueMin;
    protected $valueMax;
    protected $lengthMin;
    protected $lengthMax;

    function __construct(int $dataType, string $key, string $name, bool $optional)
    {
        $this->dataType = $dataType;
        $this->key = $key;
        $this->name = $name;
        $this->value = self::produceDefault($dataType);
        $this->default = $this->value;
        $this->canBeNull = $optional;
        $this->canBeEmpty = $optional;
        $this->description = "";
    }

    public static function produceDefault(int $dataType)
    {
        $default;

        switch ($dataType) {
            case self::DATA_TYPE_BOOL:
                $default = false;
                break;

            case self::DATA_TYPE_INT:
                $default = 0;
                break;

            case self::DATA_TYPE_FLOAT:
                $default = 0.0;
                break;

            case self::DATA_TYPE_STRING:
                $default = "";
                break;

            case self::DATA_TYPE_DATE:
            case self::DATA_TYPE_DATETIME:
            case self::DATA_TYPE_TIME:
            case self::DATA_TYPE_TIMESTAMP:
                $default = null;
                break;

            default:
                throw new \Exception("Tipo de dato desconocido.");
        }

        return $default;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setRangeValue(int $valueMin, int $valueMax)
    {
        $this->valueMin = $valueMin;
        $this->valueMax = $valueMax;
    }

    public function setRangeLength(int $lengthMin, int $lengthMax)
    {
        $this->lengthMin = $lengthMin;
        $this->lengthMax = $lengthMax;
    }

    public function getDataType()
    {
        return $this->dataType;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function canBeNull()
    {
        return $this->canBeNull;
    }

    public function canBeEmpty()
    {
        return $this->canBeEmpty;
    }

    public function getdescription()
    {
        return $this->description;
    }

    public function getValueMin()
    {
        return $this->valueMin;
    }

    public function getValueMax()
    {
        return $this->valueMax;
    }

    public function getLengthMin()
    {
        return $this->lengthMin;
    }

    public function getLengthMax()
    {
        return $this->lengthMax;
    }

    public function reset()
    {
        return $this->value = $this->default;
    }

    private function composeItemName(): string
    {
        return "El dato '$this->name' ";
    }

    public function validate()
    {
        if (!isset($this->value)) {
            if (!$this->canBeNull) {
                throw new \Exception(self::composeItemName() . "no puede ser nulo.");
            }
        }
        else {
            switch ($this->dataType) {
                case self::DATA_TYPE_BOOL:
                    if (!is_bool($this->value)) {
                        throw new \Exception(self::composeItemName() . "debe ser booleano.");
                    }
                    break;

                case self::DATA_TYPE_INT:
                    if (!is_int($this->value)) {
                        throw new \Exception(self::composeItemName() . "debe ser número entero.");
                    }
                    else if (!$this->canBeEmpty && empty($this->value)) {
                        throw new \Exception(self::composeItemName() . "no puede ser cero.");
                    }
                    else if (isset($this->valueMin) && is_int($this->valueMin) && $this->value < $this->valueMin) {
                        throw new \Exception(self::composeItemName() . "no puede ser menor a " . $this->valueMin . ".");
                    }
                    else if (isset($this->valueMax) && is_int($this->valueMax) && $this->value > $this->valueMax) {
                        throw new \Exception(self::composeItemName() . "no puede ser mayor a " . $this->valueMax . ".");
                    }
                    break;

                case self::DATA_TYPE_FLOAT:
                    if (!is_float($this->value)) {
                        throw new \Exception(self::composeItemName() . "debe ser número decimal.");
                    }
                    else if (!$this->canBeEmpty && empty($this->value)) {
                        throw new \Exception(self::composeItemName() . "no puede ser cero.");
                    }
                    else if (isset($this->valueMin) && is_float($this->valueMin) && $this->value < $this->valueMin) {
                        throw new \Exception(self::composeItemName() . "no puede ser menor a " . $this->valueMin . ".");
                    }
                    else if (isset($this->valueMax) && is_float($this->valueMax) && $this->value > $this->valueMax) {
                        throw new \Exception(self::composeItemName() . "no puede ser mayor a " . $this->valueMax . ".");
                    }
                    break;

                case self::DATA_TYPE_STRING:
                    if (!is_string($this->value)) {
                        throw new \Exception(self::composeItemName() . "debe ser texto.");
                    }
                    else if (!$this->canBeEmpty && empty($this->value)) {
                        throw new \Exception(self::composeItemName() . "no puede estar vacío.");
                    }
                    else if (isset($this->lengthMin) && is_int($this->lengthMin) && strlen($this->value) < $this->lengthMin) {
                        throw new \Exception(self::composeItemName() . "no puede tener longitud menor a " . $this->lengthMin . ".");
                    }
                    else if (isset($this->lengthMax) && is_int($this->lengthMax) && strlen($this->value) > $this->lengthMax) {
                        throw new \Exception(self::composeItemName() . "no puede tener longitud mayor a " . $this->lengthMax . ".");
                    }
                    break;

                case self::DATA_TYPE_DATE:
                case self::DATA_TYPE_DATETIME:
                case self::DATA_TYPE_TIME:
                case self::DATA_TYPE_TIMESTAMP:
                /*
                    if (!is_a("DateTime")) {
                        throw new \Exception(self::composeItemName() . "debe ser fecha o fecha-hora.");
                    }
                    else if (!$this->canBeEmpty && empty($value)) {
                        throw new \Exception(self::composeItemName() . "no puede estar vacío.");
                    }
                    else if (isset($this->valueMin) && is_int($valueMin) && $this->value < $this->valueMin) {
                        throw new \Exception(self::composeItemName() . "no puede ser anterior a " . $this->valueMin . ".");
                    }
                    else if (isset($this->valueMax) && is_int($valueMax) && $this->value > $this->valueMax) {
                        throw new \Exception(self::composeItemName() . "no puede ser posterior a " . $this->valueMax . ".");
                    }
                    break;
                */
                default:
                    throw new \Exception("Tipo de dato desconocido.");
            }
        }
    }
}
