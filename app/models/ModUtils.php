<?php
namespace app\models;

use Fraphe\App\FUserSession;
use Fraphe\App\FGuiUtils;
use Fraphe\Model\FItem;
use Fraphe\Model\FRegistry;
use app\AppConsts;

abstract class ModUtils
{
    public const ENTITY_CLASS_CUST = 2;     // customer
    public const ENTITY_CLASS_PROV = 3;     // provider

    public const ENTITY_NATURE_PER = 1;    // person
    public const ENTITY_NATURE_ORG = 2;    // organization

    public static function getEntityClassSingular(int $class): string
    {
        $entity = "";

        switch ($class) {
            case self::ENTITY_CLASS_CUST:
                $entity = "Cliente";
                break;
            case self::ENTITY_CLASS_PROV:
                $entity = "Proveedor";
                break;
            default:
                $entity = "?";
        }

        return $entity;
    }

    public static function getEntityClassPlural(int $class): string
    {
        $entity = "";

        switch ($class) {
            case self::ENTITY_CLASS_CUST:
                $entity = "Clientes";
                break;
            case self::ENTITY_CLASS_PROV:
                $entity = "Proveedores";
                break;
            default:
                $entity = "?";
        }

        return $entity;
    }

    public static function getEntityNature(int $nature): string
    {
        $entity = "";

        switch ($nature) {
            case self::ENTITY_NATURE_PER:
                $entity = "Persona física";
                break;
            case self::ENTITY_NATURE_ORG:
                $entity = "Persona moral";
                break;
            default:
                $entity = "?";
        }

        return $entity;
    }

    public static function getEntityNatureShort(int $nature): string
    {
        $entity = "";

        switch ($nature) {
            case self::ENTITY_NATURE_PER:
                $entity = "P. física";
                break;
            case self::ENTITY_NATURE_ORG:
                $entity = "P. moral";
                break;
            default:
                $entity = "?";
        }

        return $entity;
    }
}
