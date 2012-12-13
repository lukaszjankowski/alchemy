<?php
/**
 * User model tests
 *
 * @author Łukasz Jankowski <mail@lukaszjankowski.info>
 */
namespace AlchemyTest\Model;
use Alchemy\Model\User as UserModel;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Alchemy\Model\User
     */
    private $model;

    public function setUp()
    {
        $this->model = new UserModel;
    }

    public function testPerformsAuthenticationWithAuthAndAuthAdapter()
    {
        $username = 'foo';
        $password = 'bar';
        $authAdapter = $this->getMockBuilder('Alchemy\Auth\Adapter\Test')->getMock();
        $authAdapter->expects($this->once())->method('setIdentity')->with($this->equalTo($username));
        $authAdapter->expects($this->once())->method('setCredential')->with($this->equalTo($password));
        $this->model->setAuthAdapter($authAdapter);
        $auth = $this->getMockBuilder('\Zend_Auth')->disableOriginalConstructor()->getMock();
        $authResult = $this->getMockBuilder('\Zend_Auth_Result')->disableOriginalConstructor()->getMock();
        $authResult->expects($this->once())->method('isValid')->will($this->returnValue(true));
        $auth->expects($this->once())->method('authenticate')->will($this->returnValue($authResult));
        $this->model->setAuth($auth);

        $actual = $this->model->login($username, $password);

        $this->assertTrue($actual);
    }

}
