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
}