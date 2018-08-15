<?php
namespace app;

use Fraphe\App\FUserSession;
use Fraphe\Model\FRegistry;

abstract class AppUtils
{
    public static function getSelectOptions(FUserSession $userSession, int $catalog, int $selectedId, array $params = null): array
    {
        $sql;
        $options = array();
        $table = AppConsts::$tables[$catalog];
        $tableId = AppConsts::$tableIds[$catalog];

        switch ($catalog) {
            // "name" sorted by ID + name:
            case AppConsts::CC_MARKET_SEGMENT:
            case AppConsts::CC_ENTITY_CLASS:
            case AppConsts::CC_ENTITY_TYPE:
            case AppConsts::CC_CONTACT_TYPE:
            case AppConsts::OC_PROCESS_AREA:
            case AppConsts::OC_SAMPLE_CLASS:
            case AppConsts::OC_SAMPLE_TYPE:
            case AppConsts::OC_REPORT_DELIVERY_OPT:
                $sql = "SELECT $tableId AS _val, name AS _opt FROM $table WHERE NOT is_deleted ORDER BY $tableId, name;";
                break;

            // "name" sorted by name + ID:
            case AppConsts::OC_SAMPLING_EQUIPT:
            case AppConsts::OC_SAMPLING_METHOD:
            case AppConsts::OC_TESTING_METHOD:
            case AppConsts::OC_CONTAINER_TYPE:
                $sql = "SELECT $tableId AS _val, name AS _opt FROM $table WHERE NOT is_deleted ORDER BY name, $tableId;";
                break;

            // "name" sorted by code + ID:
            case AppConsts::OC_CONTAINER_UNIT:
                $sql = "SELECT $tableId AS _val, code AS _opt FROM $table WHERE NOT is_deleted ORDER BY code, $tableId;";
                break;

            // "code - name" sorted by ID + name:
            case AppConsts::OC_TEST_ACREDIT_ATTRIB:
                $sql = "SELECT $tableId AS _val, CONCAT(code, ' - ', name) AS _opt FROM $table WHERE NOT is_deleted ORDER BY $tableId, name;";
                break;

            // "name (code)" sorted by name + ID:
            // params expected: fk_entity_class,
            case AppConsts::CC_ENTITY:
                $type;
                if (isset($params["entity_type"])) {
                    $type = $params["entity_type"];
                }

                $sql = "SELECT e.$tableId AS _val, CONCAT(e.name, ' (', e.code, ')') AS _opt ";
                $sql .= "FROM $table AS e ";

                if (isset($type)) {
                    $sql .= "INNER JOIN " . $table = AppConsts::$tables[AppConsts::CC_ENTITY_ENTITY_TYPE] . " AS et ON et.id_entity = e.id_entity AND et.id_entity_type = " . $type . " ";
                }

                $sql .= "WHERE NOT e.is_deleted AND e.fk_entity_class = " . $params["fk_entity_class"] . " ORDER BY e.name, e.code, e.$tableId;";
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
