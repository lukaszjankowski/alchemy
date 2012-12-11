<?php
use Alchemy\Form\LoginForm;
use Alchemy\Auth\Adapter\Test as AdapterTest;

class AuthControllerTest extends ControllerTestCase
{
    public function testAdminModuleRequiresAthenticatedUser()
    {
        $this->dispatch('/admin/');

        $this->assertModule('admin');
        $this->assertController('auth');
        $this->assertAction('login');
    }

    public function testOtherModuleNotRequireAthenticatedUser()
    {
        $this->dispatch('/gallery/');

        $this->assertModule('gallery');
        $this->assertController('index');
        $this->assertAction('index');
    }

    public function testSuccessfulUserLogin()
    {
        $this->dispatch('/admin/auth/login');
        $this->assertNotRedirect();
        $this->assertQueryContentContains('form', 'Username:');

        $this->resetRequest()->resetResponse();
        $this->loginUser(AdapterTest::DEFAULT_USERNAME, AdapterTest::DEFAULT_PASSWORD);
        $this->assertRedirectTo($this->getRequest()->getBaseUrl() . '/admin/index');

        $this->resetRequest()->resetResponse();
        $this->dispatch('/admin/index');
        $this->assertHasFlashMessage('Successful login');
        $this->assertQueryContentRegex('h5', '/Zalogowany:.*\s+' . AdapterTest::DEFAULT_USERNAME . '/');
        $this->assertTrue(Zend_Auth::getInstance()->hasIdentity());
        $this->assertEquals(AdapterTest::DEFAULT_USERNAME, Zend_Auth::getInstance()->getIdentity());

        $this->resetRequest()->resetResponse();
        $this->dispatch('/admin/auth/login');
        $this->assertRedirectTo($this->getRequest()->getBaseUrl() . '/admin/index');
    }

    public function testUserLoginWithInvalidData()
    {
        $this->loginUser($username = '', $password = '');
        $this->assertNotRedirect();
        $this->assertQueryContentContains('dd#username-element ul.errors li', "Value is required and can't be empty");
        $this->assertQueryContentContains('dd#password-element ul.errors li', "Value is required and can't be empty");
        $this->assertFalse(Zend_Auth::getInstance()->hasIdentity());

        $this->loginUser($username = 'aa', $password = 'short');
        $this->assertNotRedirect();
        $this->assertQueryContentContains('dd#username-element ul.errors li', 'is less than 3 characters long');
        $this->assertQueryContentContains('dd#password-element ul.errors li', 'is less than 6 characters long');
    }

    public function testUserLoginWithWrongCredentials()
    {
        $this->loginUser(AdapterTest::DEFAULT_USERNAME, $password = 'bogus123');
        $this->assertNotRedirect();
        $this->assertQueryContentContains('form', 'Username:');
        $this->assertQueryContentContains('div.actionMessage.error', 'Authentication failed');
        $this->assertFalse(Zend_Auth::getInstance()->hasIdentity());
    }

    public function testNotAuthenticatedUserCannotLogOut()
    {
        $this->dispatch('/admin/auth/logout');

        $this->assertRedirectTo($this->getRequest()->getBaseUrl() . '/admin/index');
    }

    public function testUserLogsOutSuccess()
    {
        $this->loginUser();

        $this->resetRequest()->resetResponse();
        $this->dispatch('/admin/auth/logout');
        $this->assertRedirectTo($this->getRequest()->getBaseUrl() . '/admin/index');
        $this->assertFalse(Zend_Auth::getInstance()->hasIdentity());
        $this->assertHasFlashMessage('Successful logout');
    }

    public function testModelReturnsError()
    {
        Alchemy\ModelFacade::throwsExceptionAtEveryCall(true);
        $this->loginUser();
        $this->assertNotRedirect();
        $this
            ->assertQueryContentContains('div.actionMessage.error',
                'An exception thrown because of self::$throwsExceptionAtEveryCall');
    }

    private function loginUser($username = AdapterTest::DEFAULT_USERNAME, $password = AdapterTest::DEFAULT_PASSWORD)
    {
        $params = array(
            LoginForm::PARAM_USERNAME => $username,
            LoginForm::PARAM_PASSWORD => $password
        );

        $this->request->setMethod('POST')->setPost($params);
        $this->dispatch('/admin/auth/login');
    }

}
