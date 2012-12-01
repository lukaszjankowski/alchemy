<?php
namespace Alchemy\Controller\Plugin;
class Acl extends \Zend_Controller_Plugin_Abstract
{
    /**
     * Name of guest role
     */
    const ROLE_GUEST = 'guest';

    /**
     * Name of authenticated role
     */
    const ROLE_AUTHENTICATED = 'authenticated';

    /**
     * @var    \Zend_Acl
     */
    private $acl;

    /**
     * @param \Zend_Acl $acl
     */
    public function __construct(\Zend_Acl $acl)
    {
        $this->acl = $acl;
    }

    /**
     * @see \Zend_Controller_Plugin_Abstract::preDispatch()
     */
    public function preDispatch()
    {
        $role = $this->getRole();
        $this->checkRole($role);
    }

    /**
     * @return    \Zend_Acl_Role
     */
    private function getRole()
    {
        $roleName = \Zend_Auth::getInstance()->hasIdentity() ? self::ROLE_AUTHENTICATED : self::ROLE_GUEST;

        return new \Zend_Acl_Role($roleName);
    }

    /**
     * @param \Zend_Acl_Role $role
     */
    private function checkRole(\Zend_Acl_Role $role)
    {
        $resource = $this->getResource();

        if(!$this->acl->has($resource))
        {
            throw new \Zend_Acl_Exception('No ACL configuration for resource: ' . $resource);
        }

        if(!$this->acl->isAllowed($role, $resource))
        {
            $this->_request->setModuleName('admin')->setControllerName('auth')->setActionName('login');
            return;
        }
    }

    /**
     * @return    \Zend_Acl_Resource
     */
    private function getResource()
    {
        $resourceName = $this->_request->getModuleName() . '_' . $this->_request->getControllerName();

        return new \Zend_Acl_Resource($resourceName);
    }

}
