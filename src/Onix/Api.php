<?php

namespace Onix;

class Api
{
    private $runner;

    public function __construct()
    {
        $this->runner = new Runner();
    }

    public function process($query)
    {
        parse_str($query, $parsed);
        if (empty($parsed) || !isset($parsed['method'])) {
            $return = array('Status' => 'N', 'ErrMsg' => 'Nothing to do');
        } else {

            $return = array('Status' => 'Y');
        }

        return $return;
    }

    private function runTest($params = array())
    {

    }
}