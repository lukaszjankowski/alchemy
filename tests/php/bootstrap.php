<?php
require_once dirname(__FILE__) . '/../../configs/constants.php';
require_once 'testConfig.php';
require_once dirname(__FILE__) . '/../../../configsGenerated/config.php';

$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Alchemy');

require_once 'ControllerTestCase.php';
