<?php

namespace Onix;

class Runner
{
    private $allowed_methods = array(
        'run'
    );

    public function __construct($argv) {
        //$this->parseOptions(&$argv);
        if (isset($argv[1]) && in_array($argv[1], $this->allowed_methods)) {
            if (method_exists($this, $argv[1])) {
                call_user_func_array(
                    array($this, $argv[1]), 
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

    private function parseOptions($argv) {
        
    }

    private function usage($msg = '')
    {
        echo "Usage: say something nice\n";
    }

    private function run($params)
    {

    }
}