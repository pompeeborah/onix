<?php

namespace Onix;

class Runner
{
    private $allowed_methods = array(
        'run',
        'list'
    );

    public function __construct() {
    }

    public function start($argv)
    {
        //$this->parseOptions(&$argv);
        if (isset($argv[1]) && in_array($argv[1], $this->allowed_methods)) {
            if (method_exists($this, $argv[1].'Tests')) {
                call_user_func_array(
                    array($this, $argv[1].'Tests'), 
                    array(isset($argv[2]) ? array_splice($argv, 2) : array())
                );
            } else {
                $this->usage();
                throw new Exception('Function allowed, but not present');
            }
        } else {
            $this->usage();
        }
    }

    private function parseOptions($argv)
    {

    }

    private function usage($msg = '')
    {
        echo "Usage: say something nice\n";
    }

    public function runTests($params)
    {
        foreach (glob(TEST_DIR.'/*.php') as $test_file) {
            if (is_readable($test_file)) {
                echo '>> '.Utility::getTestNameFromFile($test_file);
                ob_start();
                try {
                    include($test_file);
                    echo " (PASS)\n";
                } catch (TestFailedException $tfe) {
                    echo " (FAIL)\n";
                } catch (\Exception $e) {

                }
                $test_output = ob_get_clean(); // We'll probably want this later
                print_r($test_output);
            }
        }
        Logger::getInstance()->output();
    }

    public function listTests($params)
    {

    }
}