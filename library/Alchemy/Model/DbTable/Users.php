<?php
namespace Alchemy\Model\DbTable;

class Users extends \Zend_Db_Table
{
	/**
	 * The default table name
	 */
	protected $_name = 't_users';

	/**
	 * Primary key
	 */
	protected $_primary = 'user_id';

}