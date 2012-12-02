<?php
/**
 * Extended implementation of Zend_Controller_Action_Helper_ContextSwitch::postJsonContext()
 *
 * @author Łukasz Jankowski <mail@lukaszjankowski.info>
 */
namespace Alchemy\Controller\Helper;
class JsonContext extends \Zend_Controller_Action_Helper_Abstract
{
    /**
     * Strategy pattern: return object
     *
     * @return Zend_Controller_Action_Helper_ContextSwitch Provides a fluent interface
     */
    public function direct()
    {
        return $this;
    }

    /**
     * JSON post processing
     *
     * JSON serialize view variables to response body
     *
     * @return void
     */
    public function postJsonContext()
    {
        if(!$this->_actionController->getHelper('contextSwitch')->getAutoJsonSerialization())
        {
            return;
        }

        $viewRenderer = \Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $view = $viewRenderer->view;

        if($view instanceof \Zend_View_Interface)
        {
            /**
             * @see \Zend_Json
             */
            if(method_exists($view, 'getVars'))
            {
                $response = null;
                $exception = null;

                if($this->getResponse()->isException())
                {
                    $exception = $view->message;
                }
                else
                {
                    $response = $view->getVars();
                }

                $body = array(
                    'error' => $exception,
                    'result' => $response
                );

                $this->getResponse()->setBody(\Zend_Json_Encoder::encode($body));
            }
            else
            {
                require_once 'Zend/Controller/Action/Exception.php';
                throw new \Zend_Controller_Action_Exception(
                    'View does not implement the getVars() method needed to encode the view into JSON');
            }
        }
    }

}
