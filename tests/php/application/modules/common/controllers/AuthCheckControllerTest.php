<?php
use Alchemy\Form\LoginForm;
use Alchemy\Auth\Adapter\Test as AdapterTest;

class Common_AuthCheckControllerTest extends ControllerTestCase
{
    private $correctAuthData = array(
        LoginForm::PARAM_USERNAME => AdapterTest::DEFAULT_USERNAME,
        LoginForm::PARAM_PASSWORD => AdapterTest::DEFAULT_PASSWORD
    );

    private $incorrectAuthData = array(
        LoginForm::PARAM_USERNAME => 'foo',
        LoginForm::PARAM_PASSWORD => 'password'
    );

    public function testReturnsResultNotOkWhenNoParametersGiven()
    {
        $this->dispatch('/common/authCheck');

        $expected = Common_AuthCheckController::RESULT_NOT_OK;
        $actual = $this->assertJsonResponse()->result->result;
        $this->assertEquals($expected, $actual);
    }

    public function testReturnsResultNotOkWhenIncorrectParametersGiven()
    {
        $this->_request->setMethod('POST')->setPost($this->incorrectAuthData);

        $this->dispatch('/common/authCheck');

        $expected = Common_AuthCheckController::RESULT_NOT_OK;
        $actual = $this->assertJsonResponse()->result->result;
        $this->assertEquals($expected, $actual);
    }

    public function testReturnsResultOkWhenCorrectParametersGiven()
    {
        $this->_request->setMethod('POST')->setPost($this->correctAuthData);

        $this->dispatch('/common/authCheck');

        $expected = Common_AuthCheckController::RESULT_OK;
        $actual = $this->assertJsonResponse()->result->result;
        $this->assertEquals($expected, $actual);
    }

    public function testReturnsResultNotOkOnModelError()
    {
        $this->forceErrorFromModel('User');
        $this->_request->setMethod('POST')->setPost($this->correctAuthData);

        $this->dispatch('/common/authCheck');
        $this->assertForcedErrorFromModel();
    }

}
