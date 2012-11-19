<?php
namespace Alchemy;

abstract class Model
{
    /**
     * @return \Zend_Db_Adapter_Abstract
     */
    protected function getDb()
    {
        return \Zend_Db_Table::getDefaultAdapter();
    }

}