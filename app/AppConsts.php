<?php
namespace app;

abstract class AppConsts
{
    public const CC_CONFIG_SETTING = 101001;
    public const CC_COMPANY = 101011;
    public const CC_COMPANY_BRANCH = 101012;
    public const CC_USER_TYPE = 101101;
    public const CC_USER_ROLE = 101102;
    public const CC_USER_ATTRIB = 101106;
    public const CC_USER_JOB = 101111;
    public const CC_USER = 101121;
    public const CC_USER_USER_ROLE = 101122;
    public const CC_USER_USER_ATTRIB = 101126;
    public const CC_MARKET_SEGMENT = 101201;
    public const CC_ENTITY_CLASS = 101211;
    public const CC_ENTITY_TYPE = 101212;
    public const CC_ENTITY = 101221;
    public const CC_ENTITY_ENTITY_TYPE = 101226;
    public const CC_ENTITY_SAMPLING_IMG = 101231;
    public const CC_ENTITY_ADDRESS = 101241;
    public const CC_CONTACT_TYPE = 101251;
    public const CC_CONTACT = 101256;

    public const OC_PROCESS_AREA = 201001;
    public const OC_SAMPLE_CLASS = 201101;
    public const OC_SAMPLE_TYPE = 201102;
    public const OC_SAMPLE_STATUS = 201111;
    public const OC_SAMPLING_EQUIPT_TYPE = 201121;
    public const OC_SAMPLING_EQUIPT = 201126;
    public const OC_SAMPLING_METHOD = 201131;
    public const OC_SAMPLING_NOTE = 201139;
    public const OC_CONTAINER_TYPE = 201141;
    public const OC_CONTAINER_UNIT = 201142;
    public const OC_TEST_ACREDIT_ATTRIB = 201201;
    public const OC_TEST = 201211;
    public const OC_TEST_ENTITY = 201216;
    public const OC_TEST_PROFILE = 201221;
    public const OC_TEST_PROFILE_TEST = 201226;
    public const OC_TEST_PACKAGE = 201231;
    public const OC_TEST_PACKAGE_TEST = 201236;
    public const OC_TEST_PACKAGE_TEST_PROFILE = 201237;
    public const OC_TESTING_METHOD = 201241;
    public const OC_TESTING_NOTE = 201249;
    public const OC_RECEPT_STATUS = 201251;
    public const OC_JOB_STATUS = 201261;
    public const OC_REPORT_DELIVERY_TYPE = 201301;
    public const OC_REPORT_REISSUE_CAUSE = 201306;
    public const OC_REPORT_STATUS = 201311;
    public const OC_RESULT_UNIT = 201321;
    public const OC_RESULT_UNIT_VALUE = 201322;
    public const OC_RESULT_PERMISS_LIMIT = 201326;

    public const O_SAMPLE = 202001;
    public const O_SAMPLE_TEST = 202006;
    public const O_SAMPLE_STATUS_LOG = 202011;
    public const O_SAMPLING_IMG = 202021;
    public const O_RECEPT = 202101;
    public const O_JOB = 202201;
    public const O_JOB_TEST = 202206;
    public const O_JOB_STATUS_LOG = 202211;
    public const O_REPORT = 202501;
    public const O_REPORT_TEST = 202506;
    public const O_REPORT_STATUS_LOG = 202511;

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
        self::CC_ENTITY_SAMPLING_IMG => "cc_entity_sampling_img",
        self::CC_ENTITY_ADDRESS => "cc_entity_address",
        self::CC_CONTACT_TYPE => "cc_contact_type",
        self::CC_CONTACT => "cc_contact",

        self::OC_PROCESS_AREA => "oc_process_area",
        self::OC_SAMPLE_CLASS => "oc_sample_class",
        self::OC_SAMPLE_TYPE => "oc_sample_type",
        self::OC_SAMPLE_STATUS => "oc_sample_status",
        self::OC_SAMPLING_EQUIPT_TYPE => "oc_sampling_equipt_type",
        self::OC_SAMPLING_EQUIPT => "oc_sampling_equipt",
        self::OC_SAMPLING_METHOD => "oc_sampling_method",
        self::OC_SAMPLING_NOTE => "oc_sampling_note",
        self::OC_CONTAINER_TYPE => "oc_container_type",
        self::OC_CONTAINER_UNIT => "oc_container_unit",
        self::OC_TEST_ACREDIT_ATTRIB => "oc_test_acredit_attrib",
        self::OC_TEST => "oc_test",
        self::OC_TEST_ENTITY => "oc_test_entity",
        self::OC_TEST_PROFILE => "oc_test_profile",
        self::OC_TEST_PROFILE_TEST => "oc_test_profile_test",
        self::OC_TEST_PACKAGE => "oc_test_package",
        self::OC_TEST_PACKAGE_TEST => "oc_test_package_test",
        self::OC_TEST_PACKAGE_TEST_PROFILE => "oc_test_package_test_profile",
        self::OC_TESTING_METHOD => "oc_testing_method",
        self::OC_TESTING_NOTE => "oc_testing_note",
        self::OC_RECEPT_STATUS => "oc_recept_status",
        self::OC_JOB_STATUS => "oc_job_status",
        self::OC_REPORT_DELIVERY_TYPE => "oc_report_delivery_type",
        self::OC_REPORT_REISSUE_CAUSE => "oc_report_reissue_cause",
        self::OC_REPORT_STATUS => "oc_report_status",
        self::OC_RESULT_UNIT => "oc_result_unit",
        self::OC_RESULT_UNIT_VALUE => "oc_result_unit_value",
        self::OC_RESULT_PERMISS_LIMIT => "oc_result_permiss_limit",

