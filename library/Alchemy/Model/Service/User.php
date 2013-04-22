<?php
namespace Alchemy\Model\Service;

use Alchemy\Model;

class User extends Model
{
    /**
     * @var \Zend_Auth_Adapter_Interface
     */
    private $authAdapter;

    /**
     * @var \Zend_Auth
     */
    private $auth;

    /**
     * @param  string  $username
     * @param  string  $password
     * @return boolean
     */
    public function login($username, $password)
    {
        $adapter = $this->getAuthAdapter();
        $adapter->setIdentity($username);
        $adapter->setCredential($password);

        $result = $this->getAuth()->authenticate($adapter);
        \Zend_Session::regenerateId();

        return $result->isValid();
    }

    /**
     * @param  string  $username
     * @param  string  $password
     * @return boolean
     */
    public function checkAuth($username, $password)
    {
        return $this->login($username, $password);
    }

    /**
     * @param \Zend_Auth_Adapter_Interface $authAdapter
     */
    public function setAuthAdapter(\Zend_Auth_Adapter_Interface $authAdapter)
    {
        $this->authAdapter = $authAdapter;
    }

    /**
     * @return \Zend_Auth_Adapter_Interface
     */
    private function getAuthAdapter()
    {
        if (!is_null($this->authAdapter)) {
            return $this->authAdapter;
        }

        return \Zend_Registry::get('authAdapter');
    }

    /**
     * @param \Zend_Auth $auth
     */
    public function setAuth(\Zend_Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return Zend_Auth
     */
    private function getAuth()
    {
        if (!is_null($this->auth)) {
            return $this->auth;
        }

        return \Zend_Auth::getInstance();
    }
}
