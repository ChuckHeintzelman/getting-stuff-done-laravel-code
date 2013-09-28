<?php
// File: app/tests/GSD/Providers/TodoManagerTest.php

use GSD\Providers\TodoManager;

class TodoManagerTest extends TestCase {

    public function tearDown()
    {
        $this->refreshApplication();
    }

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
        App::bind('GSD\Repositories\TodoRepositoryInterface', function()
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
        App::bind('GSD\Repositories\TodoRepositoryInterface', function()
        {
            $mock = Mockery::mock('GSD\Repositories\TodoRepositoryInterface');
            $mock->shouldReceive('exists')
                 ->once()
                 ->andReturn(false);
            return $mock;
        });

        // Mock the list object
        App::bind('GSD\Entities\ListInterface', function()
        {
            $mock = Mockery::mock('GSD\Entities\ListInterface');
            $mock->shouldReceive('set')->twice()->andReturn($mock, $mock);
            $mock->shouldReceive('save')->once()->andReturn($mock);
            return $mock;
        });

        $list = Todo::makeList('abc', 'test abc');
        $this->assertInstanceOf('GSD\Entities\ListInterface', $list);
    }

    public function testAllListsReturnsArray()
    {
        // Mock the repository
        App::bind('GSD\Repositories\TodoRepositoryInterface', function()
        {
            $mock = Mockery::mock('GSD\Repositories\TodoRepositoryInterface');
            $mock->shouldReceive('getAll')
                 ->once()
                 ->andReturn(array());
            return $mock;
        });

        $result = Todo::allLists();
        $this->assertInternalType('array', $result);
    }

    public function testAllArchivedListsReturnsArray()
    {
        // Mock the repository
        App::bind('GSD\Repositories\TodoRepositoryInterface', function()
        {
            $mock = Mockery::mock('GSD\Repositories\TodoRepositoryInterface');
            $mock->shouldReceive('getAll')
                 ->once()
                 ->andReturn(array());
            return $mock;
        });

        $result = Todo::allLists(true);
        $this->assertInternalType('array', $result);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetListThrowsExceptionWhenMissing()
    {
        // Mock the repository
        App::bind('GSD\Repositories\TodoRepositoryInterface', function()
        {
            $mock = Mockery::mock('GSD\Repositories\TodoRepositoryInterface');
            $mock->shouldReceive('exists')
                 ->once()
                 ->andReturn(false);
            return $mock;
        });

        // Should throw an error
        $list = Todo::get('abc');
    }

    public function testGetListReturnsCorrectType()
    {
        // Mock the repository
        App::bind('GSD\Repositories\TodoRepositoryInterface', function()
        {
            $list = Mockery::mock('GSD\Entities\ListInterface');
            $mock = Mockery::mock('GSD\Repositories\TodoRepositoryInterface');
            $mock->shouldReceive('exists')->once()->andReturn(true);
            $mock->shouldReceive('load')->once()->andReturn($list);
            return $mock;
        });

        // Should throw an error
        $list = Todo::get('abc');
        $this->assertInstanceOf('GSD\Entities\ListInterface', $list);
    }
}
