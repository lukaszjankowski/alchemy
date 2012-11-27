<?php
// Include website configuration
require '../../configsGenerated/config.php';

require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(APPLICATION_PROCEDURE, '../../configsGenerated/application.ini');
$application->bootstrap()->run();
