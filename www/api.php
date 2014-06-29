<?php

require('../vendor/autoload.php');
require('../src/bootstrap.php');

$api = new \Onix\Api();
echo json_encode($api->process($_SERVER['QUERY_STRING']));
