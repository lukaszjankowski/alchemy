<?php
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(APPLICATION_PROCEDURE, WEBSITE_PATH . '/application/configs/application.ini');
$application->bootstrap()->run();
