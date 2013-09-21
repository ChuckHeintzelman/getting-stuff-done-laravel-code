<?php namespace GSD\Respositories;

// File: app/src/GSD/Repositories/TodoRepositoryInterface.php

use GSD\Entities\TodoListInterface;

interface TodoRepositoryInterface {

    /**
     * Load a TodoList form it's id
     * @param  string $id ID of the list
     * @return TodoListInterface The List
     * @throws  InvalidArgumentException If $id not found
     */
    public function load($id);

    /**
     * Save a TodoList
     * @param string $id ID of the list
     * @param TodoListInterface $list The TODO List
     */
    public function save($id, TodoListInterface $list);
}
