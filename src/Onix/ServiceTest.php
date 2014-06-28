<?php

namespace Onix;

class ServiceTest
{
    private $client;

    private $logger;

    private $response;

    public function __construct($test_name = null)
    {
        $this->client = new \GuzzleHttp\Client();
        $this->logger = Logger::getInstance();
        
        if (!$test_name) {
            $trace = debug_backtrace();
            if (isset($trace[0]['file'])) {
                $test_name = Utility::getTestNameFromFile($trace[0]['file']);
            }
        }
        $this->logger->addTest($test_name);
    }

    public function info($msg)
    {
        $this->logger->info($msg);
    }

    public function get($url, $options = array())
    {
        try {
            $this->response = $this->client->get($url, $options);
            $this->logger->pass('Get '.$url);
        } catch (TestFailedException $tfe) {
            throw $tfe;
        } catch (\Exception $e) {
            $this->logger->fail('Get '.$url, array('exception' => $e->getMessage()));
        }
    }

    public function post($url, $data = array(), $options = array())
    {
        try {
            $this->response = $this->client->post($url, $data, $options);
            $this->logger->pass('Post '.$url);
        } catch (TestFailedException $tfe) {
            throw $tfe;
        } catch (\Exception $e) {
            $this->logger->fail('Post '.$url, array('exception' => $e->getMessage()));
        }
    }

    public function isResponseCode($expected_code)
    {
        try {
            $actual_code = $this->response->getStatusCode();
            if ($actual_code == $expected_code) {
                $this->logger->pass('Response code '.$expected_code);
            } else {
                $this->logger->fail('Response code '.$actual_code.' (excepted '.$expected_code.')');
            }
        } catch (TestFailedException $tfe) {
            throw $tfe;
        } catch (\Exception $e) {
            $this->logger->fail('Response code unknown', array('exception' => $e->getMessage()));
        }
    }

    public function isValidXML()
    {
        try {
            libxml_use_internal_errors(true);
            $xml = new \SimpleXMLElement($this->response->getBody());
            if ($xml instanceof \SimpleXMLElement) {
                $this->logger->pass('XML valid');
            } else {
                $this->logger->fail('XML not valid');
            }
        } catch (TestFailedException $tfe) {
            throw $tfe;
        } catch (\Exception $e) {
            $this->logger->fail('XML not valid', array('exception' => $e->getMessage()));
        }
    }
}
