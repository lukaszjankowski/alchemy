<?php
require_once dirname(__FILE__) . '/../../configs/constants.php';
require_once 'testConfig.php';
require_once dirname(__FILE__) . '/../../../configsGenerated/config.php';

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Alchemy');

require_once 'Zend/Application.php';
require_once 'ControllerTestCase.php';
