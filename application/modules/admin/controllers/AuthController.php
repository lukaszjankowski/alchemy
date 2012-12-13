<?php
use Alchemy\Form\LoginForm;

class Admin_AuthController extends Alchemy\Controller\Action
{
    public function loginAction()
    {
        if(Zend_Auth::getInstance()->hasIdentity())
        {
            return $this->_redirect('/admin/index');
        }

        $loginForm = new LoginForm;
        $this->view->loginForm = $loginForm;

        if(!$this->_request->isPost())
        {
            return;
        }

        return $this->loginOnPost($loginForm);
    }

    private function loginOnPost(LoginForm $loginForm)
    {
        $formData = $this->_request->getPost();

        if(!$loginForm->isValid($formData))
        {
            $loginForm->populate($formData);
            return;
        }

        return $this->loginUserWithValidData($loginForm->getValue('username'), $loginForm->getValue('password'));
    }

    private function loginUserWithValidData($username, $password)
    {
        $model = $this->getModel('User');
        if(false === $model->login($username, $password))
        {
            $this->setModelErrors($model->getError());
            return;
        }

        if(false === $model->getResult())
        {
            $this->_helper->report('Authentication failed!', self::REPORT_ERROR);
            return;
        }

        $this->_helper->report('Successful login', self::REPORT_INFO, true);
        return $this->_redirect('/admin/index');
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->report('Successful logout', self::REPORT_INFO, true);
        return $this->_redirect('/admin/index');
    }

}
