<?php
namespace Alchemy\Auth\Adapter;

class DbTable extends \Zend_Auth_Adapter_DbTable
{
    /**
     * __construct() - Sets configuration options
     *
     * @param  \Zend_Db_Adapter_Abstract $zendDb              If null, default database adapter assumed
     * @param  string                    $tableName
     * @param  string                    $identityColumn
     * @param  string                    $credentialColumn
     * @param  string                    $credentialTreatment
     * @return void
     */
    public function __construct(
        \Zend_Db_Adapter_Abstract $zendDb = null,
        $tableName = null,
        $identityColumn = null,
        $credentialColumn = null,
        $credentialTreatment = null
    ) {
        parent::__construct($zendDb = null, 't_users', 'user_name', 'user_pass', 'MD5(CONCAT(?, pass_salt))');
    }
}
