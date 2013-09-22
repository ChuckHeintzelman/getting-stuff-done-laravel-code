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

    /**
     * Return the list's id (base filename)
     */
    public function id()
    {
        return $this->get('id');
    }

    /**
     * Is the list archived?
     * @return bool
     */
    public function isArchived()
    {
        return !! $this->get('archived');
    }

    /**
     * Is the list dirty?
     * @return bool
     */
    public function isDirty()
    {
        return $this->isDirty;
    }

    /**
     * Return the title (alias for get('title'))
     */
    public function title()
    {
        return $this->get('title');
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
            if ( ! array_key_exists('id', $this->attributes))
            {
                throw new \RuntimeException("Cannot save if id not set");
            }
            if ( ! $this->repository->save($this))
            {
                throw new \RuntimeException("Repository could not save");
            }
            $this->isDirty = false;
        }
        return $this;
    }

    /**
     * Archive the list. This makes the list only available from the archive.
     * @return ListInterface For method chaining
     * @throws RuntimeException If cannot save.
     */
    public function archive()
    {
        // If already archived, then return this
        if ($this->isArchived())
        {
            return $this;
        }

        if ( ! array_key_exists('id', $this->attributes))
        {
            throw new \RuntimeException("Cannot archive if id not set");
        }
        $id = $this->attributes['id'];

        // Delete existing, unarchived list if it exists
        if ($this->repository->exists($id, false) and
            ! $this->repository->delete($id, false))
        {
            throw new \RuntimeException("Repository failed deleting unarchived list");
        }

        // Set archived and save
        $this->set('archived', true);
        return $this->save();
    }

    // Task operations -------------------------

    /**
     * Add a new task to the collection
     * @param string|TaskInterface $task Either a TaskInterface or a string
     *                                   we can construct one from.
     * @return $this for method chaining
     */
    public function taskAdd($task)
    {
        $this->tasks->add($task);
        $this->isDirty = true;
        return $this;
    }

    /**
     * Return number of tasks
     * @return integer
     */
    public function taskCount()
    {
        return count($this->tasks->getAll());
    }

    /**
     * Return a task
     * @param integer $index Task index #
     * @return TaskInterface
     * @throws OutOfBoundsException If $index outside range
     */
    public function task($index)
    {
        return $this->tasks->get($index);
    }

    /**
     * Return a task attribute
     * @param integer $index Task index #
     * @param string $name Attribute name
     * @return mixed
     * @throws OutOfBoundsException If $index outside range
     * @throws InvalidArgumentException If $name is invalid
     */
    public function taskGet($index, $name)
    {
        $task = $this->tasks->get($index);
        return $task->get($name);
    }

    /**
     * Return all tasks as an array.
     * @return array All the TaskInterface objects
     */
    public function tasks()
    {
        return $this->tasks->getAll();
    }

    /**
     * Set a task attribute
     * @param integer $index Task index #
     * @param string $name Attribute name
     * @param mixed $value Attribute value
     * @return $this for method chaining
     * @throws OutOfBoundsException If $index outside range
     * @throws InvalidArgumentException If $name is invalid
     */
    public function taskSet($index, $name, $value)
    {
        $task = $this->tasks->get($index);
        $task->set($name, $value);
        return $this;
    }

    /**
     * Remove the specified task
     * @return $this for method chaining
     * @throws OutOfBoundsException If $index outside range
     */
    public function taskRemove($index)
    {
        $this->tasks->remove($index);
        return $this;
    }
}
