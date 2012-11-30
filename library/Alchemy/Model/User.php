<?php
namespace Alchemy\Model;

use Alchemy\Model;

class User extends Model
{
    public function login($username, $password)
    {
        $adapter = \Zend_Registry::get('authAdapter');
        $adapter->setIdentity($username);
        $adapter->setCredential($password);

        $auth = \Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        \Zend_Session::regenerateId();

        if($result->isValid())
        {
            return true;
        }

        return false;
    }

}
