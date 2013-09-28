<?php
// File: app/tests/GSD/Entities/TodoListTest.php

class TodoListTest extends TestCase {

    public function tearDown()
    {
        $this->refreshApplication();
    }
    public function setup()
    {
        App::bind('GSD\Repositories\TodoRepositoryInterface', function()
        {
            return Mockery::mock('GSD\Repositories\TodoRepositoryInterface');
        });
        App::bind('GSD\Entities\TaskCollectionInterface', function()
        {
            return Mockery::mock('GSD\Entities\TaskCollectionInterface');
        });
    }

    public function testBoundToInterface()
    {
        $obj = App::make('GSD\Entities\ListInterface');
        $this->assertInstanceOf('GSD\Entities\TodoList', $obj);
    }

    /**
     * @expectedException InvalidArgumentException
    */
    public function testGetInvalidNameThrowsException()
    {
        $list = App::make('GSD\Entities\TodoList');
        $list->get('bogus');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetInvalidNameThrowsException()
    {
        $list = App::make('GSD\Entities\TodoList');
        $list->set('bogus', true);
    }

    public function testGetSetWorks()
    {
        $list = App::make('GSD\Entities\TodoList');
        $result = $list->set('id', 'abc');
        $this->assertSame($list, $result);
        $result = $list->get('id');
        $this->assertEquals($result, 'abc');
    }

    public function testSaveNotDirtyDoesNothing()
    {
        $list = App::make('GSD\Entities\TodoList');
        //$list->set('id', '123');
        $result = $list->save();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Cannot save if id not set
     */
    public function testSaveNoIdThrowsException()
    {
        $list = App::make('GSD\Entities\TodoList');
        $list->set('title', 'My Title');
        $list->save();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Repository could not save
     */
    public function testSaveThrowsExceptionIfRepoFails()
    {
        App::bind('GSD\Repositories\TodoRepositoryInterface', function()
        {
            $mock = Mockery::mock('GSD\Repositories\TodoRepositoryInterface');
            $mock->shouldReceive('save')->once()->andReturn(false);
            return $mock;
        });
        $list = App::make('GSD\Entities\TodoList');
        $list->set('id', 'listname');
        $list->save();
    }

    public function testSaveWorksAsExpected()
    {
        App::bind('GSD\Repositories\TodoRepositoryInterface', function()
        {
            $mock = Mockery::mock('GSD\Repositories\TodoRepositoryInterface');
            $mock->shouldReceive('save')->once()->andReturn(true);
            return $mock;
        });
        $list = App::make('GSD\Entities\TodoList');
        $list->set('id', 'listname');
        $result = $list->save();
        $this->assertSame($list, $result);
    }

    public function testArchiveWhenAlreadyArchivedDoesNothing()
    {
        $list = App::make('GSD\Entities\TodoList');
        $list->set('archived', true);
        $list->archive();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Cannot archive if id not set
     */
    public function testArchiveWithNoIdThrowsException()
    {
        $list = App::make('GSD\Entities\TodoList');
        $list->archive();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Repository failed deleting unarchived list
     */
    public function testArchiveWhenRepoFailsOnDelete()
    {
        App::bind('GSD\Repositories\TodoRepositoryInterface', function()
        {
            $mock = Mockery::mock('GSD\Repositories\TodoRepositoryInterface');
            $mock->shouldReceive('exists')->once()->andReturn(true);
            $mock->shouldReceive('delete')->once()->andReturn(false);
            return $mock;
        });
        $list = App::make('GSD\Entities\TodoList');
        $list->set('id', 'actions');
        $list->archive();
    }
}