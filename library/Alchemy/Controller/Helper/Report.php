<?php
namespace Alchemy\Controller\Helper;

class Report extends \Zend_Controller_Action_Helper_Abstract
{
    public function preDispatch()
    {
        $this->displayOrSaveAgainMessages();
    }

    public function postDispatch()
    {
        $this->saveMessagesIfRedirect();
    }

    private function saveMessagesIfRedirect()
    {
        if (!$this->getResponse()->isRedirect()) {
            return;
        }

        if (!$messages = $this->_actionController->view->actionMessages) {
            return;
        }

        \Zend_Controller_Action_HelperBroker::getStaticHelper('report')->addFlashMessages($messages);
    }

    private function displayOrSaveAgainMessages()
    {
        $flashMessanger = \Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        $messages = $flashMessanger->getMessages();

        if ($this->getResponse()->isRedirect()) {
            \Zend_Controller_Action_HelperBroker::getStaticHelper('report')->addFlashMessages($messages);
        } else {
            $this->_actionController->view->actionMessages = is_array($messages) ? $messages : array();
        }
    }

    public function direct($message, $messageType, $isFlashMessage = false)
    {
        $this->addMessage($message, $messageType, $isFlashMessage);
    }

    /**
     * Set report message
     *
     * @param	string message
     * @param integer $reportType
     */
    public function addMessage($message, $messageType, $isFlashMessage = false)
    {
        $messageArray = array(
            'txt' => $message,
            'type' => $messageType
        );

        if ($isFlashMessage) {
            $this->addFlashMessages(
                array(
                    $messageArray
                )
            );

            return;
        }

        $current = $this->_actionController->view->actionMessages;
        $current[] = $messageArray;
        $this->_actionController->view->actionMessages = $current;
    }

    /**
     * @param array $messages
     */
    public function addFlashMessages(array $messages)
    {
        foreach ($messages as $message) {
            \Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->addMessage($message);
        }
    }
}
