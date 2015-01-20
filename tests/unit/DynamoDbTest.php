<?php

use Aws\DynamoDb\Session\SessionHandler;
use jambroo\aws\session\saveHandler\DynamoDb as DynamoDbSaveHandler;

class DynamoDbTest extends \Codeception\TestCase\Test
{
    /**
     * @var SessionHandler
     */
    protected $sessionHandler;

    /**
     * @var DynamoDbSaveHandler
     */
    protected $saveHandler;

    public function setUp()
    {
        parent::setUp();

        $this->sessionHandler = $this->getMock(
            'Aws\DynamoDb\Session\SessionHandler',
            array(
                'open',
                'close',
                'read',
                'write',
                'destroy',
                'gc',
            ),
            array(),
            '',
            false
        );

        $this->saveHandler = new DynamoDbSaveHandler($this->sessionHandler);
    }

    public function testSessionHandlerOpenIsCalled()
    {
        $this->sessionHandler->expects($this->once())
            ->method('open')
            ->with($this->equalTo('mypath'), $this->equalTo('myname'))
            ->will($this->returnValue(true));

        $result = $this->saveHandler->open('mypath', 'myname');

        $this->assertTrue($result);
    }

    public function testSessionHandlerCloseIsCalled()
    {
        $this->sessionHandler->expects($this->once())
            ->method('close')
            ->with()
            ->will($this->returnValue(true));

        $result = $this->saveHandler->close();

        $this->assertTrue($result);
    }

    public function testSessionHandlerReadIsCalled()
    {
        $this->sessionHandler->expects($this->once())
            ->method('read')
            ->with($this->equalTo('myid'))
            ->will($this->returnValue('mydata'));

        $result = $this->saveHandler->read('myid');

        $this->assertEquals('mydata', $result);
    }

    public function testSessionHandlerWriteIsCalled()
    {
        $this->sessionHandler->expects($this->once())
            ->method('write')
            ->with($this->equalTo('myid'), $this->equalTo('mydata'))
            ->will($this->returnValue(true));

        $result = $this->saveHandler->write('myid', 'mydata');

        $this->assertTrue($result);
    }

    public function testSessionHandlerDestroyIsCalled()
    {
        $this->sessionHandler->expects($this->once())
            ->method('destroy')
            ->with($this->equalTo('myid'))
            ->will($this->returnValue(true));

        $result = $this->saveHandler->destroy('myid');

        $this->assertTrue($result);
    }

    public function testSessionHandlerGcIsCalled()
    {
        $this->sessionHandler->expects($this->once())
            ->method('gc')
            ->with($this->equalTo(420))
            ->will($this->returnValue(true));

        $result = $this->saveHandler->gc(420);

        $this->assertTrue($result);
    }
}