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
}
