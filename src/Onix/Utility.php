<?php

namespace Onix;

class Utility
{
    private static $json_errors = array(
        JSON_ERROR_NONE => 'No error has occurred',
        JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
        JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX => 'Syntax error',
        JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
        JSON_ERROR_RECURSION => 'One or more recursive references in the value to be encoded',
        JSON_ERROR_INF_OR_NAN => 'One or more NAN or INF values in the value to be encoded',
        JSON_ERROR_UNSUPPORTED_TYPE => 'A value of a type that cannot be encoded was given'
    );

    public static function getTestNameFromFile($test_file)
    {
        return preg_replace('/\.[a-z0-9_]+$/', '', basename($test_file));
    }

    public static function decodeJSONError($error_code)
    {
        if (array_key_exists($error_code, self::$json_errors)) {
            return self::$json_errors[$error_code];
        } else {
            return 'Unknown error';
        }
    }
}