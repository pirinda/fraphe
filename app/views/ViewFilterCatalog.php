<?php
namespace app\views;

use app\AppConsts;
use Fraphe\App\FUserSession;
use Fraphe\Lib\FLibUtils;

class ViewFilterCatalog
{
    private $catalog;
    private $dbColumn;
    private $uriParam;
    private $optionSelected;    // selected catalog ID
    private $optionsAllowed;    // array of integers with catalog IDs
    private $optionsAvailable;  // associative array of catalog IDs (keys) and names (values)

    /**
     * $optionsAllowed: Array of integers with required ID to show. If null then all options are shown and are also elegible to be selected for filtering.
     */
    public function __construct(FUserSession $userSession, int $catalog, string $dbColumn, string $uriParam, array $optionsAllowed = null)
    {
        $this->catalog = $catalog;
        $this->dbColumn = $dbColumn;
        $this->uriParam = $uriParam;
        $this->optionSelected = empty($optionsAllowed) || count($optionsAllowed) > 1 ? 0 : $optionsAllowed[0];
        $this->optionsAllowed = $optionsAllowed;
        $this->createOptions($userSession);
    }

    private function createOptions(FUserSession $userSession)
    {
        $sql;
        $table = AppConsts::$tables[$this->catalog];
        $tableId = AppConsts::$tableIds[$this->catalog];

        switch ($this->catalog) {
            // "name (code)" sorted by sorting + ID:
            case AppConsts::OC_PROCESS_AREA:
                $sql = "SELECT $tableId AS _id, CONCAT(name, ' (', code, ')') AS _opt FROM $table WHERE NOT is_deleted ORDER BY sorting, $tableId;";
                break;

            default:
        }

        $this->optionsAvailable = array();
        foreach ($userSession->getPdo()->query($sql) as $row) {
            $this->optionsAvailable[intval($row['_id'])] = $row['_opt'];
        }
    }

    private function composeRequestUri(string $requestUri, int $optionSelected): string
    {
        $string = str_replace($this->uriParam . "=" . $this->optionSelected, $this->uriParam . "=" . $optionSelected, $requestUri);
        return $string;
    }

    public function composeFilterGui(): string
    {
        // get current option selected:

        if (!empty($_GET[$this->uriParam])) {
            $this->optionSelected = intval($_GET[$this->uriParam]);
        }
        else {
            $this->optionSelected = empty($this->optionsAllowed) || count($this->optionsAllowed) > 1 ? 0 : $this->optionsAllowed[0];
        }

        // compose current URL query:

        $query_string = $_SERVER['QUERY_STRING'];
        $request_uri = $_SERVER['REQUEST_URI'];

        if (strpos($query_string, $this->uriParam . "=") === false) {
            $request_uri .= (empty($query_string) ? "?" : "&") . $this->uriParam . "=" . $this->optionSelected;
        }

        // render filter:
        $count = 0;
        $html = '<div class="btn-group">';
        $html .= '<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">';
        $html .= (empty($this->optionSelected) ? "(todos)" : $this->optionsAvailable[$this->optionSelected]) . '&nbsp;<span class="caret"></span></button>';
        $html .= '<ul class="dropdown-menu" role="menu">';
        if (!empty($this->optionsAllowed)) {
            foreach ($this->optionsAllowed as $id) {
                $count++;
                $html .= '<li><a href="' . $this->composeRequestUri($request_uri, $id) . '">' . $this->optionsAvailable[$id] . '</a></li>';
            }
        }
        else {
            foreach ($this->optionsAvailable as $id => $name) {
                $count++;
                $html .= '<li><a href="' . $this->composeRequestUri($request_uri, $id) . '">' . $name . '</a></li>';
            }
        }
        if ($count > 1) {
            $html .= '<li><a href="' . $this->composeRequestUri($request_uri, 0) . '">(todos)</a></li>';
        }
        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }

    public function composeFilterSql(): string
    {
        $sql = "";

        if (!empty($this->optionSelected)) {
            $sql = "$this->dbColumn = " . $this->optionSelected . " ";
        }
        else if (isset($this->optionsAllowed) && count($this->optionsAllowed) > 0) {
            foreach ($this->optionsAllowed as $id) {
                $sql .= (empty($sql) ? "" : ", ") . $id;
            }
            $sql = "$this->dbColumn IN ($sql) ";
        }

        return $sql;
    }
}
