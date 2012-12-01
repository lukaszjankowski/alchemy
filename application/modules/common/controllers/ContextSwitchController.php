<?php
/**
 * Context switch controller
 *
 * @author Łukasz Jankowski <mail@lukaszjankowski.info>
 */
class Admin_ContextSwitchController extends Alchemy\Controller\Action
{
    public function indexAction()
    {
        return $this->forward('json');
    }

    public function jsonAction()
    {
        $module = $this->_getParam('m', $this->getRequest()->getModuleName());
        $controller = $this->_getParam('c', $this->getRequest()->getControllerName());
        $action = $this->_getParam('a', $this->getRequest()->getActionName());
        $params = array('format' => 'json');


// echo PHP_EOL . PHP_EOL . '<pre style="text-align: left">';
// var_dump($this->_getAllParams());
// echo '</pre>' . PHP_EOL . PHP_EOL;
// echo PHP_EOL . PHP_EOL . '<pre style="text-align: left">';
// var_dump(array($module, $controller, $action));
// echo '</pre>' . PHP_EOL . PHP_EOL;

// die('<br />died at ' . __FILE__ . ', line ' . __LINE__);
        return $this->forward($action, $controller, $module, $params);
    }

}
