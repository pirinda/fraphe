<?php
namespace Fraphe\Model;

use Fraphe\Lib\FUtils;

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

    public const INPUT_TEXT = 11;
    public const INPUT_NUMBER = 21;
    public const INPUT_RADIO = 31;
    public const INPUT_CHECKBOX = 32;
    public const INPUT_DATE = 41;
    public const INPUT_DATETIME = 42;
    public const INPUT_TIME = 43;
    public const SELECT = 51;
    public const TEXTAREA = 61;

    protected $dataType;
    protected $key;
    protected $name;
    protected $hint;
    protected $value;
    protected $default;
    protected $canBeNull;
    protected $canBeEmpty;
    protected $isPk;
    protected $valueMin;
    protected $valueMax;
    protected $lengthMin;
    protected $lengthMax;
    protected $guiReadOnly;
    protected $guiEvents;

    function __construct(int $dataType, string $key, string $name, string $hint, bool $mandatory, bool $isPk = false)
    {
        $this->dataType = $dataType;
        $this->key = $key;
        $this->name = $name;
        $this->hint = $hint;
        $this->value = self::produceDefault($dataType);
        $this->default = $this->value;
        $this->canBeNull = !$mandatory;
        $this->canBeEmpty = !$mandatory;
        $this->isPk = $isPk;
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
                $default = 0; // a timestamp
                break;

            default:
                throw new \Exception(__METHOD__ . ": Tipo de dato desconocido: $datatype.");
        }

        return $default;
    }

    public function setValue($value)
    {
        switch ($this->dataType) {
            case self::DATA_TYPE_BOOL:
                $this->value = boolval($value);
                break;

            case self::DATA_TYPE_INT:
                $this->value = intval($value);
                break;

            case self::DATA_TYPE_FLOAT:
                $this->value = floatval($value);
                break;

            case self::DATA_TYPE_STRING:
                $this->value = strval($value);
                break;

            case self::DATA_TYPE_DATE:
            case self::DATA_TYPE_DATETIME:
            case self::DATA_TYPE_TIME:
            case self::DATA_TYPE_TIMESTAMP:
                if (!isset($value)) {
                    // null
                    $this->value = 0;
                }
                else if (is_int($value)) {
                    // already a timestamp
                    $this->value = $value;
                }
                else if (is_string($value)) {
                    switch ($this->dataType) {
                        case self::DATA_TYPE_DATE:
                            // parse timestamp from string value in format "yyyy-mm-dd"
                            $this->value = FUtils::parseStdDate($value);
                            break;
                        case self::DATA_TYPE_DATETIME:
                            // parse timestamp from string value in format "yyyy-mm-dd hh:mm:ss"
                            $this->value = FUtils::parseStdDatetime($value);
                            break;
                        case self::DATA_TYPE_TIME:
                            // parse timestamp from string value in format "hh:mm:ss"
                            $this->value = FUtils::parseStdTime($value);
                            break;
                        case self::DATA_TYPE_TIMESTAMP:
                            // parse timestamp from string value in format "yyyy-mm-dd hh:mm:ss"
                            $this->value = FUtils::parseStdTimestamp($value);
                            break;
                        default:
                    }
                }
                else if (is_object($value)) {
                    throw new \Exception(__METHOD__ . ": Tipo de objeto fecha-hora no soportado: " . get_class($value) . ".");
                }
                else {
                    throw new \Exception(__METHOD__ . ": Tipo de dato fecha-hora no soportado: " . gettype($value) . ".");
                }
                break;

            default:
                throw new \Exception(__METHOD__ . ": Tipo de dato desconocido: $this->dataType.");
        }
    }

    public function setValueMin(int $value)
    {
        $this->valueMin = $value;
    }

    public function setValueMax(int $value)
    {
        $this->valueMax = $value;
    }

    public function setLengthMin(int $length)
    {
        $this->lengthMin = $length;
    }

    public function setLengthMax(int $length)
    {
        $this->lengthMax = $length;
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

    function setMandatory(bool $mandatory)
    {
        $this->canBeNull = !$mandatory;
        $this->canBeEmpty = !$mandatory;
    }

    public function setGuiReadOnly(bool $guiReadOnly)
    {
        $this->guiReadOnly = $guiReadOnly;
    }

    public function setGuiEvents(string $guiEvents)
    {
        $this->guiEvents = $guiEvents;
    }

    public function getDataType(): int
    {
        return $this->dataType;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHint(): string
    {
        return $this->hint;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function canBeNull(): bool
    {
        return $this->canBeNull;
    }

    public function canBeEmpty(): bool
    {
        return $this->canBeEmpty;
    }

    public function isPk(): bool
    {
        return $this->isPk;
    }

    public function getValueMin()
    {
        return $this->valueMin;
    }

    public function getValueMax()
    {
        return $this->valueMax;
    }

    public function getLengthMin(): int
    {
        return $this->lengthMin;
    }

    public function getLengthMax(): int
    {
        return $this->lengthMax;
    }

    public function isGuiReadOnly(): bool
    {
        return $this->guiReadOnly;
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
                throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "no puede ser nulo.");
            }
        }
        else {
            switch ($this->dataType) {
                case self::DATA_TYPE_BOOL:
                    if (!is_bool($this->value)) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "debe ser booleano.");
                    }
                    break;

                case self::DATA_TYPE_INT:
                    if (!is_int($this->value)) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "debe ser número entero.");
                    }
                    else if (!$this->canBeEmpty && empty($this->value)) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "no puede ser cero.");
                    }
                    else if (isset($this->valueMin) && is_int($this->valueMin) && $this->value < $this->valueMin) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "no puede ser menor a $this->valueMin.");
                    }
                    else if (isset($this->valueMax) && is_int($this->valueMax) && $this->value > $this->valueMax) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "no puede ser mayor a $this->valueMax.");
                    }
                    break;

                case self::DATA_TYPE_FLOAT:
                    if (!is_float($this->value)) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "debe ser número decimal.");
                    }
                    else if (!$this->canBeEmpty && empty($this->value)) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "no puede ser cero.");
                    }
                    else if (isset($this->valueMin) && is_float($this->valueMin) && $this->value < $this->valueMin) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "no puede ser menor a $this->valueMin.");
                    }
                    else if (isset($this->valueMax) && is_float($this->valueMax) && $this->value > $this->valueMax) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "no puede ser mayor a $this->valueMax.");
                    }
                    break;

                case self::DATA_TYPE_STRING:
                    if (!is_string($this->value)) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "debe ser texto.");
                    }
                    else if (!$this->canBeEmpty && empty($this->value)) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "no puede estar vacío.");
                    }
                    else if (isset($this->lengthMin) && is_int($this->lengthMin) && strlen($this->value) < $this->lengthMin) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "no puede tener longitud menor a $this->lengthMin.");
                    }
                    else if (isset($this->lengthMax) && is_int($this->lengthMax) && strlen($this->value) > $this->lengthMax) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "no puede tener longitud mayor a $this->lengthMax.");
                    }
                    break;

                case self::DATA_TYPE_DATE:
                case self::DATA_TYPE_DATETIME:
                case self::DATA_TYPE_TIME:
                case self::DATA_TYPE_TIMESTAMP:
                    if (!is_int($this->value)) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "debe ser número entero (timestamp).");
                    }
                    else if (!$this->canBeEmpty && empty($this->value)) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "no puede ser cero (timestamp).");
                    }
                    else if (isset($this->valueMin) && is_int($this->valueMin) && $this->value < $this->valueMin) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "no puede ser menor a " . date("Y-m-d H:i:s", $this->valueMin) . " (timestamp: $this->valueMin).");
                    }
                    else if (isset($this->valueMax) && is_int($this->valueMax) && $this->value > $this->valueMax) {
                        throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "no puede ser mayor a " . date("Y-m-d H:i:s", $this->valueMax) . " (timestamp: $this->valueMax).");
                    }
                    break;

                default:
                    throw new \Exception(__METHOD__ . ": " . "Tipo de dato desconocido.");
            }
        }
    }

    public function composeHtmlInput(int $type, int $lengthLabel, int $lengthInput, string $prefix = ""): string
    {
        $html = '';
        $key = $prefix . $this->key;
        $required = ($this->canBeEmpty ? '' : ' required');

        switch ($type) {
            case self::INPUT_TEXT:
                $placeholder = ' placeholder="' . $this->name . (empty($this->hint) ? '' : ' (' . $this->hint . ')') . '"';
                $maxLength = ' maxlength="' . $this->lengthMax . '"';

                $html .= '<div class="form-group">';
                $html .= '<div class="col-sm-' . $lengthLabel . '">';
                $html .= '<label class="control-label small" for="' . $key . '">' . $this->name . ':' . ($this->canBeEmpty ? '' : ' *') . '</label>';
                $html .= '</div>';
                $html .= '<div class="col-sm-' . $lengthInput . '">';
                $html .= '<input type="text" class="form-control input-sm" name="' . $key . '" id="' . $key . '" value="' . $this->value . '"' . $required . $placeholder . $maxLength .
                    ($this->guiReadOnly ? ' readonly' : '') .
                    (!empty($this->guiEvents) ? ' ' . $this->guiEvents : '') . '>';
                $html .= '</div>';
                $html .= '</div>';
                break;

            case self::INPUT_NUMBER:
                $readonly = ($this->isPk ? ' readonly' : '');
                $placeholder = ' placeholder="' . $this->name . (empty($this->hint) ? '' : ' (' . $this->hint . ')') . '"';
                $min = ' min="' . $this->valueMin . '"';
                $max = $this->valueMin == $this->valueMax ? '' : ' max="' . $this->valueMax . '"';

                $html .= '<div class="form-group">';
                $html .= '<div class="col-sm-' . $lengthLabel . '">';
                $html .= '<label class="control-label small" for="' . ($this->isPk ? FRegistry::ID : $key) . '">' . $this->name . ':' . ($this->canBeEmpty ? '' : ' *') . '</label>';
                $html .= '</div>';
                $html .= '<div class="col-sm-' . $lengthInput . '">';
                $html .= '<input type="number" class="form-control input-sm" name="' . ($this->isPk ? FRegistry::ID : $key) . '" id="' . ($this->isPk ? FRegistry::ID : $key) . '" value="' . $this->value . '"' . $required . $readonly . $placeholder . $min . $max .
                    ($this->guiReadOnly ? ' readonly' : '') .
                    (!empty($this->guiEvents) ? ' ' . $this->guiEvents : '') . '>';
                $html .= '</div>';
                $html .= '</div>';
                break;

            case self::INPUT_RADIO:
                break;

            case self::INPUT_CHECKBOX:
                $html .= '<div class="form-group">';
                $html .= '<div class="col-sm-offset-' . $lengthLabel . ' col-sm-' . $lengthInput . '">';
                $html .= '<div class="checkbox">';
                $html .= '<label class="small"><input type="checkbox" name="' . $key . '" id="' . $key . '" value="1"' . ($this->value ? ' checked' : '') .
                    ($this->guiReadOnly ? ' readonly' : '') .
                    (!empty($this->guiEvents) ? ' ' . $this->guiEvents : '') .
                    '>' . $this->name . '</label>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                break;

            case self::INPUT_DATE:
                $placeholder = ' placeholder="' . $this->name . (empty($this->hint) ? '' : ' (' . $this->hint . ')') . '"';

                $html .= '<div class="form-group">';
                $html .= '<div class="col-sm-' . $lengthLabel . '">';
                $html .= '<label class="control-label small" for="' . $key . '">' . $this->name . ':' . ($this->canBeEmpty ? '' : ' *') . '</label>';
                $html .= '</div>';
                $html .= '<div class="col-sm-' . $lengthInput . '">';
                $html .= '<input type="date" class="form-control input-sm" name="' . $key . '" id="' . $key . '" value="' . (empty($this->value) ? '' : FUtils::formatStdDate($this->value)) . '"' . $required . $placeholder .
                    ($this->guiReadOnly ? ' readonly' : '') .
                    (!empty($this->guiEvents) ? ' ' . $this->guiEvents : '') . '>';
                $html .= '</div>';
                $html .= '</div>';
                break;

            case self::INPUT_DATETIME:
                $placeholder = ' placeholder="' . $this->name . (empty($this->hint) ? '' : ' (' . $this->hint . ')') . '"';

                $html .= '<div class="form-group">';
                $html .= '<div class="col-sm-' . $lengthLabel . '">';
                $html .= '<label class="control-label small" for="' . $key . '">' . $this->name . ':' . ($this->canBeEmpty ? '' : ' *') . '</label>';
                $html .= '</div>';
                $html .= '<div class="col-sm-' . $lengthInput . '">';
                $html .= '<input type="datetime-local" class="form-control input-sm" name="' . $key . '" id="' . $key . '" value="' . (empty($this->value) ? '' : FUtils::formatHtmlDatetime($this->value)) . '"' . $required . $placeholder .
                    ($this->guiReadOnly ? ' readonly' : '') .
                    (!empty($this->guiEvents) ? ' ' . $this->guiEvents : '') . '>';
                $html .= '</div>';
                $html .= '</div>';
                break;

            case self::INPUT_TIME:
                throw new \Exception(__METHOD__ . ": " . $this->composeItemName() . "tiene un tipo de dato no soportado aún.");
                break;  // useless

            default:
                throw new \Exception(__METHOD__ . ": " . "Tipo de element desconocido.");
        }

        return $html;
    }

    public function composeHtmlTextArea(int $lengthLabel, int $lengthInput, int $rows, string $prefix = ""): string
    {
        $html = '';
        $key = $prefix . $this->key;
        $required = ($this->canBeEmpty ? '' : ' required');

        $placeholder = ' placeholder="' . $this->name . (empty($this->hint) ? '' : ' (' . $this->hint . ')') . '"';
        $maxLength = ' maxlength="' . $this->lengthMax . '"';

        $html .= '<div class="form-group">';
        $html .= '<div class="col-sm-' . $lengthLabel . '">';
        $html .= '<label class="control-label small" for="' . $key . '">' . $this->name . ':' . ($this->canBeEmpty ? '' : ' *') . '</label>';
        $html .= '</div>';
        $html .= '<div class="col-sm-' . $lengthInput . '">';
        $html .= '<textarea class="form-control input-sm" name="' . $key . '" id="' . $key . '" rows="' . $rows . '"' . $required . $placeholder . $maxLength .
            ($this->guiReadOnly ? ' readonly' : '') .
            (!empty($this->guiEvents) ? ' ' . $this->guiEvents : '') .
            '>' . $this->value . '</textarea>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public function composeHtmlSelect(array $options, int $lengthLabel, int $lengthInput, string $prefix = ""): string
    {
        $html = '';
        $key = $prefix . $this->key;
        $required = ($this->canBeEmpty ? '' : ' required');

        $html .= '<div class="form-group">';
        $html .= '<div class="col-sm-' . $lengthLabel . '">';
        $html .= '<label class="control-label small" for="' . $key . '">' . $this->name . ':' . ($this->canBeEmpty ? '' : ' *') . '</label>';
        $html .= '</div>';
        $html .= '<div class="col-sm-' . $lengthInput . '">';
        $html .= '<select class="form-control input-sm" name="' . $key . '" id="' . $key . '"' .
            ($this->guiReadOnly ? ' readonly' : '') .
            (!empty($this->guiEvents) ? ' ' . $this->guiEvents : '') . '>';
        foreach ($options as $option) {
            $html .= $option;
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
