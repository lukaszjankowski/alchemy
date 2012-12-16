<?php
namespace Alchemy;
use \Alchemy\Model\Exception as ModelException;

abstract class Model
{
    /**
     * @return \Zend_Db_Adapter_Abstract
     */
    protected function getDb()
    {
        return \Zend_Db_Table::getDefaultAdapter();
    }

    /**
     * @param string $method
     * @param array $args
     * @throws ModelException
     */
    public function __call($method, array $args = array())
    {
        throw new ModelException("Unknown method '" . get_class($this) . "::$method()'");
    }

}