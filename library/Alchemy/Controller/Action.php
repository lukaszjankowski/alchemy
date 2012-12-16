<?php
namespace Alchemy\Controller;
use \Zend_Controller_Action_Helper_ContextSwitch as ContextSwitch;
use \Alchemy\Model\Factory;

abstract class Action extends \Zend_Controller_Action implements Report
{
    protected function _redirect($url, array $options = array())
    {
        $options['exit'] = false;
        return parent::_redirect($url, $options);
    }

    /**
     * @param string $modelName
     * @return \Alchemy\ModelFacade
     */
    public function getModel($modelName)
    {
        return Factory::getInstance()->getModel($modelName);
    }

    /**
     * @param array $errors
     */
    public function setModelErrors(array $errors)
    {
        $this->_helper->report->addMessage($errors['message'], self::REPORT_ERROR);
    }

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
    public function init()
    {
        $this->_helper->contextSwitch()
            ->setCallback('json', ContextSwitch::TRIGGER_POST,
                array(
                    $this->_helper->jsonContext(),
                    'postJsonContext'
                ));
    }

}
