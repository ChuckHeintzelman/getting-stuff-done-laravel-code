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
}