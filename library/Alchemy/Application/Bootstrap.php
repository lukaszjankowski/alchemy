<?php
namespace Alchemy\Application;
use Alchemy\Controller\Plugin\Acl;

class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap
{
    protected function _initLayout()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $this->getPluginResource('layout')->init();
        $view->doctype('XHTML1_TRANSITIONAL');
        $view->headLink()->appendStylesheet($view->baseUrl('/alchemy/css/main.css'));
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        $view->headTitle('My Gallery');

        return $view;
    }

    protected function _initDb()
    {
        $resources = $this->getOption('resources');
        $dbConfig = $resources['db'];

        $db = \Zend_Db::factory($dbConfig['adapter'],
            array(
                'host' => $dbConfig['params']['host'],
                'username' => $dbConfig['params']['username'],
                'password' => $dbConfig['params']['password'],
                'dbname' => $dbConfig['params']['dbname'],
                'adapterNamespace' => $dbConfig['params']['adapterNamespace']
            ));

        \Zend_Db_Table::setDefaultAdapter($db);
    }

    protected function _initAcl()
    {
        $this->bootstrap('frontController');
        $front = $this->getResource('frontController');
        $acl = new \Zend_Acl;
        $acl->deny();

        $acl->addRole(new \Zend_Acl_Role(Acl::ROLE_GUEST));
        $acl->addRole(new \Zend_Acl_Role(Acl::ROLE_AUTHENTICATED), Acl::ROLE_GUEST);

        $aclConfig = new \Zend_Config_Ini(WEBSITE_PATH . '/application/configs/acl.ini');

        foreach($aclConfig as $resourceName => $role)
        {
            $acl->addResource($resourceName);
            $acl->allow($role, $resourceName);
        }

        $front->registerPlugin(new Acl($acl));
    }

    protected function _initAuth()
    {
        $this->bootstrap('db');
        $authConfig = $this->getOption('auth');
        $adapterClass = $authConfig['adapterClass'];
        $adapter = new $adapterClass;

        \Zend_Registry::set('authAdapter', $adapter);
    }

    protected function _initPlugins()
    {
        \Zend_Controller_Action_HelperBroker::addPrefix('Alchemy\\Controller\\Helper');
    }

}
