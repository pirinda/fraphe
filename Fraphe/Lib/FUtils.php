<?php
namespace Fraphe\Lib;

abstract class FUtils
{
    public static function sanitizeInput(string $input): string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);

        return $input;
    }

    /** Format used: "Y-m-d".
      */
    public static function formatDbmsDate(int $timestamp): string
    {
        return !isset($timestamp) ? "" : date("Y-m-d", $timestamp);
    }

    /** Format used: "Y-m-d  H:i:s".
      */
    public static function formatDbmsDatetime(int $timestamp): string
    {
        return !isset($timestamp) ? "" : date("Y-m-d H:i:s", $timestamp);
    }

    /** Format used: " H:i:s".
      */
    public static function formatDbmsTime(int $timestamp): string
    {
        return !isset($timestamp) ? "" : date("H:i:s", $timestamp);
    }

    /** Format used: "Y-m-d H:i:s". Same as formatDbmsDate().
      */
    public static function formatDbmsTimestamp(int $timestamp): string
    {
        return self::formatDbmsDatetime($timestamp);
    }

    /** Format used: "Y-m-d".
      */
    public static function parseDbmsDate(string $time): int
    {
        if (!isset($time)) {
            return 0;
        }

        $dt = \DateTime::createFromFormat("!Y-m-d", $time, new \DateTimeZone(date_default_timezone_get())); // '!'
        return $dt->getTimestamp();
    }

    /** Format used: "Y-m-d H:i:s".
      */
    public static function parseDbmsDatetime(string $time): int
    {
        if (!isset($time)) {
            return 0;
        }

        $dt = \DateTime::createFromFormat("!Y-m-d H:i:s", $time, new \DateTimeZone(date_default_timezone_get()));
        return $dt->getTimestamp();
    }

    /** Format used: "H:i:s".
      */
    public static function parseDbmsTime(string $time): int
    {
        if (!isset($time)) {
            return 0;
        }

        $dt = \DateTime::createFromFormat("!H:i:s", $time, new \DateTimeZone(date_default_timezone_get()));
        return $dt->getTimestamp();
    }

    /** Format used: "Y-m-d H:i:s". Same as parseDbmsDatetime().
      */
    public static function parseDbmsTimestamp(string $time): int
    {
        return self::parseDbmsDatetime($time);
    }

    /** Format used: "Y-m-d".
      */
    public static function extractDate(int $timestamp): int
    {
        if (!isset($timestamp)) {
            return 0;
        }

        $dt = new \DateTime(self::formatDbmsDate($timestamp));
        return $dt->getTimestamp();
    }

    /** Format used: "Y-m-d H:i:s".
      */
    public static function getLocalDatetime(): int
    {
        return self::parseDbmsDatetime(date("Y-m-d H:i:s"));
    }
}
