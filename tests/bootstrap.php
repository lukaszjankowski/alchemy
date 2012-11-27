<?php
require_once dirname(__FILE__) . '/../configs/constants.php';
define('APPLICATION_PROCEDURE', APPLICATION_PROCEDURE_TESTING);
require_once dirname(__FILE__) . '/../../configsGenerated/config.php';

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Alchemy');

require_once 'Zend/Application.php';
require_once 'tests/application/Controllers/ControllerTestCase.php';
