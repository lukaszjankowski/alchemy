<?php
/**
 * Authorization data verification
 *
 * @author Łukasz Jankowski <mail@lukaszjankowski.info>
 */
use Alchemy\Form\LoginForm;

class Common_AuthCheckController extends Alchemy\Controller\Action
{
    const RESULT_OK = 'RESULT_OK';
    const RESULT_NOT_OK = 'RESULT_NOT_OK';

    public function init()
    {
        parent::init();
        $this->_helper->contextSwitch()->addActionContext('index', array(
                'json'
            ))->initContext('json');
    }

    public function indexAction()
    {
        if(!$this->validateParameters() || !$this->checkCredentials())
        {
            $this->view->result = self::RESULT_NOT_OK;
            return;
        }

        $this->view->result = self::RESULT_OK;
    }

    private function validateParameters()
    {
        $loginForm = new LoginForm;
        $formData = $this->_request->getPost();

        return $loginForm->isValid($formData);
    }

    private function checkCredentials()
    {
        $model = $this->getModel('User');
        $username = $this->_request->getPost('username');
        $password = $this->_request->getPost('password');

        if(false === $model->checkAuth($username, $password))
        {
            $this->setModelErrors($model->getErrors());
            return false;
        }

        if(false === $model->getResult())
        {
            return false;
        }

        return true;
    }

}
