<?php namespace GSD\Repositories;

// File: app/src/GSD/Repositories/TodoRepositoryInterface.php

use GSD\Entities\ListInterface;

interface TodoRepositoryInterface {

    /**
     * Does the todo list exist?
     * @param string $id ID of the list
     * @return boolean
     */
    public function exists($id);

    /**
     * Load a TodoList form it's id
     * @param  string $id ID of the list
     * @return ListInterface The List
     * @throws  InvalidArgumentException If $id not found
     */
    public function load($id);

    /**
     * Save a TodoList
     * @param string $id ID of the list
     * @param ListInterface $list The TODO List
     */
    public function save($id, ListInterface $list);
}
