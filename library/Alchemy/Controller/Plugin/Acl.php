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

    const RESULT_ACCESS_ALLOWED = 'allowed';

    const RESULT_ACCESS_DENIED = 'denied';

    /**
     * @var    \Zend_Acl
     */
    private $acl;

    /**
     * ACL verification result
     *
     * @var    string
     */
    private $result;

    /**
     * @param \Zend_Acl $acl
     */
    public function __construct(\Zend_Acl $acl)
    {
        $this->acl = $acl;
        $this->result = self::RESULT_ACCESS_DENIED;
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
        $roleName = \Zend_Auth::getInstance()->hasIdentity() ? self::ROLE_AUTHENTICATED
            : self::ROLE_GUEST;

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
            return;
        }

        if(!$this->acl->isAllowed($role, $resource))
        {
            $this->_request->setModuleName('admin')->setControllerName('auth')->setActionName('login');
            return;
        }

        $this->result = self::RESULT_ACCESS_ALLOWED;
    }

    /**
     * @return    \Zend_Acl_Resource
     */
    private function getResource()
    {
        $resourceName = $this->_request->getModuleName() . '_' . $this->_request->getControllerName();

        return new \Zend_Acl_Resource($resourceName);
    }

    /**
     * @return    string
     */
    public function getResult()
    {
        return $this->result;
    }

}
