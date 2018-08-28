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

    public static function formatDbmsDate(int $timestamp): string
    {
        return !isset($timestamp) ? null : date("Y-m-d", $timestamp);
    }

    public static function formatDbmsDatetime(int $timestamp): string
    {
        return !isset($timestamp) ? null : date("Y-m-d H:i:s", $timestamp);
    }

    public static function formatDbmsTime(int $timestamp): string
    {
        return !isset($timestamp) ? null : date("H:i:s", $timestamp);
    }

    public static function formatDbmsTimestamp(int $timestamp): string
    {
        return !isset($timestamp) ? null : date("Y-m-d H:i:s", $timestamp);
    }

    public static function parseDbmsDate(string $time): int
    {
        if (!isset($time)) {
            return null;
        }

        $dt = \DateTime::createFromFormat("Y-m-d", $time);
        return $dt->getTimestamp();
    }

    public static function parseDbmsDatetime(string $time): int
    {
        if (!isset($time)) {
            return null;
        }

        $dt = \DateTime::createFromFormat("Y-m-d H:i:s", $time);
        return $dt->getTimestamp();
    }

    public static function parseDbmsTime(string $time): int
    {
        if (!isset($time)) {
            return null;
        }

        $dt = \DateTime::createFromFormat("H:i:s", $time);
        return $dt->getTimestamp();
    }

    public static function parseDbmsTimestamp(string $time): int
    {
        if (!isset($time)) {
            return null;
        }

        $dt = \DateTime::createFromFormat("Y-m-d H:i:s", $time);
        return $dt->getTimestamp();
    }

    public static function extractDate(int $timestamp): int
    {
        $dt = new \DateTime(self::formatDbmsDate($timestamp));
        return $dt->getTimestamp();
    }
}
