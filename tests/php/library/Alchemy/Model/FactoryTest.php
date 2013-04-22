<?php
namespace AlchemyTest\Model;

use Alchemy\Model\Facade;
use \Alchemy\Model\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetInstanceCalledTwiceReturnsSameInstance()
    {
        $first = Factory::getInstance();
        $second = Factory::getInstance();

        $this->assertSame($first, $second);
    }

    public function testCreatesModelOnlyOnce()
    {
        $first = Factory::getInstance()->getModel('User');
        $this->assertInstanceOf('\\Alchemy\\Model\\Facade', $first);

        $second = Factory::getInstance()->getModel('User');
        $this->assertSame($first, $second);
    }

    public function testResetsModelInstances()
    {
        $first = Factory::getInstance()->getModel('User');
        $this->assertInstanceOf('\\Alchemy\\Model\\Facade', $first);
        Factory::getInstance()->reset();
        $second = Factory::getInstance()->getModel('User');
        $this->assertNotSame($first, $second);
    }

    public function testReturnsSameModelAsSetManually()
    {
        $model = $this->getMockBuilder('\\Alchemy\\Model\\Facade')->disableOriginalConstructor()->getMock();
        Factory::getInstance()->setModel('User', $model);
        $expected = $model;
        $actual = Factory::getInstance()->getModel('User');
        $this->assertSame($expected, $actual);
    }
}
