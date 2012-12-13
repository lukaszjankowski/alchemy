<?php
namespace AlchemyTest\Controller\Plugin;
use Alchemy\Controller\Plugin\Acl;

class AclTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Request object
     * @var \Zend_Controller_Request_Http
     */
    public $request;

    /**
     * @var \Zend_Acl
     */
    private $acl;

    protected function setUp()
    {
        \Zend_Controller_Front::getInstance()->resetInstance();
        $this->request = new \Zend_Controller_Request_Http;
        \Zend_Session::$_unitTestEnabled = true;

        $this->acl = new \Zend_Acl;
        $this->acl->deny();
        $this->acl->addRole(new \Zend_Acl_Role(Acl::ROLE_GUEST));
        $this->acl->addRole(new \Zend_Acl_Role(Acl::ROLE_AUTHENTICATED), Acl::ROLE_GUEST);

        parent::setUp();
    }

    public function testShouldDenyAccessForUnknownResourceAndNotModifyRequest()
    {
        $request = $this->request->setModuleName('admin')->setControllerName('index')->setActionName('index');
        $plugin = new Acl($this->acl);
        $plugin->setRequest($this->request);

        $plugin->preDispatch();

        $this->assertEquals('gallery', $this->request->getModuleName());
        $this->assertEquals('error', $this->request->getControllerName());
        $this->assertEquals('error404', $this->request->getActionName());
    }

    public function testShouldDenyAccessForWrongRole()
    {
        $this->request->setModuleName('admin')->setControllerName('index')->setActionName('index');
        $this->acl->addResource('admin_index');
        $this->acl->allow(Acl::ROLE_AUTHENTICATED, 'admin_index');

        $plugin = new Acl($this->acl);
        $plugin->setRequest($this->request);
        $plugin->preDispatch();

        $this->assertEquals('admin', $this->request->getModuleName());
        $this->assertEquals('auth', $this->request->getControllerName());
        $this->assertEquals('login', $this->request->getActionName());
    }

    public function testShouldAllowAccessForCorrectRole()
    {
        $request = $this->request->setModuleName('admin')->setControllerName('index')->setActionName('index');
        $this->acl->addResource('admin_index');
        $this->acl->allow(Acl::ROLE_GUEST, 'admin_index');

        $plugin = new Acl($this->acl);
        $plugin->setRequest($this->request);
        $plugin->preDispatch();

        $this->assertEquals('admin', $this->request->getModuleName());
        $this->assertEquals('index', $this->request->getControllerName());
        $this->assertEquals('index', $this->request->getActionName());
    }

}
