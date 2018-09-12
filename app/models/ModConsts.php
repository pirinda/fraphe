<?php
namespace app\models;

abstract class ModConsts
{
    public const CC_CONFIG_SETTING_VERSION = 1;

    public const CC_COMPANY_COM = 1;

    public const CC_COMPANY_BRANCH_HQ = 1;

    public const CC_USER_TYPE_ADMIN = 1;
    public const CC_USER_TYPE_USER = 2;

    public const CC_USER_NA = 1;
    public const CC_USER_SUPER = 2;
    public const CC_USER_ADMIN = 3;

    public const CC_ENTITY_CLASS_COM = 1;
    public const CC_ENTITY_CLASS_CUST = 2;
    public const CC_ENTITY_CLASS_PROV = 3;

    public const CC_ENTITY_TYPE_COM = 101;
    public const CC_ENTITY_TYPE_CUST_PUB = 201;
    public const CC_ENTITY_TYPE_CUST_CORP = 211;
    public const CC_ENTITY_TYPE_CUST_ENT = 212;
    public const CC_ENTITY_TYPE_CUST_HOSP = 221;
    public const CC_ENTITY_TYPE_CUST_ASSUR = 226;
    public const CC_ENTITY_TYPE_CUST_LAB = 229;
    public const CC_ENTITY_TYPE_CUST_SPEC = 231;
    public const CC_ENTITY_TYPE_CUST_SERV_FIN = 251;
    public const CC_ENTITY_TYPE_CUST_SERV = 252;
    public const CC_ENTITY_TYPE_CUST_CONS = 256;
    public const CC_ENTITY_TYPE_CUST_SAL_EXT = 261;
    public const CC_ENTITY_TYPE_CUST_SAL_INT = 266;
    public const CC_ENTITY_TYPE_CUST_EMP = 269;
    public const CC_ENTITY_TYPE_PROV_PUB = 301;
    public const CC_ENTITY_TYPE_PROV_CORP = 311;
    public const CC_ENTITY_TYPE_PROV_ENT = 312;
    public const CC_ENTITY_TYPE_PROV_HOSP = 321;
    public const CC_ENTITY_TYPE_PROV_ASSUR = 326;
    public const CC_ENTITY_TYPE_PROV_LAB = 329;
    public const CC_ENTITY_TYPE_PROV_SPEC = 331;
    public const CC_ENTITY_TYPE_PROV_SERV_FIN = 351;
    public const CC_ENTITY_TYPE_PROV_SERV = 352;
    public const CC_ENTITY_TYPE_PROV_CONS = 356;
    public const CC_ENTITY_TYPE_PROV_SAL_EXT = 361;
    public const CC_ENTITY_TYPE_PROV_SAL_INT = 366;
    public const CC_ENTITY_TYPE_PROV_EMP = 369;

    public const CC_ENTITY_COM = 1;
    public const CC_ENTITY_CUST_PUB = 2;
    public const CC_ENTITY_PROV_PUB = 3;

    public const CC_CONTACT_TYPE_MAIN = 1;
    public const CC_CONTACT_TYPE_TECH = 2;
    public const CC_CONTACT_TYPE_RECEPT = 3;
    public const CC_CONTACT_TYPE_SAMPLING = 4;
    public const CC_CONTACT_TYPE_PROCESS = 5;
    public const CC_CONTACT_TYPE_RESULT = 6;
    public const CC_CONTACT_TYPE_QLT = 7;
    public const CC_CONTACT_TYPE_MKT = 8;
    public const CC_CONTACT_TYPE_BILL = 9;
    public const CC_CONTACT_TYPE_COLL = 10;

    public const CC_USER_ROLE_RECEPT = 1;
    public const CC_USER_ROLE_SAMPLING = 2;
    public const CC_USER_ROLE_PROCESS = 3;
    public const CC_USER_ROLE_REPORT = 4;
    public const CC_USER_ROLE_QUALITY = 5;
    public const CC_USER_ROLE_MARKETING = 6;
    public const CC_USER_ROLE_DIRECTION = 7;

    public const CC_USER_ATTRIB_SIGN_SAMPLING = 1;
    public const CC_USER_ATTRIB_SIGN_PROCESSING = 2;

    public const OC_SAMPLE_STATUS_SAMPLING = 1;
    public const OC_SAMPLE_STATUS_RECEPT = 2;
    public const OC_SAMPLE_STATUS_PROCESS = 3;
    public const OC_SAMPLE_STATUS_PROCESSING = 4;
    public const OC_SAMPLE_STATUS_GUARD = 5;
    public const OC_SAMPLE_STATUS_DISCARTED = 6;
    public const OC_SAMPLE_STATUS_CANCELLED = 9;

    public const OC_SAMPLING_METHOD_CUSTOMER = 1;

    public const OC_RECEPT_STATUS_NEW = 1;
    public const OC_RECEPT_STATUS_PROCESSING = 2;
    public const OC_RECEPT_STATUS_CANCELLED = 9;

    public const OC_JOB_STATUS_PENDING = 1;
    public const OC_JOB_STATUS_PROCESSING = 2;
    public const OC_JOB_STATUS_FINISHED = 3;
    public const OC_JOB_STATUS_CANCELLED = 9;

    public const OC_REPORT_DELIVERY_TYPE_E = 1;
    public const OC_REPORT_DELIVERY_TYPE_E_PRINT_RECEPT = 2;
    public const OC_REPORT_DELIVERY_TYPE_E_PRINT_PLACE = 3;
    public const OC_REPORT_DELIVERY_TYPE_PRINT_RECEPT = 4;
    public const OC_REPORT_DELIVERY_TYPE_PRINT_PLACE = 5;

    public const OC_REPORT_STATUS_PENDING = 1;
    public const OC_REPORT_STATUS_PROCESSING = 2;
    public const OC_REPORT_STATUS_FINISHED = 3;
    public const OC_REPORT_STATUS_VERIFIED = 4;
    public const OC_REPORT_STATUS_VALIDATED = 5;
    public const OC_REPORT_STATUS_RELEASED = 6;
    public const OC_REPORT_STATUS_DELIVERED = 7;
    public const OC_REPORT_STATUS_CANCELLED = 9;

    public const OC_RESULT_PERMISS_LIMIT_NA = 1;
    public const OC_RESULT_PERMISS_LIMIT_CUSTOMER = 2;
}
