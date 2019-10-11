<?php
namespace Magestore\HelloMagento\Test\Unit\Model;
use Magestore\HelloMagento\HelloMessage;

class HelloMessageText extends \PHPUnit\Framework\TestCase
{
    protected $_objectManager;
    protected $_model;
    

    protected function setUp()
    {
        $this->_objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_model = $this->_objectManager->getObject('Magestore\HelloMagento\Model\HelloMessage');
    }
    
    /**
     * this function tests the result of hello world string
     *
     */
    public function testGetMessage(){
        $result = $this->_model->getMessage();
        $expectedResult = 'Hello Magento 2 :)) blalala';
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * this function tests the result of the addtion of two numbers
     *
     */
    public function testAdd()
    {
        $result = $this->_model->Add(3.0, 4.0);
        $expectedResult = 7.0;
        $this->assertEquals($expectedResult, $result);
    }
}