<?php
namespace app;

use Fraphe\App\FUserSession;
use Fraphe\Model\FRegistry;
use app\models\catalogs\ModEntity;

abstract class AppUtils
{
    public static function composeSelectOption(int $selectedId = 0): string
    {
        return '<option value="0"' . ($selectedId == 0 ? " selected" : "") . '>- seleccionar -</option>';
    }

    public static function getSelectOptions(FUserSession $userSession, int $catalog, int $selectedId, array $params = null): array
    {
        $sql;
        $options = array();
        $table = AppConsts::$tables[$catalog];
        $tableId = AppConsts::$tableIds[$catalog];

        switch ($catalog) {
            // "name" sorted by ID + name:
            case AppConsts::CC_ENTITY_CLASS:
            case AppConsts::CC_ENTITY_TYPE:
            case AppConsts::CC_CONTACT_TYPE:
            case AppConsts::OC_SAMPLE_CLASS:
            case AppConsts::OC_SAMPLE_TYPE:
            case AppConsts::OC_REPORT_DELIVERY_TYPE:
                $sql = "SELECT $tableId AS _val, name AS _opt FROM $table WHERE NOT is_deleted ORDER BY $tableId, name;";
                break;

            // "name" sorted by name + ID:
            case AppConsts::OC_SAMPLING_METHOD:
            case AppConsts::OC_TESTING_METHOD:
            case AppConsts::OC_CONTAINER_TYPE:
                $sql = "SELECT $tableId AS _val, name AS _opt FROM $table WHERE NOT is_deleted ORDER BY name, $tableId;";
                break;

            // "name" sorted by sorting + ID:
            case AppConsts::CC_MARKET_SEGMENT:
                $sql = "SELECT $tableId AS _val, name AS _opt FROM $table WHERE NOT is_deleted ORDER BY sorting, $tableId;";
                break;

            // "name (code)" sorted by name + code + ID:
            case AppConsts::OC_SAMPLING_EQUIPT:
            case AppConsts::OC_TEST:
                $sql = "SELECT $tableId AS _val, CONCAT(name, ' (', code, ')') AS _opt FROM $table WHERE NOT is_deleted ORDER BY name, code, $tableId;";
                break;

            // "name (code)" sorted by sorting + ID:
            case AppConsts::OC_PROCESS_AREA:
                $sql = "SELECT $tableId AS _val, CONCAT(name, ' (', code, ')') AS _opt FROM $table WHERE NOT is_deleted ORDER BY sorting, $tableId;";
                break;

            // "code" sorted by code + ID:
            case AppConsts::OC_CONTAINER_UNIT:
                $sql = "SELECT $tableId AS _val, code AS _opt FROM $table WHERE NOT is_deleted ORDER BY code, $tableId;";
                break;

            // "code - name" sorted by code + name + ID:
            case AppConsts::OC_TEST_ACREDIT_ATTRIB:
                $sql = "SELECT $tableId AS _val, CONCAT(code, ' - ', name) AS _opt FROM $table WHERE NOT is_deleted ORDER BY code, name, $tableId;";
                break;

            // "name (initials)" sorted by name + initials + ID:
            // $params keys that must be supplied: id_user_role
            // $params keys that can be supplied: id_user_attrib
            case AppConsts::CC_USER:
                $sqlRole = "";
                $sqlAttrib = "";

                if (isset($params["id_user_role"])) {
                    $sqlRole = "AND ur.id_user_role = " . strval($params["id_user_role"]) . " ";
                }
                if (isset($params["id_user_attrib"])) {
                    $sqlAttrib = "AND ua.id_user_attrib = " . strval($params["id_user_attrib"]) . " ";
                }

                $sql = "SELECT u.$tableId AS _val, CONCAT(u.name, ' (', u.initials, ')') AS _opt ";
                $sql .= "FROM $table AS u ";
                $sql .= !isset($params["id_user_role"]) ? "" : "INNER JOIN " . AppConsts::$tables[AppConsts::CC_USER_USER_ROLE] . " AS ur ON ur.id_user = u.id_user ";
                $sql .= !isset($params["id_user_attrib"]) ? "" : "INNER JOIN " . AppConsts::$tables[AppConsts::CC_USER_USER_ATTRIB] . " AS ua ON ua.id_user = u.id_user ";
                $sql .= "WHERE NOT u.is_deleted " . $sqlRole . $sqlAttrib;
                $sql .= "ORDER BY u.name, u.initials, u.$tableId;";
                break;

            // "name [alias] (code)" sorted by name + alias + code + ID:
            // $params keys that must be supplied: fk_entity_class
            // $params keys that can be supplied: entity_type or entity_type_n (from 1 to n)
            case AppConsts::CC_ENTITY:
                $sqlType = "";
                $sqlCorp = "";

                if (isset($params["entity_type"])) {
                    $sqlType = strval($params["entity_type"]);
                }
                else {
                    $i = 1;
                    while (true) {
                        if (!isset($params["entity_type_$i"])) {
                            break;
                        }
                        else {
                            $sqlType .= (empty($sqlType) ? "" : ", ") . strval($params["entity_type_$i"]);
                            $i++;
                        }
                    }
                }
                if (isset($params[ModEntity::PARAM_CORP_MEMBERS])) {
                    $sqlCorp = "AND e.nk_entity_parent = " . $params[ModEntity::PARAM_CORP_MEMBERS] . " ";
                }

                $sql = "SELECT e.$tableId AS _val, CONCAT(e.name, IF(e.alias = '', '', CONCAT(', ', e.alias, ',')), ' (', e.code, ')') AS _opt ";
                $sql .= "FROM $table AS e ";
                if (!empty($sqlType)) {
                    $sql .= "INNER JOIN " . AppConsts::$tables[AppConsts::CC_ENTITY_ENTITY_TYPE] . " AS et ON ";
                    $sql .= "et.id_entity = e.id_entity AND et.id_entity_type IN (" . $sqlType . ") ";
                }
                $sql .= "WHERE NOT e.is_deleted AND e.fk_entity_class = " . strval($params["fk_entity_class"]) . " " . $sqlCorp;
                $sql .= "ORDER BY e.name, e.alias, e.code, e.$tableId;";
                break;

            default:
        }

        $options[] = self::composeSelectOption($selectedId);

        if (isset($sql)) {
            foreach ($userSession->getPdo()->query($sql) as $row) {
                $options[] = '<option value="' . $row['_val'] . '"' . (!empty($selectedId) && $selectedId == intval($row['_val']) ? " selected" : "") . '>' . $row['_opt'] . '</option>';
            }
        }

        return $options;
    }

    public static function readField(FUserSession $userSession, string $field, int $catalog, int $id): string
    {
        $name = "";
        $table = AppConsts::$tables[$catalog];
        $tableId = AppConsts::$tableIds[$catalog];
        $sql = "SELECT $field FROM $table WHERE $tableId = $id;";
        $statement = $userSession->getPdo()->query($sql);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $name = $row[$field];
        }
        else {
            throw new \Exception(__METHOD__ . ": " . FRegistry::ERR_MSG_REGISTRY_NOT_FOUND);
        }

        return $name;
    }
}
