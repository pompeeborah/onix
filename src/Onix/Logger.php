<?php

namespace Onix;

class Logger
{
    private static $instance;

    private $test_data = array();

    private $current_test = null;

    private function __construct()
    {
    }

    public static function getInstance($refresh = false)
    {
        if ($refresh || !self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function addTest($name, $params = array())
    {
        $this->test_data[$name] = array();
        $this->current_test = &$this->test_data[$name];
        $this->current_test['steps'] = array();
        $this->current_test['result'] = 'pass';
    }

    public function pass($msg, $data = array())
    {
        $results = array(
            'state' => 1,
            'message' => $msg
        );

        if (!empty($data)) {
            $results['extra'] = $data;
        }

        $this->current_test['steps'][] = $results;
    }

    public function fail($msg, $data = array())
    {
        $results = array(
            'state' => 0,
            'message' => $msg
        );
        
        if (!empty($data)) {
            $results['extra'] = $data;
        }

        $this->current_test['steps'][] = $results;

        $this->current_test['result'] = 'fail';

        throw new TestFailedException($msg);
    }

    public function info($msg)
    {
        $this->current_test['steps'][] = array('message' => $msg);
    }

    public function getCurrentResults()
    {
        return $this->current_test;
    }

    public function getAllResults()
    {
        return $this->test_data;
    }

    public function output()
    {
        print_r($this->test_data);
    }
}
