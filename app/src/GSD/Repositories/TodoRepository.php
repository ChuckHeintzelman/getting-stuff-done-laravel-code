<?php namespace GSD\Repositories;

// File: app/src/GSD/Repositories/TodoRespository.php

use Config;
use GSD\Entities\ListInterface;

class TodoRepository implements TodoRepositoryInterface {

    protected $path;
    protected $extension;

    /**
     * Constructor. We'll throw exceptions if the paths don't exist
     */
    public function __construct()
    {
        $this->path = str_finish(Config::get('app.gsd.folder'), '/');
        if ( ! is_dir($this->path))
        {
            throw new \RuntimeException("Directory doesn't exist: $this->path");
        }
        if ( ! is_dir($this->path.'archived'))
        {
            throw new \RuntimeException("Directory doesn't exist: $this->path".
                'archived');
        }
        $this->extension = Config::get('app.gsd.extension');
        if ( ! starts_with($this->extension, '.'))
        {
            $this->extension = '.' . $this->extension;
        }
    }

    /**
     * Delete the todo list
     * @param string $id ID of the list
     * @return boolean True if successful
     */
    public function delete($id, $archived = false)
    {
        $file = $this->fullpath($id, $archived);
        if (file_exists($file))
        {
            return unlink($file);
        }
        return false;
    }

    /**
     * Does the todo list exist?
     * @param  string $id ID of the list
     * @param  boolean $archived Check for archived lists only?
     * @return boolean
     */
    public function exists($id, $archived = false)
    {
        $file = $this->fullpath($id, $archived);
        return file_exists($file);
    }

    /**
     * Return the ids of all the lists
     * @param  boolean $archived Return archived ids or unarchived?
     * @return array of list ids
     */
    public function getAll($archived = false)
    {
        $match = $this->path;
        if ($archived)
        {
            $match .= 'archived/';
        }
        $match .= '*' . $this->extension;
        $files = glob($match);
        $ids = array();
        foreach ($files as $file)
        {
            $ids[] = basename($file, $this->extension);
        }
        return $ids;
    }

    /**
     * Load a TodoList from it's id
     * @param string $id ID of the list
     * @param boolean $archived Load an archived list?
     * @return ListInterface The list
     * @throws InvalidArgumentException If $id not found
     */
    public function load($id, $archived = false)
    {
        if ( ! $this->exists($id, $archived))
        {
            throw new \InvalidArgumentException(
                "List with id=$id, archived=$archived not found");
        }
        $lines = file($this->fullpath($id, $archived));

        // Pull title
        $title = array_shift($lines);
        $title = trim(substr($title, 1));

        // Pull subtitle
        if (count($lines) && $lines[0][0] == '(')
        {
            $subtitle = trim(array_shift($lines));
            $subtitle = ltrim($subtitle, '(');
            $subtitle = rtrim($subtitle, ')');
        }

        // Setup the list
        $list = \App::make('GSD\Entities\ListInterface');
        $list->set('id', $id);
        $list->set('title', $title);
        if ( ! empty($subtitle))
        {
            $list->set('subtitle', $subtitle);
        }
        $list->set('archived', $archived);

        // And add the tasks
        foreach ($lines as $line)
        {
            $line = trim($line);
            if ($line)
            {
                $list->taskAdd($line);
            }
        }

        return $list;
    }

    /**
     * Save a TodoList
     * @param ListInterface $list The TODO List
     * @return boolean True if successful
     */
    public function save(ListInterface $list)
    {
        $id = $list->get('id');
        $archived = $list->get('archived');
        $build = array();
        $build[] = '# ' . $list->get('title');
        $subtitle = $list->get('subtitle');
        if ($subtitle)
        {
            $build[] = "($subtitle)";
        }
        $lastType = '';
        $tasks = $list->tasks();
        foreach ($tasks as $task)
        {
            $task = (string)$task;
            $type = $task[0];
            if ($type != $lastType)
            {
                $build[] = '';      // Blank line between types of tasks
                $lastType = $type;
            }
            $build[] = $task;
        }
        $content = join("\n", $build);
        $filename = $this->fullpath($id, $archived);
        $result = file_put_contents($filename, $content);

        return $result !== false;
    }

    /**
     * Return the path to the list file
     */
    protected function fullpath($id, $archived)
    {
        $path = $this->path;
        if ($archived)
        {
            $path .= 'archived/';
        }
        $path .= $id . $this->extension;
        return $path;
    }
}