<?php

namespace Onix;

class Utility
{
    public static function getTestNameFromFile($test_file)
    {
        return preg_replace('/\.[a-z0-9_]+$/', '', basename($test_file));
    }
}