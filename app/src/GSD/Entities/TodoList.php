<?php namespace GSD\Entities;

// File: app/src/GSD/Entities/TodoList.php

use GSD\Repositories\TodoRepositoryInterface;

class TodoList implements ListInterface {

    protected static $validAttribs = array('id', 'archived', 'subtitle', 'title');

    protected $repository;
    protected $tasks;
    protected $attributes;
    protected $isDirty;

    /**
     * Inject the dependencies during construction
     * @param TodoRepositoryInterface $repo The repository
     * @param TaskCollectionInterface $collection The task collection
     */
    public function __construct(TodoRepositoryInterface $repo,
        TaskCollectionInterface $collection)
    {
        $this->repository = $repo;
        $this->tasks = $collection;
        $this->attributes = array();
        $this->isDirty = false;
    }

    // List attributes -------------------------

    /**
     * Return a list attribute
     * @param string $name id|archived|subtitle|title
     * @return mixed
     * @throws InvalidArgumentException If $name is invalid
     */
    public function get($name)
    {
        if ( ! in_array($name, static::$validAttribs))
        {
            throw new \InvalidArgumentException("Invalid attribute named $name");
        }
        if (array_key_exists($name, $this->attributes))
        {
            return $this->attributes[$name];
        }
        return null;
    }

    /**
     * Set a list attribute
     * @param string $name id|archived|subtitle|title
     * @param mixed $value Attribute value
     * @return $this for method chaining
     * @throws InvalidArgumentException If $name is invalid
     */
    public function set($name, $value)
    {
        if ( ! in_array($name, static::$validAttribs))
        {
            throw new \InvalidArgumentException("Invalid attribute named $name");
        }
        if ($name == 'archived') $value = !! $value;
        $this->attributes[$name] = $value;
        $this->isDirty = true;
        return $this;
    }

    // List operations -------------------------

    /**
     * Save the list
     * @return $this For method chaining
     * @throws RuntimeException If cannot save.
     */
    public function save()
    {
        if ($this->isDirty)
        {
            $archived = !empty($this->attributes['archived']);
            if ( ! array_key_exists('id', $this->attributes))
            {
                throw new \RuntimeException("Cannot save if id not set");
            }
            $id = $this->attributes['id'];
            if ( ! $this->repository->save($id, $this, $archived))
            {
                throw new \RuntimeException("Repository could not save");
            }
            $this->isDirty = false;
        }
        return $this;
    }

    // Task operations -------------------------

    // Not yet implemented ---------------------

    public function id()
    {
        throw new \Exception('not implemented');
    }

    public function isArchived()
    {
        throw new \Exception('not implemented');
    }

    public function isDirty()
    {
        throw new \Exception('not implemented');
    }

    public function title()
    {
        throw new \Exception('not implemented');
    }

    public function archive()
    {
        throw new \Exception('not implemented');
    }

    public function load($id)
    {
        throw new \Exception('not implemented');
    }

    public function taskAdd($task)
    {
        throw new \Exception('not implemented');
    }

    public function taskCount()
    {
        throw new \Exception('not implemented');
    }

    public function task($index)
    {
        throw new \Exception('not implemented');
    }

    public function taskGet($index, $name)
    {
        throw new \Exception('not implemented');
    }

    public function tasks()
    {
        throw new \Exception('not implemented');
    }

    public function taskSet($index, $name, $value)
    {
        throw new \Exception('not implemented');
    }

    public function taskRemove($index)
    {
        throw new \Exception('not implemented');
    }
}
