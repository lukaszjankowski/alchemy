<?php
require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

abstract class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    protected $appConfig;

    public function setUp()
    {
        $this->appConfig = WEBSITE_PATH . '../configsGenerated/application.ini';
        $this->bootstrap = new Zend_Application(APPLICATION_PROCEDURE, $this->appConfig);

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
        if(!empty($message))
        {
            $msg = $message . "\n" . $msg;
        }
        $this->fail($msg);
    }

    /**
     * Assert response is correct JSON format
     *
     * @return void
     */
    protected function assertJsonResponse()
    {
        $this->assertNotRedirect();
        $this->assertHeaderContains('Content-Type', 'application/json');
        $body = $this->_response->getBody();

        try
        {
            $decoded = Zend_Json_Decoder::decode($body);
        }
        catch(Zend_Json_Exception $e)
        {
            $this->fail('Failed asserting response is JSON format');
        }

        if(array('errors', 'result') != array_keys($decoded))
        {
            $this->fail('Failed asserting response is correct JSON format');
        }
    }

}
