<?php

define('ROOT_DIR', dirname(__DIR__));
define('ETC_DIR', ROOT_DIR.'/etc');
define('LOG_DIR', ROOT_DIR.'/log');
define('TEST_DIR', ROOT_DIR.'/tests');

function srcAutoloader($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', '/', $namespace) . '/';
    }
    $fileName .= str_replace('_', '/', $className) . '.php';

    require_once $fileName;
}

spl_autoload_register('srcAutoloader');