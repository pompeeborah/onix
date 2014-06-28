<?php

namespace Onix;

class ServiceTest
{
    private $client;

    private $logger;

    private $response;

    private $decoded_body;

    private $body_type;

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
            $this->decoded_body = new \SimpleXMLElement($this->response->getBody());
            if ($this->decoded_body instanceof \SimpleXMLElement) {
                $this->body_type = 'xml';
                $this->logger->pass('XML valid');
            } else {
                $this->body_type = null;
                $this->logger->fail('XML not valid');
            }
        } catch (TestFailedException $tfe) {
            throw $tfe;
        } catch (\Exception $e) {
            $this->logger->fail(
                'XML not valid',
                array('exception' => $e->getMessage(), 'errors' => libxml_get_errors())
            );
        }
    }

    public function seeXMLElement($xpath, $min_count = 0)
    {
        if ($this->body_type != 'xml') {
            $this->isValidXML();
        }

        $results = $this->decoded_body->xpath($xpath);
        if (!$results || empty($results)) {
            $this->logger->fail('XML element not found: '.$xpath);
        } else {
            if (count($results) >= $min_count) {
                $this->logger->pass('XML element found: '.$xpath.' ('.count($results).'/'.$min_count.')');
            } else {
                $this->logger->fail(
                    'XML element found, but below minimum count: '.$xpath.' ('.count($results).'/'.$min_count.')'
                );
            }
        }
    }

    public function isValidJSON()
    {
        $this->decoded_body = json_decode($this->response->getBody());
        if (!$this->decoded_body) {
            $this->logger->fail('JSON not valid', array('exception' => Utility::decodeJSONError(json_last_error())));
        } else {
            $this->body_type = 'json';
            $this->logger->pass('JSON valid');
        }
    }

    public function seeJSONElement($path, $min_count = 0)
    {
        if ($this->body_type != 'json') {
            $this->isValidJSON();
        }

        
    }
}
