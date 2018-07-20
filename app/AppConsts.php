<?php
namespace app;

abstract class AppConsts
{
    public const CC_CONFIG_SETTING = 101001;
    public const CC_COMPANY = 101011;
    public const CC_COMPANY_BRANCH = 101012;
    public const CC_USER_TYPE = 101021;
    public const CC_USER_ROLE = 101022;
    public const CC_USER_ATTRIB = 101023;
    public const CC_USER_JOB = 101025;
    public const CC_USER = 101026;
    public const CC_USER_USER_ROLE = 101027;
    public const CC_USER_USER_ATTRIB = 101028;
    public const CC_MARKET_SEGMENT = 101031;
    public const CC_ENTITY_CLASS = 101041;
    public const CC_ENTITY_TYPE = 101042;
    public const CC_ENTITY = 101046;
    public const CC_ENTITY_ENTITY_TYPE = 101047;
    public const CC_ENTITY_ADDRESS = 101048;
    public const CC_CONTACT_TYPE = 101051;
    public const CC_CONTACT = 101056;

    public const OC_PROCESS_AREA = 201011;
    public const OC_SAMPLE_CATEGORY = 201021;
    public const OC_SAMPLE_CLASS = 201022;
    public const OC_SAMPLE_TYPE = 201023;
    public const OC_SAMPLE_STATUS = 201024;
    public const OC_SAMPLING_EQUIPT_TYPE = 201026;
    public const OC_SAMPLING_EQUIPT = 201027;
    public const OC_SAMPLING_METHOD = 201031;
    public const OC_TESTING_METHOD = 201036;
    public const OC_TEST_ACREDIT_ATTRIB = 201039;
    public const OC_TEST = 201041;
    public const OC_TEST_PROCESS_OPT = 201046;
    public const OC_TEST_PROFILE = 201051;
    public const OC_TEST_PROFILE_TEST = 201052;
    public const OC_TEST_PACKAGE = 201061;
    public const OC_TEST_PACKAGE_TEST = 201062;
    public const OC_TEST_PACKAGE_TEST_PROFILE = 201063;
    public const OC_CONTAINER_TYPE = 201071;
    public const OC_CONTAINER_UNIT = 201072;
    public const OC_REPORT_DELIVERY_OPT = 201081;
    public const OC_REPORT_REISSUE_REASON = 201082;
    public const OC_RESULT_UNIT = 201086;
    public const OC_RESULT_UNIT_VALUE = 201087;
    public const OC_RESULT_PERMISS_LIMIT = 201088;
    public const OC_SAMPLING_NOTE = 201101;
    public const OC_TESTING_NOTE = 201106;
    public const OC_JOB_STATUS = 201121;

    public const O_SAMPLE = 202001;
    public const O_SAMPLE_TEST = 202006;
    public const O_SAMPLE_STATUS_LOG = 202009;
    public const O_RECEPT = 202011;
    public const O_JOB = 202021;
    public const O_JOB_TEST = 202026;
    public const O_JOB_STATUS_LOG = 202029;

    public static $tables = array(
        self::CC_CONFIG_SETTING => "cc_config_setting",
        self::CC_COMPANY => "cc_company",
        self::CC_COMPANY_BRANCH => "cc_company_branch",
        self::CC_USER_TYPE => "cc_user_type",
        self::CC_USER_ROLE => "cc_user_role",
        self::CC_USER_ATTRIB => "cc_user_attrib",
        self::CC_USER_JOB => "cc_user_job",
        self::CC_USER => "cc_user",
        self::CC_USER_USER_ROLE => "cc_user_user_role",
        self::CC_USER_USER_ATTRIB => "cc_user_user_attrib",
        self::CC_MARKET_SEGMENT => "cc_market_segment",
        self::CC_ENTITY_CLASS => "cc_entity_class",
        self::CC_ENTITY_TYPE => "cc_entity_type",
        self::CC_ENTITY => "cc_entity",
        self::CC_ENTITY_ENTITY_TYPE => "cc_entity_entity_type",
        self::CC_ENTITY_ADDRESS => "cc_entity_address",
        self::CC_CONTACT_TYPE => "cc_contact_type",
        self::CC_CONTACT => "cc_contact",

        self::OC_PROCESS_AREA => "oc_process_area",
        self::OC_SAMPLE_CATEGORY => "oc_sample_category",
        self::OC_SAMPLE_CLASS => "oc_sample_class",
        self::OC_SAMPLE_TYPE => "oc_sample_type",
        self::OC_SAMPLE_STATUS => "oc_sample_status",
        self::OC_SAMPLING_EQUIPT_TYPE => "oc_sampling_equipt_type",
        self::OC_SAMPLING_EQUIPT => "oc_sampling_equipt",
        self::OC_SAMPLING_METHOD => "oc_sampling_method",
        self::OC_TESTING_METHOD => "oc_testing_method",
        self::OC_TEST_ACREDIT_ATTRIB => "oc_test_acredit_attrib",
        self::OC_TEST => "oc_test",
        self::OC_TEST_PROCESS_OPT => "oc_test_process_opt",
        self::OC_TEST_PROFILE => "oc_test_profile",
        self::OC_TEST_PROFILE_TEST => "oc_test_profile_test",
        self::OC_TEST_PACKAGE => "oc_test_package",
        self::OC_TEST_PACKAGE_TEST => "oc_test_package_test",
        self::OC_TEST_PACKAGE_TEST_PROFILE => "oc_test_package_test_profile",
        self::OC_CONTAINER_TYPE => "oc_container_type",
        self::OC_CONTAINER_UNIT => "oc_container_unit",
        self::OC_REPORT_DELIVERY_OPT => "oc_report_delivery_opt",
        self::OC_REPORT_REISSUE_REASON => "oc_report_reissue_reason",
        self::OC_RESULT_UNIT => "oc_result_unit",
        self::OC_RESULT_UNIT_VALUE => "oc_result_unit_value",
        self::OC_RESULT_PERMISS_LIMIT => "oc_result_permiss_limit",
        self::OC_SAMPLING_NOTE => "oc_sampling_note",
        self::OC_TESTING_NOTE => "oc_testing_note",
        self::OC_JOB_STATUS => "oc_job_status",

        self::O_SAMPLE => "o_sample",
        self::O_SAMPLE_TEST => "o_sample_test",
        self::O_SAMPLE_STATUS_LOG => "o_sample_status_log",
        self::O_RECEPT => "o_recept",
        self::O_JOB => "o_job",
        self::O_JOB_TEST => "o_job_test",
        self::O_JOB_STATUS_LOG => "o_job_status_log",
    );
}
