<?php namespace GSD\Entities;

// File: app/GSD/Entities/TaskCollection.php

class TaskCollection implements TaskCollectionInterface {

    protected $tasks;   // Array of TaskInterfaces

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = array();
    }

    /**
     * Add a new task to the collection
     * @param string|TaskInterface $task Either a TaskInterface or a string we can construct one from.
     * @throws InvalidArgumentException If $task not string or TaskInterface
     */
    public function add($task)
    {
        if ( ! ($task instanceof TaskInterface))
        {
            if ( ! is_string($task))
            {
                throw new \InvalidArgumentException('$task must be string or TaskInterface');
            }
            $newTask = \App::make('GSD\Entities\TaskInterface');
            if ( ! $newTask->setFromString($task))
            {
                throw new \InvalidArgumentException('Cannot parse task string');
            }
            $task = $newTask;
        }
        $this->tasks[] = $task;
        $this->sortTasks();
    }

    /**
     * Return task based on index
     * @param integer $index 0 is first item in collection
     * @return TaskInterface The Todo Task
     * @throws OutOfBoundsException If $index outside range
     */
    public function get($index)
    {
        $this->sortTasks();
        if ($index < 0 || $index >= count($this->tasks))
        {
            throw new \OutOfBoundsException('$index is outside range');
        }
        return $this->tasks[$index];
    }

    /**
     * Return array containing all tasks
     * @return array
     */
    public function getAll()
    {
        $this->sortTasks();
        return $this->tasks;
    }

    /**
     * Remove the specified task
     * @param integer $index The task to remove
     * @throws OutOfBoundsException If $index outside range
     */
    public function remove($index)
    {
        if ($index < 0 || $index >= count($this->tasks))
        {
            throw new \OutOfBoundsException('$index is outside range');
        }
        unset($this->tasks[$index]);
        $this->sortTasks();
    }

    /**
     * Sort the tasks where:
     *  1) Next actions are alphebetically first
     *  2) Normal tasks are alphebetically next
     *  3) Completed tasks are sorted by date completed, descending
     */
    protected function sortTasks()
    {
        $next = array();
        $normal = array();
        $completed = array();
        foreach ($this->tasks as $task)
        {
            if ($task->isComplete())
            {
                $completed[] = $task;
            }
            elseif ($task->isNextAction())
            {
                $next[] = $task;
            }
            else
            {
                $normal[] = $task;
            }
        }
        usort($next, 'static::cmpDescription');
        usort($normal, 'static::cmpDescription');
        usort($completed, 'static::cmpCompleted');
        $this->tasks = array_merge($next, $normal, $completed);
    }

    /**
     * Compare two tasks by description
     */
    public static function cmpDescription($a, $b)
    {
        return strnatcmp($a->description(), $b->description());
    }

    /**
     * Compare two tasks by completion date
     */
    public static function cmpCompleted($a, $b)
    {
        $stamp1 = $a->dateCompleted()->timestamp;
        $stamp2 = $b->dateCompleted()->timestamp;
        if ($stamp1 == $stamp2)
        {
            return strnatcmp($a->description(), $b->description());
        }
        return $stamp1 - $stamp2;
    }
}
