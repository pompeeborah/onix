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
            if (method_exists($this, $parsed['method'])) {
                $return = call_user_func_array(
                    array($this, $parsed['method']),
                    array($parsed)
                );
            } else {
                $return = array('Status' => 'N', 'ErrMsg' => 'Invalid method');
            }
        }

        return $return;
    }

    private function runTest($params = array())
    {
        if (!isset($params['name'])) {
            $return = array('Status' => 'N', 'ErrMsg' => 'No test provided');
        } else {
            $results = $this->runner->runTest($params['name']);
            if (!isset($results['Status']) || $results['Status'] == 'N') {
                $return = array('Status' => 'N', 'ErrMsg' => isset($results['ErrMsg']) ? $results['ErrMsg'] : '');
            } else {
                $return = array('Status' => 'Y', 'Results' => $results['Results']);
            }
        }

        return $return;
    }

    private function listTests()
    {
        return $this->runner->listTests();
    }
}
