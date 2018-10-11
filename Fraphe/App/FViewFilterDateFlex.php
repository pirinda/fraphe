<?php
namespace Fraphe\App;

use Fraphe\Lib\FLibUtils;

class FViewFilterDateFlex
{
    public const TYPE_DAY = 1;
    public const TYPE_MON = 2;
    public const TYPE_YEAR = 3;
    public const DATE_TYPE = "dateTp";
    public const DATE_TIMESTAMP = "dateTs";

    private $dbColumn;
    private $type;
    private $timestamp;

    public function __construct(string $dbColumn, int $iniType, int $iniTimestamp)
    {
        $this->dbColumn = $dbColumn;
        $this->type = $iniType;
        $this->timestamp = $iniTimestamp;
    }

    private function composeRequestUri(string $requestUri, int $type, int $timestamp): string
    {
        $string = str_replace(self::DATE_TYPE . "=" . $this->type, self::DATE_TYPE . "=" . $type, $requestUri);
        $string = str_replace(self::DATE_TIMESTAMP . "=" . $this->timestamp, self::DATE_TIMESTAMP . "=" . $timestamp, $string);
        return $string;
    }

    private function composeDate(): string
    {
        $date = "";

        switch ($this->type) {
            case self::TYPE_DAY:
                $date = date("d/m/Y", $this->timestamp);
                break;
            case self::TYPE_MON:
                $date = date("m/Y", $this->timestamp);
                break;
            case self::TYPE_YEAR:
                $date = date("Y", $this->timestamp);
                break;
            default:
        }

        return $date;
    }

    private function dateAdd(): int
    {
        $datetime = new \DateTime();
        $datetime->setTimestamp($this->timestamp);

        switch ($this->type) {
            case self::TYPE_DAY:
                $datetime->add(new \DateInterval("P1D"));
                break;

            case self::TYPE_MON:
                $datetime->add(new \DateInterval("P1M"));
                break;

            case self::TYPE_YEAR:
                $datetime->add(new \DateInterval("P1Y"));
                break;

            default:
        }

        return $datetime->getTimestamp();
    }

    private function dateSub(): int
    {
        $datetime = new \DateTime();
        $datetime->setTimestamp($this->timestamp);

        switch ($this->type) {
            case self::TYPE_DAY:
                $datetime->sub(new \DateInterval("P1D"));
                break;

            case self::TYPE_MON:
                $datetime->sub(new \DateInterval("P1M"));
                break;

            case self::TYPE_YEAR:
                $datetime->sub(new \DateInterval("P1Y"));
                break;

            default:
        }

        return $datetime->getTimestamp();
    }

    public function composeFilterGui(): string
    {
        // get current type and timestamp:

        if (!empty($_GET[self::DATE_TYPE])) {
            $this->type = intval($_GET[self::DATE_TYPE]);
        }

        if (!empty($_GET[self::DATE_TIMESTAMP])) {
            $this->timestamp = intval($_GET[self::DATE_TIMESTAMP]);
        }

        // compose current URL query:

        $query_string = $_SERVER['QUERY_STRING'];
        $request_uri = $_SERVER['REQUEST_URI'];

        if (strpos($query_string, self::DATE_TYPE . "=") === false) {
            $request_uri .= (empty($query_string) ? "?" : "&") . self::DATE_TYPE . "=" . $this->type;
        }

        if (strpos($query_string, self::DATE_TIMESTAMP . "=") === false) {
            $request_uri .= (empty($query_string) ? "?" : "&") . self::DATE_TIMESTAMP . "=" . $this->timestamp;
        }

        // render filter:

        $html = '<div class="btn-group">';
        $html .= '<button type="button" class="btn btn-primary btn-sm" onclick="location.href=\'' . $this->composeRequestUri($request_uri, self::TYPE_DAY, time()) . '\';">hoy</button>';
        $html .= '<button type="button" class="btn btn-primary btn-sm" onclick="location.href=\'' . $this->composeRequestUri($request_uri, self::TYPE_MON, time()) . '\';">mes</button>';
        $html .= '<button type="button" class="btn btn-primary btn-sm" onclick="location.href=\'' . $this->composeRequestUri($request_uri, self::TYPE_YEAR, time()) . '\';">año</button>';
        $html .= '<div class="btn-group">';
        $html .= '<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">';
        $html .= $this->composeDate() . '&nbsp;<span class="caret"></span></button>';
        $html .= '<ul class="dropdown-menu" role="menu">';
        $html .= '<li><a href="' . $this->composeRequestUri($request_uri, self::TYPE_DAY, $this->timestamp) . '">ver día</a></li>';
        $html .= '<li><a href="' . $this->composeRequestUri($request_uri, self::TYPE_MON, $this->timestamp) . '">ver mes</a></li>';
        $html .= '<li><a href="' . $this->composeRequestUri($request_uri, self::TYPE_YEAR, $this->timestamp) . '">ver año</a></li>';
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '<button type="button" class="btn btn-primary btn-sm" onclick="location.href=\'' . $this->composeRequestUri($request_uri, $this->type, $this->dateSub()) . '\';"><span class="glyphicon glyphicon-menu-left"></span></button>';
        $html .= '<button type="button" class="btn btn-primary btn-sm" onclick="location.href=\'' . $this->composeRequestUri($request_uri, $this->type, $this->dateAdd()) . '\';"><span class="glyphicon glyphicon-menu-right"></span></button>';
        $html .= '</div>';

        return $html;
    }

    public function composeFilterSql(): string
    {
        $sql = "";

        switch ($this->type) {
            case self::TYPE_DAY:
                $sql = "DATE($this->dbColumn) = '" . FLibUtils::formatStdDate($this->timestamp) . "' ";
                break;
            case self::TYPE_MON:
                $sql = "(YEAR($this->dbColumn) = " . date("Y", $this->timestamp) . " AND MONTH($this->dbColumn) = " . date("m", $this->timestamp) . ") ";
                break;
            case self::TYPE_YEAR:
                $sql = "YEAR($this->dbColumn) = " . date("Y", $this->timestamp) . " ";
                break;
            default:
        }

        return $sql;
    }
}
