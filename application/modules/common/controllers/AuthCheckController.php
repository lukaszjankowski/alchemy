<?php
/**
 * Authorization data verification
 *
 * @author Łukasz Jankowski <mail@lukaszjankowski.info>
 */
class Common_AuthCheckController extends Alchemy\Controller\Action
{
    public function init()
    {
        parent::init();
        $this->_helper->contextSwitch()->addActionContext('index', array(
            'json'
        ))->initContext('json');
    }

    public function indexAction()
    {
        throw new Exception('test');
    }

}
