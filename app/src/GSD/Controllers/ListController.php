<?php namespace GSD\Controllers;

use App;
use Carbon\Carbon;
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
        $archived = !! Input::get('archived');
        $lists = Todo::allLists($archived);

        $return = array(
            'lists' => array(),
        );

        foreach ($lists as $listId)
        {
            $list = Todo::get($listId, $archived);
            $return['lists'][] = array(
                'name'           => $listId,
                'title'          => $list->title(),
                'subtitle'       => $list->get('subtitle'),
                'isArchived'     => $list->isArchived(),
                'numNextActions' => $list->taskCount('next'),
                'numNormal'      => $list->taskCount('todo'),
                'numCompleted'   => $list->taskCount('done'),
            );
        }
        return Response::json($return);
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
        if ($id != Input::get('name'))
        {
            return Response::json(array('error' => 'List id/name mismatch'));
        }

        // Build new list with values
        $list = App::make('GSD\Entities\ListInterface');
        $list->set('id', $id);
        $list->set('title', Input::get('title'));
        $list->set('subtitle', Input::get('subtitle'));
        $list->set('archived', str2bool(Input::get('archived')));

        // Add tasks to list from values passed
        $tasks = Input::get('tasks');
        if ( ! is_array($tasks)) $tasks = array();
        foreach ($tasks as $task)
        {
            $newTask = App::make('GSD\Entities\TaskInterface');
            $descript = $task['descript'];
            if ($task['dateDue'])
            {
                $d = Carbon::createFromTimestamp($task['dateDue'] / 1000);
                $descript .= ' :due:' . $d->format('Y-m-d');
            }
            $newTask->setDescription($descript);
            if (str2bool($task['isCompleted']))
            {
                $newTask->setIsComplete(
                    true,
                    Carbon::createFromTimestamp($task['dateCompleted'] / 1000)
                );
            }
            if (str2bool($task['isNext']))
            {
                $newTask->setIsNextAction(true);
            }
            $list->taskAdd($newTask);
        }

        // Save and return success
        $list->save();
        return Response::json(array('success' => true));
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