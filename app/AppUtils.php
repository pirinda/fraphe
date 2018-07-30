<?php
namespace app;

use Fraphe\App\FUserSession;

abstract class AppUtils
{
    public static function getSelectOptions(FUserSession $userSession, int $catalog, $selectedValue = 0): array
    {
        $sql;
        $options = array();

        switch ($catalog) {
            case AppConsts::CC_MARKET_SEGMENT:
                $sql = "SELECT id_market_segment AS _val, name AS _opt FROM cc_market_segment WHERE NOT is_deleted ORDER BY id_market_segment, name;";
                break;
            case AppConsts::OC_PROCESS_AREA:
                $sql = "SELECT id_process_area AS _val, name AS _opt FROM oc_process_area WHERE NOT is_deleted ORDER BY id_process_area, name;";
                break;
            case AppConsts::OC_SAMPLE_CATEGORY:
                $sql = "SELECT id_sample_category AS _val, name AS _opt FROM oc_sample_category WHERE NOT is_deleted ORDER BY id_sample_category, name;";
                break;
            case AppConsts::OC_SAMPLE_CLASS:
                $sql = "SELECT id_sample_class AS _val, name AS _opt FROM oc_sample_class WHERE NOT is_deleted ORDER BY id_sample_class, name;";
                break;
            case AppConsts::OC_SAMPLE_TYPE:
                $sql = "SELECT id_sample_type AS _val, name AS _opt FROM oc_sample_type WHERE NOT is_deleted ORDER BY id_sample_type, name;";
                break;
            case AppConsts::OC_SAMPLING_METHOD:
                $sql = "SELECT id_sampling_method AS _val, name AS _opt FROM oc_sampling_method WHERE NOT is_deleted ORDER BY name, id_sampling_method;";
                break;
            case AppConsts::OC_TESTING_METHOD:
                $sql = "SELECT id_testing_method AS _val, name AS _opt FROM oc_testing_method WHERE NOT is_deleted ORDER BY name, id_testing_method;";
                break;
            case AppConsts::OC_TEST_ACREDIT_ATTRIB:
                $sql = "SELECT id_test_acredit_attrib AS _val, CONCAT(code, ' - ', name) AS _opt FROM oc_test_acredit_attrib WHERE NOT is_deleted ORDER BY id_test_acredit_attrib, name;";
                break;
            case AppConsts::OC_REPORT_DELIVERY_OPT:
                $sql = "SELECT id_report_delivery_opt AS _val, name AS _opt FROM oc_report_delivery_opt WHERE NOT is_deleted ORDER BY id_report_delivery_opt, name;";
                break;
            default:
        }

        $options[] = '<option value="0"' . ($selectedValue == 0 ? " selected" : "") . '>- seleccionar -</option>';

        if (isset($sql)) {
            foreach ($userSession->getPdo()->query($sql) as $row) {
                $options[] = '<option value="' . $row['_val'] . '"' . (!empty($selectedValue) && $selectedValue == $row['_val'] ? " selected" : "") . '>' . $row['_opt'] . '</option>';
            }
        }

        return $options;
    }
}
