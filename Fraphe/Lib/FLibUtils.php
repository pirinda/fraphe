<?php
namespace Fraphe\Lib;

abstract class FLibUtils
{
    public static function sanitizeInput(string $input): string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);

        return $input;
    }

    /** Format used: "d/m/Y".
      */
    public static function formatLocDate(int $timestamp): string
    {
        return !isset($timestamp) ? "" : date("d/m/Y", $timestamp);
    }

    /** Format used: "d/m/Y H:i:s".
      */
    public static function formatLocDatetime(int $timestamp): string
    {
        return !isset($timestamp) ? "" : date("d/m/Y H:i:s", $timestamp);
    }

    /** Format used: "Y-m-d".
      */
    public static function formatStdDate(int $timestamp): string
    {
        return !isset($timestamp) ? "" : date("Y-m-d", $timestamp);
    }

    /** Format used: "Y-m-d H:i:s".
      */
    public static function formatStdDatetime(int $timestamp): string
    {
        return !isset($timestamp) ? "" : date("Y-m-d H:i:s", $timestamp);
    }

    /** Format used: "H:i:s".
      */
    public static function formatStdTime(int $timestamp): string
    {
        return !isset($timestamp) ? "" : date("H:i:s", $timestamp);
    }

    /** Format used: "Y-m-d H:i:s". Same as formatStdDate().
      */
    public static function formatStdTimestamp(int $timestamp): string
    {
        return self::formatStdDatetime($timestamp);
    }

    /** Format used: "Y-m-d\TH:i".
      */
    public static function formatHtmlDatetime(int $timestamp): string
    {
        return !isset($timestamp) ? "" : date("Y-m-d\TH:i", $timestamp);
    }
    /** Format used: "!Y-m-d".
      */
    public static function parseStdDate(string $time): int
    {
        if (!isset($time)) {
            return 0;
        }

        $dt = \DateTime::createFromFormat("!Y-m-d", $time, new \DateTimeZone(date_default_timezone_get()));
        if ($dt === false) {
            return 0;
        }
        return $dt->getTimestamp();
    }

    /** Format used: "!Y-m-d H:i:s".
      */
    public static function parseStdDatetime(string $time): int
    {
        if (!isset($time)) {
            return 0;
        }

        $dt = \DateTime::createFromFormat("!Y-m-d H:i:s", $time, new \DateTimeZone(date_default_timezone_get()));
        if ($dt === false) {
            return 0;
        }
        return $dt->getTimestamp();
    }

    /** Format used: "!H:i:s".
      */
    public static function parseStdTime(string $time): int
    {
        if (!isset($time)) {
            return 0;
        }

        $dt = \DateTime::createFromFormat("!H:i:s", $time, new \DateTimeZone(date_default_timezone_get()));
        if ($dt === false) {
            return 0;
        }
        return $dt->getTimestamp();
    }

    /** Format used: "!Y-m-d H:i:s". Same as parseStdDatetime().
      */
    public static function parseStdTimestamp(string $time): int
    {
        return self::parseStdDatetime($time);
    }

    /** Format used: "!Y-m-d\TH:i".
      */
    public static function parseHtmlDatetime(string $time): int
    {
        if (!isset($time)) {
            return 0;
        }

        $dt = \DateTime::createFromFormat("!Y-m-d\TH:i", $time, new \DateTimeZone(date_default_timezone_get()));
        if ($dt === false) {
            return 0;
        }
        return $dt->getTimestamp();
    }

    /** Format used: "Y-m-d".
      */
    public static function extractDate(int $timestamp): int
    {
        if (!isset($timestamp)) {
            return 0;
        }

        $dt = new \DateTime(self::formatStdDate($timestamp));
        if ($dt === false) {
            return 0;
        }
        return $dt->getTimestamp();
    }

    /** Format used: "Y-m-d H:i:s".
      */
    public static function getLocalDatetime(): int
    {
        return self::parseStdDatetime(date("Y-m-d H:i:s"));
    }
}
