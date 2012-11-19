<?php
require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

abstract class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    protected $application;

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_PROCEDURE,
            WEBSITE_PATH . '/application/configs/application.ini');

        parent::setUp();
    }

    public function tearDown()
    {
        $this->resetRequest();
        $this->resetResponse();

        parent::tearDown();
    }

    public function dispatch($url = null)
    {
        // removing this helper solves issues with multiple redirects in one test
        // a fresh instance of helper is registered anyway
        Zend_Controller_Action_HelperBroker::removeHelper('redirector');
//         Zend_Controller_Action_HelperBroker::removeHelper('flashMessenger');

        parent::dispatch($url);
    }

    /**
     * Assert that FlashMessanger has $flashMessage between currentMessages
     *
     * @param  string $flashMessage
     * @param  string $message
     * @return void
     */
    protected function assertHasFlashMessage($flashMessage, $message = '')
    {
        $this->_incrementAssertionCount();
        $fm = Zend_Controller_Action_HelperBroker::getStaticHelper('flashMessenger');
        foreach($fm->getCurrentMessages() as $msgArray)
        {
            if($msgArray['txt'] == $flashMessage)
            {
                return;
            }
        }
        $msg = sprintf('Failed asserting FlashMessanger has set message <"%s">', $flashMessage);
        if (!empty($message)) {
            $msg = $message . "\n" . $msg;
        }
        $this->fail($msg);
    }


}
