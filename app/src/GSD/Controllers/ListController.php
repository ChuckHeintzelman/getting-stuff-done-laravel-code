<?php namespace GSD\Controllers;

use Response;
use Todo;

class ListController extends \Controller {

    /**
     * Returns a list of lists
     *
     * @return Response
     */
    public function index()
    {
        return Response::json(array('error' => 'index not done'));
    }

    /**
     * Create a new list
     */
    public function store()
    {
        return Response::json(array('error' => 'store not done'));
    }

    /**
     * Return the list
     *
     * @param  string $id The list name
     */
    public function show($id)
    {
        return Response::json(array('error' => 'show not done'));
    }

    /**
     * Update the specified list
     *
     * @param string $id The list name
     */
    public function update($id)
    {
        return Response::json(array('error' => 'update not done'));
    }

    /**
     * Uncreate the specified list
     *
     * @param  string $id The list name
     */
    public function destroy($id)
    {
        return Response::json(array('error' => 'destroy not done'));
    }

    /**
     * Archive the specified list
     *
     * @param string $id The list name
     */
    public function archive($id)
    {
        return Response::json(array('error' => 'archive not done'));
    }

    /**
     * Unarchive the specified list
     *
     * @param string $id The list name
     */
    public function unarchive($id)
    {
        return Response::json(array('error' => 'unarchive not done'));
    }

    /**
     * Rename $source list to $dest
     *
     * @param string $source The source list name
     * @param string $dest   The destination list name
     */
    public function rename($source, $dest)
    {
        return Response::json(array('error' => 'renane not done'));
    }

}