<?php namespace GSD\Repositories;

use GSD\Entities\ListInterface;

interface TodoRepositoryInterface {

    /**
     * Does the todo list exist?
     * @param  string $id ID of the list
     * @param  boolean $archived Check for archived lists only?
     * @return boolean
     */
    public function exists($id, $archived = false);

    /**
     * Return the ids of all the lists
     * @param  boolean $archived Return archived ids or unarchived?
     * @return array of list ids
     */
    public function getAll($archived = false);

    /**
     * Load a TodoList from it's id
     * @param string $id ID of the list
     * @param boolean $archived Load an archived list?
     * @return ListInterface The list
     * @throws InvalidArgumentException If $id not found
     */
    public function load($id, $archived = false);

    /**
     * Save a TodoList
     * @param string $id ID of the list
     * @param boolean $archived Save an archived list?
     * @param ListInterface $list The TODO List
     */
    public function save($id, ListInterface $list, $archived = false);
}
