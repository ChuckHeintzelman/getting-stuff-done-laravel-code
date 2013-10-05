<?php
// File: app/tests/GSD/Repositories/TodoRepositoryTest.php

class TodoRepositoryTest extends TestCase {

    protected $repo;

    // Deletes any existing lists and copies the blank ones over
    public function setup()
    {
        $path = __DIR__.'/data/';
        $ext = Config::get('todo.extension');
        $files = array_merge(
            glob($path.'*'.$ext),
            glob($path.'archived/*'.$ext)
        );
        foreach ($files as $file)
        {
            unlink($file);
        }
        copy($path.'test1.test', $path.'test1'.$ext);
        copy($path.'test2.test', $path.'test2'.$ext);

        $this->repo = App::make('GSD\Repositories\TodoRepositoryInterface');
    }

    public function testDelete()
    {
        $result = $this->repo->delete('test1');
        $this->assertTrue($result);
    }

    public function testDeleteArchived()
    {
        // Save test1 as archived
        $list = $this->repo->load('test1');
        $list->set('archived', true);
        $list->set('id', 'test-archived');
        $list->save();

        // Delete it
        $result = $this->repo->delete('test-archived', true);
        $this->assertTrue($result);
    }

    public function testDeleteMissingReturnsFalse()
    {
        $result = $this->repo->delete('bogus');
        $this->assertFalse($result);
    }

    public function testExists()
    {
        $this->assertTrue($this->repo->exists('test2'));
        $this->assertFalse($this->repo->exists('bogus'));
    }

    public function testExistsArchived()
    {
        // Save test1 as archived
        $list = $this->repo->load('test1');
        $list->set('archived', true);
        $list->set('id', 'test-archived');
        $list->save();

        $this->assertTrue($this->repo->exists('test-archived', true));
        $this->assertFalse($this->repo->exists('something-else', true));
    }

    public function testGetAll()
    {
        $result = $this->repo->getAll();
        $this->assertCount(2, $result);
        $result = $this->repo->getAll(true);
        $this->assertSame(array(), $result);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testLoadMissingThrowsException()
    {
        $this->repo->load('bogus');
    }

    public function testLoadedComponents()
    {
        $list = $this->repo->load('test1');
        $this->assertEquals('Test List 1', $list->get('title'));
        $this->assertNull($list->get('subtitle'));
        $this->assertEquals('Something simple', $list->taskGet(0, 'description'));
        $this->assertEquals(
            '2013-09-13',
            $list->taskGet(1, 'dateCompleted')->format('Y-m-d')
        );
    }

    public function testSaves()
    {
        $list = $this->repo->load('test1');
        $list->set('id', 'test-one');
        $list->save();
        $file1 = trim(file_get_contents(__DIR__.'/data/test1.txt'));
        $file2 = trim(file_get_contents(__DIR__.'/data/test-one.txt'));
        $this->assertEquals($file1, $file2);

        $list = $this->repo->load('test2');
        $list->set('archived', true);
        $list->save();
        $file1 = trim(file_get_contents(__DIR__.'/data/test2.txt'));
        $file2 = trim(file_get_contents(__DIR__.'/data/archived/test2.txt'));
        $this->assertEquals($file1, $file2);

    }
}