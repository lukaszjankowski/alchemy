<?php
namespace Alchemy\Controller;

use \Zend_Controller_Action_Helper_ContextSwitch as ContextSwitch;
use \Alchemy\Model\Factory;
use \Alchemy\Exception;

abstract class Action extends \Zend_Controller_Action implements Report
{
    /**
     * Overriden to avoid exits
     *
     * @see Zend_Controller_Action::_redirect()
     */
    protected function _redirect($url, array $options = array())
    {
        $options['exit'] = false;

        return parent::_redirect($url, $options);
    }

    /**
     * @param  string               $modelName
     * @return \Alchemy\ModelFacade
     */
    public function getModel($modelName)
    {
        return Factory::getInstance()->getModel($modelName);
    }

    /**
     * @param array $error
     */
    public function setModelError(array $error)
    {
        if ($this->isJsonContext()) {
            throw new Exception($error['message']);
        }

        $this->_helper->report->addMessage($error['message'], self::REPORT_ERROR);
    }

    /**
     * @return boolean
     */
    private function isJsonContext()
    {
        return 'json' == $this->_helper->contextSwitch->getCurrentContext();
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
            ->setCallback(
                'json',
                ContextSwitch::TRIGGER_POST,
                array(
                    $this->_helper->jsonContext(),
                    'postJsonContext'
                )
            );
    }
}
