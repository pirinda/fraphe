<?php
namespace Fraphe\App;

abstract class FAppConsts
{
    public const APP_NAME = "appName";
    public const APP_VENDOR = "appVendor";
    public const APP_MODE = "appMode";
    public const APP_MODS = "appModules";

    public const ROOT_DIR = "rootDir";
    public const ROOT_DIR_WEB = "rootDirWeb";

    public const CFG_FILE_APP = "app/config/app.json";
    public const CFG_FILE_MENU = "app/config/menu.json";

    public const DB_HOST = "dbHost";
    public const DB_PORT = "dbPort";
    public const DB_NAME = "dbName";
    public const DB_USER_NAME = "dbUserName";
    public const DB_USER_PSWD = "dbUserPswd";

    public const USER_ID = "userId";
    public const USER_NAME = "userName";
    public const USER_LOGIN_TS = "userLoginTs";

    public const TAG_PAGE = "page";
    public const TAG_MOD = "module";
    public const TAG_MENU = "menu";

    public const PAGE_HOME = "home";
    public const PAGE_FEAT = "features";
    public const PAGE_HELP = "help";
}
