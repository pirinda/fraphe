<?php
namespace app;

use Fraphe\App\FUserSession;
use Fraphe\Model\FRegistry;

abstract class AppUtils
{
    public static function getSelectOptions(FUserSession $userSession, int $catalog, int $selectedId = 0): array
    {
        $sql;
        $options = array();
        $table = AppConsts::$tables[$catalog];
        $tableId = AppConsts::$tableIds[$catalog];

        switch ($catalog) {
            // "name" sorted by ID + name:
            case AppConsts::CC_MARKET_SEGMENT:
            case AppConsts::OC_PROCESS_AREA:
            case AppConsts::OC_SAMPLE_CATEGORY:
            case AppConsts::OC_SAMPLE_CLASS:
            case AppConsts::OC_SAMPLE_TYPE:
            case AppConsts::OC_REPORT_DELIVERY_OPT:
                $sql = "SELECT $tableId AS _val, name AS _opt FROM $table WHERE NOT is_deleted ORDER BY $tableId, name;";
                break;

            // "name" sorted by name + ID:
            case AppConsts::OC_SAMPLING_METHOD:
            case AppConsts::OC_TESTING_METHOD:
                $sql = "SELECT $tableId AS _val, name AS _opt FROM $table WHERE NOT is_deleted ORDER BY name, $tableId;";
                break;

            // "code - name" sorted by ID + name:
            case AppConsts::OC_TEST_ACREDIT_ATTRIB:
                $sql = "SELECT $tableId AS _val, CONCAT(code, ' - ', name) AS _opt FROM $table WHERE NOT is_deleted ORDER BY $tableId, name;";
                break;

            default:
        }

        $options[] = '<option value="0"' . ($selectedId == 0 ? " selected" : "") . '>- seleccionar -</option>';

        if (isset($sql)) {
            foreach ($userSession->getPdo()->query($sql) as $row) {
                $options[] = '<option value="' . $row['_val'] . '"' . (!empty($selectedId) && $selectedId == intval($row['_val']) ? " selected" : "") . '>' . $row['_opt'] . '</option>';
            }
        }

        return $options;
    }

    public static function getName(FUserSession $userSession, int $catalog, int $id): string
    {
        $name = "";
        $table = AppConsts::$tables[$catalog];
        $tableId = AppConsts::$tableIds[$catalog];
        $sql = "SELECT name FROM $table WHERE $tableId = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $name = $row["name"];
        }
        else {
            throw new \Exception(__METHOD__ . ": " . FRegistry::ERR_MSG_REGISTRY_NOT_FOUND);
        }

        return $name;
    }
}
