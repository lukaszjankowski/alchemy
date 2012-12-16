<?php
/**
 * A model service that always throws exceptions (for testing purposes)
 *
 * @author Łukasz Jankowski <mail@lukaszjankowski.info>
 */
namespace Alchemy\Model\Service;
use Alchemy\Model\Exception;

use \Alchemy\Model;

class Error extends Model
{
    public function __call($method, array $args = array())
    {
        throw new Exception('A model exception was thrown from ' . __CLASS__);
    }

}
