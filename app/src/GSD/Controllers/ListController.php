<?php namespace GSD\Controllers;

use GSD\Entities\ListInterface;
use Input;
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
        $archived = !! Input::get('archived');
        try
        {
            $list = Todo::get($id, $archived);
            $result = $this->toAssoc($list);
        }
        catch (\RuntimeException $e)
        {
            $result = array(
                'error' => $e->getMessage()
            );
        }
        return Response::json($result);
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

    /**
     * Convert a TodoList to an associative array
     * @param ListInterface $list The List
     * @return array The associative array
     */
    protected function toAssoc(ListInterface $list)
    {
        $return = array(
            'list' => array(
                'name'     => $list->get('id'),
                'title'    => $list->get('title'),
                'subtitle' => $list->get('subtitle'),
                'archived' => $list->get('archived'),
                'tasks'    => array(),
            ),
        );
        foreach ($list->tasks() as $task)
        {
            $array = array(
                'isNext'        => $task->isNextAction(),
                'isCompleted'   => $task->isComplete(),
                'descript'      => $task->description(),
                'dateDue'       => $task->dateDue(),
                'dateCompleted' => $task->dateCompleted(),
            );
            if ($array['dateDue'])
            {
                $array['dateDue'] = $array['dateDue']->timestamp * 1000;
            }
            if ($array['dateCompleted'])
            {
                $array['dateCompleted'] = $array['dateCompleted']->timestamp * 1000;
            }
            $return['list']['tasks'][] = $array;
        }
        return $return;
    }
}