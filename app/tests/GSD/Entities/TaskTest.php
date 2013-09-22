<?php
// File: app/tests/GSD/Entities/TaskTest.php

class TaskTest extends TestCase {

    protected function newTask()
    {
        return App::make('GSD\Entities\TaskInterface');
    }

    public function testGetters()
    {
        $task = $this->newTask();

        // Use specific getters
        $this->assertFalse($task->isComplete());
        $this->assertEquals('', $task->description());
        $this->assertNull($task->dateDue());
        $this->assertNull($task->dateCompleted());
        $this->assertFalse($task->isNextAction());

        // Use generic getter
        $this->assertFalse($task->get('isComplete'));
        $this->assertEquals('', $task->get('description'));
        $this->assertNull($task->get('dateDue'));
        $this->assertNull($task->get('dateCompleted'));
        $this->assertFalse($task->get('isNextAction'));
    }

    public function testSettingCompleteUpdatesWhenComplete()
    {
        $task = $this->newTask();

        $task->setIsComplete(true);
        $this->assertInstanceOf('Carbon\Carbon', $task->dateCompleted());
        $this->assertEquals(date('Y-m-d'), $task->dateCompleted()->format('Y-m-d'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetDueThrowsException()
    {
        $task = $this->newTask();
        $task->setDateDue(123);
    }

    public function testOtherSetters()
    {
        $task = $this->newTask();

        $test1 = 'Test description';
        $test2 = 'Another test';
        $task->setDescription($test1);
        $this->assertEquals($test1, $task->description());
        $task->set('description', $test2);
        $this->assertEquals($test2, $task->description());

        $test1 = new Carbon\Carbon('1/1/2013');
        $task->setDateDue($test1);
        $this->assertEquals($test1, $task->dateDue());
        $task->set('dateDue', null);
        $this->assertNull($task->dateDue());

        $task->setIsNextAction(true);
        $this->assertTrue($task->isNextAction());
        $task->set('isNextAction', false);
        $this->assertFalse($task->isNextAction());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetWithBadNameThrowsError()
    {
        $task = $this->newTask();

        $task->get('something');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetWithBadNameThrowsError()
    {
        $task = $this->newTask();

        $task->set('something', 'bla');
    }

    /**
     * @dataProvider stringTests
     */
    public function testStringVariations($string, $valid, $stringSame)
    {
        $task = $this->newTask();

        $result = $task->setFromString($string);
        if ($valid)
        {
            $this->assertTrue($result);
            if ($stringSame)
            {
                $this->assertEquals($string, (string)$task);
            }
            else
            {
                $this->assertNotEquals($string, (string)$task);
            }
        }
        else
        {
            $this->assertFalse($result);
        }
    }
    public function stringTests()
    {
        return array(
            array('', false, false),
            array('* Simple next action', true, true),
            array('* Next with due date :due:2013-09-14', true, true),
            array('- task with  an extra space', true, false),
            array('x bad', false, false),
            array('- Due date :due:2013-09-14 in middle', true, false),
            array('x 2013-08-03 Start Laravel Book: Getting Stuff Done', true, true),
        );
    }
}