        self::O_SAMPLE => "o_sample",
        self::O_SAMPLE_TEST => "o_sample_test",
        self::O_SAMPLE_STATUS_LOG => "o_sample_status_log",
        self::O_SAMPLING_IMG => "o_sampling_img",
        self::O_RECEPT => "o_recept",
        self::O_JOB => "o_job",
        self::O_JOB_TEST => "o_job_test",
        self::O_JOB_STATUS_LOG => "o_job_status_log",
        self::O_REPORT => "o_report",
        self::O_REPORT_TEST => "o_report_test",
        self::O_REPORT_STATUS_LOG => "o_report_status_log",
    );

    public static $tableIds = array(
        self::CC_CONFIG_SETTING => "id_config_setting",
        self::CC_COMPANY => "id_company",
        self::CC_COMPANY_BRANCH => "id_company_branch",
        self::CC_USER_TYPE => "id_user_type",
        self::CC_USER_ROLE => "id_user_role",
        self::CC_USER_ATTRIB => "id_user_attrib",
        self::CC_USER_JOB => "id_user_job",
        self::CC_USER => "id_user",
        //
        //
        self::CC_MARKET_SEGMENT => "id_market_segment",
        self::CC_ENTITY_CLASS => "id_entity_class",
        self::CC_ENTITY_TYPE => "id_entity_type",
        self::CC_ENTITY => "id_entity",
        //
        self::CC_ENTITY_SAMPLING_IMG => "id_entity_sampling_img",
        self::CC_ENTITY_ADDRESS => "id_entity_address",
        self::CC_CONTACT_TYPE => "id_contact_type",
        self::CC_CONTACT => "id_contact",

        self::OC_PROCESS_AREA => "id_process_area",
        self::OC_SAMPLE_CLASS => "id_sample_class",
        self::OC_SAMPLE_TYPE => "id_sample_type",
        self::OC_SAMPLE_STATUS => "id_sample_status",
        self::OC_SAMPLING_EQUIPT_TYPE => "id_sampling_equipt_type",
        self::OC_SAMPLING_EQUIPT => "id_sampling_equipt",
        self::OC_SAMPLING_METHOD => "id_sampling_method",
        self::OC_SAMPLING_NOTE => "id_sampling_note",
        self::OC_CONTAINER_TYPE => "id_container_type",
        self::OC_CONTAINER_UNIT => "id_container_unit",
        self::OC_TEST_ACREDIT_ATTRIB => "id_test_acredit_attrib",
        self::OC_TEST => "id_test",
        self::OC_TEST_ENTITY => "id_test_entity",
        self::OC_TEST_PROFILE => "id_test_profile",
        //
        self::OC_TEST_PACKAGE => "id_test_package",
        //
        //
        self::OC_TESTING_METHOD => "id_testing_method",
        self::OC_TESTING_NOTE => "id_testing_note",
        self::OC_RECEPT_STATUS => "id_recept_status",
        self::OC_JOB_STATUS => "id_job_status",
        self::OC_REPORT_DELIVERY_TYPE => "id_report_delivery_type",
        self::OC_REPORT_REISSUE_CAUSE => "id_report_reissue_cause",
        self::OC_REPORT_STATUS => "id_report_status",
        self::OC_RESULT_UNIT => "id_result_unit",
        self::OC_RESULT_UNIT_VALUE => "id_result_unit_value",
        self::OC_RESULT_PERMISS_LIMIT => "id_result_permiss_limit",

        self::O_SAMPLE => "id_sample",
        self::O_SAMPLE_TEST => "id_sample_test",
        self::O_SAMPLE_STATUS_LOG => "id_sample_status_log",
        self::O_SAMPLING_IMG => "id_sampling_img",
        self::O_RECEPT => "id_recept",
        self::O_JOB => "id_job",
        self::O_JOB_TEST => "id_job_test",
        self::O_JOB_STATUS_LOG => "id_job_status_log",
        self::O_REPORT => "id_report",
        self::O_REPORT_TEST => "id_report_test",
        self::O_REPORT_STATUS_LOG => "id_report_status_log",
    );
}
