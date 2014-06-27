<?php

namespace Onix;

class ServiceTest
{
    private $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }
}
