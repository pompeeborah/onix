<?php

require('vendor/autoload.php');
require('src/bootstrap.php');

$onix = new \Onix\Runner();
$onix->start($argv);
