<?php
namespace Alchemy\Form;

/**
 * Formularz logowania
 *
 * @author     Lukasz Jankowski <mail@lukaszjankowski.info>
 * @package
 * @subpackage
 */
class LoginForm extends \Zend_Form
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('id', 'loginForm');

        $username = $this->createElement('text', 'username');
        $username->setLabel('Username: ')->setRequired(true)->addFilter('StringToLower')->addFilter('StringTrim')
            ->addValidators(
                array(
                    'Alpha',
                    array(
                        'StringLength',
                        false,
                        array(
                            3,
                            20
                        )
                    )
                ));
        $password = $this->createElement('password', 'password');
        $password->setLabel('Password: ')->setRequired(true)
            ->addValidators(
                array(
                    'Alnum',
                    array(
                        'StringLength',
                        false,
                        array(
                            6,
                            20
                        )
                    )
                ));
        $submit = $this->createElement('submit', 'Login');
        $submit->setLabel('Login')->setIgnore(true);
        $this
            ->addElements(
                array(
                    $username,
                    $password,
                    $submit
                ));
        $this
            ->setDecorators(
                array(
                    'FormElements',
                    array(
                        'HtmlTag',
                        array(
                            'tag' => 'dl'
                        )
                    ),
                    'Form'
                ));
    }
}
