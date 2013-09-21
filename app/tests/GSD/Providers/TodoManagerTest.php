<?php
// File: app/tests/GSD/Providers/TodoManagerTest.php

use GSD\Providers\TodoManager;

class TodoManagerTest extends TestCase {

    public function testImATeapot()
    {
        $obj = new TodoManager;
        $this->assertEquals($obj->imATeapot(), "I'm a teapot.");
    }

    public function testFacade()
    {
        $this->assertEquals(Todo::imATeapot(), "I'm a teapot.");
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMakeListThrowsExceptionWhenExists()
    {
        // Mock the repository
        App::bind('TodoRepositoryInterface', function()
        {
            $mock = Mockery::mock('GSD\Repositories\TodoRepositoryInterface');
            $mock->shouldReceive('exists')
                 ->once()
                 ->andReturn(true);
            return $mock;
        });

        // Should throw an error
        Todo::makeList('abc', 'test abc');
    }

    public function testMakeList()
    {
        // Mock the repository
        App::bind('TodoRepositoryInterface', function()
        {
            $mock = Mockery::mock('GSD\Repositories\TodoRepositoryInterface');
            $mock->shouldReceive('exists')
                 ->once()
                 ->andReturn(false);
            return $mock;
        });

        // Mock the list object
        App::bind('ListInterface', function()
        {
            $mock = Mockery::mock('GSD\Entities\ListInterface');
            $mock->shouldReceive('set')->twice()->andReturn($mock, $mock);
            $mock->shouldReceive('save')->once()->andReturn($mock);
            return $mock;
        });

        $list = Todo::makeList('abc', 'test abc');
        $this->assertInstanceOf('GSD\Entities\ListInterface', $list);
    }

}
