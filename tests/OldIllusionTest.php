<?php

$test = new \Onix\ServiceTest();
$test->get('http://www.oldillusion.com/onix/sample.xml');
$test->isResponseCode(200);
$test->isValidXML();
$test->seeXMLElement('/catalog/book[@id="bk1a01"]');