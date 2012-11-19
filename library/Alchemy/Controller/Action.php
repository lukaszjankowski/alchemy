<?php
namespace Alchemy\Controller;

abstract class Action extends \Zend_Controller_Action implements Report
{
    protected function _redirect($url, array $options = array())
    {
        $options['exit'] = false;
        return parent::_redirect($url, $options);
    }

    /**
     * @param	string $modelName
     * @return	Alchemy\ModelFacade
     */
    public function getModel($modelName)
    {
        $className = "Alchemy\\ModelFacade\\$modelName";
        \Zend_Loader::loadClass($className);

        return new $className;
    }

    /**
     * @param    array $errors
     */
    public function setModelErrors(array $errors)
    {
        foreach($errors as $error)
        {
            $this->_helper->report->addMessage($error['message'], self::REPORT_ERROR);
        }
    }

}
