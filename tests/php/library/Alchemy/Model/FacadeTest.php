<?php
/**
 * Tests for model facade
 *
 * @author Łukasz Jankowski <mail@lukaszjankowski.info>
 */
namespace AlchemyTest\Model\Facade;
use \Alchemy\Model\Facade;
use \Alchemy\Model\Exception as Exception;

class ModelFacadeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Alchemy\ModelFacade
     */
    private $facade;

    /**
     * @var \Alchemy\Model
     */
    private $model;

    public function setUp()
    {
        $this->model = $this->getMock('\\Alchemy\\Model\\Service\\User');
        $this->facade = new Facade($this->model);
    }

    public function testThrowsExceptionWhenUnexistingModelMethodCalled()
    {
        try
        {
            $this->facade->foo();
            $this->fail('Expected to throw an exception');
        }
        catch(\Exception $e)
        {
            $this->assertInstanceOf('Exception', $e);
            $this->assertContains('Unknown method', $e->getMessage());
        }
    }

    public function testErrorsAndResultAreInitiallyEmpty()
    {
        $this->assertEquals(null, $this->facade->getResult());
        $this->assertEquals(array(), $this->facade->getError());
    }

    public function testCallsModelMethodWithArgsAndReturnsResult()
    {
        $username = 'foo';
        $password = 'bar';
        $result = true;
        $this->model->expects($this->once())->method('login')->with($username, $password)
            ->will($this->returnValue($result));

        $this->facade->login($username, $password);
        $this->assertEquals($result, $this->facade->getResult());
    }

    public function testReturnsErrorWhenModelThrowsException()
    {
        $username = 'foo';
        $password = 'bar';
        $this->model->expects($this->once())->method('login')
            ->will($this->throwException(new Exception('exception message')));

        $this->facade->login($username, $password);
        $this->assertEquals(null, $this->facade->getResult());
        $this->assertEquals(array(
                'message' => 'exception message'
            ), $this->facade->getError());
    }

}
