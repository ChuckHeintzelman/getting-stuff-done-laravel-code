<?php namespace GSD\Entities;

// File: app/src/GSD/Entities/ListInterface.php

interface ListInterface {

    // List attributes -------------------------

    /**
     * Return the id
     */
    public function id();

    /**
     * Is the list archived?
     * @return bool
     */
    public function isArchived();

    /**
     * Is the list dirty?
     * @return bool
     */
    public function isDirty();

    /**
     * Return a list attribute
     * @param string $name id|isArchived|isDirty|title
     * @return mixed
     * @throws InvalidArgumentException If $name is invalid
     */
    public function get($name);

    /**
     * Set a list attribute
     * @param string $name id|isArchived|isDirty|title
     * @param mixed $value Attribute value
     * @return ListInterface for method chaining
     * @throws InvalidArgumentException If $name is invalid
     */
    public function set($name, $value);

    /**
     * Return the title (alias for get('title'))
     */
    public function title();

    // List operations -------------------------

    /**
     * Archive the list. This makes the list only available from the archive.
     * @return ListInterface For method chaining
     */
    public function archive();

    /**
     * Save the task list
     * @return ListInterface for method chaining
     */
    public function save();

    // Task operations -------------------------

    /**
     * Add a new task to the collection
     * @param string|TaskInterface $task Either a TaskInterface or a string we can construct one from.
     * @return ListInterface for method chaining
     */
    public function taskAdd($task);

    /**
     * Return number of tasks
     * @return integer
     */
    public function taskCount();

    /**
     * Return a task
     * @param integer $index Task index #
     * @return TaskInterface
     * @throws OutOfBoundsException If $index outside range
     */
    public function task($index);

    /**
     * Return a task attribute
     * @param integer $index Task index #
     * @param string $name Attribute name
     * @return mixed
     * @throws OutOfBoundsException If $index outside range
     */
    public function taskGet($index, $name);

    /**
     * Return all tasks as an array.
     * @return array All the TaskInterface objects
     */
    public function tasks();

    /**
     * Set a task attribute
     * @param integer $index Task index #
     * @param string $name Attribute name
     * @param mixed $value Attribute value
     * @return ListInterface for method chaining
     * @throws OutOfBoundsException If $index outside range
     */
    public function taskSet($index, $name, $value);

    /**
     * Remove the specified task
     * @throws OutOfBoundsException If $index outside range
     */
    public function taskRemove($index);
}
