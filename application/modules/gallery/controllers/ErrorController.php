<?php
/**
 * Error controller
 */
class ErrorController extends Alchemy\Controller\Action
{
    public function init()
    {
        if ('json' == $this->_helper->contextSwitch()->getCurrentContext()) {
            $this->_helper->contextSwitch()->addActionContext(
                'error',
                array(
                    'json'
                )
            )->initContext('json');
        }
    }

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->getResponse()->setHeader('Cache-Control', 'no-cache, no-store, max-age: 0', true);
                $this->getResponse()->setHeader('Pragma', 'no-cache', true);
                $this->getResponse()->setHeader('Expires', 'Thu, 10 Nov 1981 08:52:00 GMT', true);
                $this->view->message = 'Application error';
                break;
        }

        $this->view->showBacktrace = APPLICATION_PROCEDURE_PRODUCTION
            !== $this->getInvokeArg('bootstrap')->getEnvironment();
        $this->view->exception = $errors->exception;
        $this->view->request = $errors->request;
    }

    public function error500Action()
    {
        throw new Zend_Controller_Action_Exception('Internal server error');
    }

    public function error404Action()
    {
        throw new Zend_Controller_Action_Exception('Page not found', 404);
    }
}
