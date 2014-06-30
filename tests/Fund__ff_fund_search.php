<?php

$test = new \Onix\ServiceTest();

$test->get('/Fund/ff_fund_search/name_match/fidelity/format/xml');
$test->isResponseCode(200);
$test->isValidXML();
$test->seeXMLElement('/RestServiceFund/item', 100);

$test->get('/Fund/ff_fund_search/name_match/fidelity/format/json');
$test->isResponseCode(200);
$test->isValidJSON();
$test->seeJSONElement('/', 100);
