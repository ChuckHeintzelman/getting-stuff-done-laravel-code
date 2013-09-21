<?php namespace GSD\Entities;

// File: app/src/GSD/Entities/TaskListInterface.php

interface TaskListInterface {

    /**
     * Loads the task list by id
     */
    public function load($id);

    /**
     * Save the task list
     */
    public function save();

    /**
     * Return the id
     */
    public function id();

    /**
     * Is the list dirty?
     */
    public function isDirty();

    /**
     * Return a list attribute
     */
    public function get($name);

    /**
     * Set a list attribute
     */
    public function set($name, $value);

    /**
     * Return the title (alias for get('title'))
     */
    public function title();

    /**
     * Add a new task to the collection
     * @param TodoTaskInterface $task
     */
    public function addTask(TodoTaskInterface $task);

    /**
     * Return a task
     */
    public function getTask($index);

    /**
     * Return all tasks as an array
     */
    public function allTasks();

    /**
     * Remove the specified task
     */
    public function removeTask($index);
}
