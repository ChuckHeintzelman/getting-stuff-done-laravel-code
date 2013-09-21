<?php namespace GSD\Entities;

// File: app/src/GSD/Entities/TodoTaskInterface.php

interface TodoTaskInterface {

    /**
     * Has the task been completed?
     * @return boolean
     */
    public function isComplete();

    /**
     * What's the description of the stask
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
}
