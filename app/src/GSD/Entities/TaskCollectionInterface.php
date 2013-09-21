<?php namespace GSD\Entities;

// File: app/GSD/Entities/TaskCollectionInterface.php

interface TaskCollectionInterface {

    /**
     * Add a new task to the collection
     * @param TodoTaskInterface $task
     */
    public function add(TodoTaskInterface $task);

    /**
     * Return task based on index
     * @param integer $index 0 is first item in collection
     * @return TodoTaskInterface The Todo Task
     * @throws OutOfBoundsException If $index outside range
     */
    public function get($index);

    /**
     * Return array containing all tasks
     * @return array
     */
    public function getAll();

    /**
     * Remove the specified task
     * @param integer $index The task to remove
     * @throws OutOfBoundsException If $index outside range
     */
    public function remove($index);
}
