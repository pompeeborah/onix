<?php

$test = new \Onix\ServiceTest();

$test->get('http://www.oldillusion.com/onix/sample.xml');
//$test->isResponseCode(200);
//$test->isValidXML();
//$test->seeXMLElement('/catalog/book[@id]', 15);

$test->get('http://www.oldillusion.com/onix/sample.json');
$test->isValidJSON();
$test->seeJSONElement('book', 15);