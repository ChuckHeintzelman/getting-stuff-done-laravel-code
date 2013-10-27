<?php namespace GSD\Entities;

// File: app/src/GSD/Entities/TaskInterface.php

interface TaskInterface {

    /**
     * Has the task been completed?
     * @return boolean
     */
    public function isComplete();

    /**
     * What's the description of the task
     * @return string
     */
    public function description();

    /**
     * When is the task due?
     * @return mixed Either null if no due date set, or a Carbon object.
     */
    public function dateDue();

    /**
     * When was the task completed?
     * @return mixed Either null if not complete, or a Carbon object
     */
    public function dateCompleted();

    /**
     * Is the task a Next Action?
     * @return boolean
     */
    public function isNextAction();

    /**
     * Set whether task is complete.
     * @param bool $complete
     * @param mixed $when If null then uses current date/time, otherwise
     *                    a Carbon object or date/time string
     */
    public function setIsComplete($complete, $when = null);

    /**
     * Set task description
     * @param string $description
     */
    public function setDescription($description);

    /**
     * Set date due
     * @param null|string|Carbon $date null to clear, otherwise stores Carbon
     *        date internally.
     */
    public function setDateDue($date);

    /**
     * Set whether task is a next action
     * @param bool $nextAction
     */
    public function setIsNextAction($nextAction);

    /**
     * Set a property. (Ends up calling specific setter)
     * @param string $name isComplete|description|dateDue|isNextAction
     * @param mixed $value The value to set
     * @throws InvalidArgumentException If $name is invalid
     */
    public function set($name, $value);

    /**
     * Get a property.
     * @param string $name isComplete|description|dateDue|isNextAction|dateCompleted
     * @return mixed
     * @throws InvalidArgumentException If $name is invalid
     */
    public function get($name);

    /**
     * Set all the tasks attributes from a string.
     * @param string $info The task info
     * @return bool True on success, false otherwise
     */
    public function setFromString($info);

    /**
     * Return the task as a string
     */
    public function __toString();
}
