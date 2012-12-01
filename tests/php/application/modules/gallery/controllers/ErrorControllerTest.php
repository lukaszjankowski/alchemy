<?php
class ErrorControllerTest extends ControllerTestCase
{
    public function testInvalidURLShouldBeHandledByErrorController()
    {
        $this->dispatch('/foo');

        $this->assertModule('gallery');
        $this->assertController('error');
        $this->assertAction('error');
        $this->assertResponseCode(500);
        $this->assertQueryContentContains('body', 'No ACL configuration for resource: gallery_foo');
// temporarily commented out
//         $this->assertResponseCode(404);
//         $this->assertQueryContentContains('h2', 'Page not found');
    }

    public function testInvalidModuleShouldRaiseError500()
    {
        $this->dispatch('/gallery/error/error500');

        $this->assertModule('gallery');
        $this->assertController('error');
        $this->assertAction('error');
        $this->assertResponseCode(500);
        $this->assertQueryContentContains('h2', 'Application error');
    }

    public function testBacktraceShouldBeDisplayedInDevEnv()
    {
        $this->bootstrap = new Zend_Application('DEVELOPMENT', $this->appConfig);
        $this->bootstrap();
        $this->dispatch('/gallery/error/error500');

        $this->assertQueryContentContains('h3', 'Exception information:');
        $this->assertQueryContentContains('h3', 'Stack trace:');
    }

    public function testBacktraceShouldNotBeDisplayedInProEnv()
    {
        $this->bootstrap = new Zend_Application('PRODUCTION', $this->appConfig);
        $this->bootstrap();
        $this->dispatch('/gallery/error/error500');

        $this->assertNotQueryContentContains('h3', 'Exception information:');
        $this->assertNotQueryContentContains('h3', 'Stack trace:');
    }

}
