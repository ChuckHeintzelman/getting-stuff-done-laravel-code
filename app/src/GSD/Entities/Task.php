<?php namespace GSD\Entities;

// File: app/src/GSD/Entities/Task.php

use Carbon\Carbon;

class Task implements TaskInterface {

    protected $complete;        // Is the task complete?
    protected $description;     // Task description
    protected $due;             // null or Carbon
    protected $whenCompleted;   // null or Carbon
    protected $nextAction;      // Is this a next action?

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->clear();
    }

    /**
     * Clear all task attributes
     */
    protected function clear()
    {
        $this->complete = false;
        $this->description = '';
        $this->due = null;
        $this->whenCompleted = null;
        $this->nextAction = false;
    }

    /**
     * Has the task been completed?
     * @return boolean
     */
    public function isComplete()
    {
        return $this->complete;
    }

    /**
     * What's the description of the task
     * @return string
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * When is the task due?
     * @return mixed Either null if no due date set, or a Carbon object.
     */
    public function dateDue()
    {
        return $this->due;
    }

    /**
     * When was the task completed?
     * @return mixed Either null if not complete, or a Carbon object
     */
    public function dateCompleted()
    {
        return $this->whenCompleted;
    }

    /**
     * Is the task a Next Action?
     * @return boolean
     */
    public function isNextAction()
    {
        return $this->nextAction;
    }

    /**
     * Set whether task is complete.
     * @param bool $complete
     * @param mixed $when If null then uses current date/time, otherwise
     *                    a Carbon object or date/time string
     */
    public function setIsComplete($complete, $when = null)
    {
        $this->complete = !! $complete;
        if ($this->complete)
        {
            if ($when == null)
            {
                $when = new Carbon;
            }
            else if (is_string($when))
            {
                $when = new Carbon($when);
            }
            $this->whenCompleted = $when;
        }
        else
        {
            $this->whenCompleted = null;
        }
    }

    /**
     * Set task description
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Set date due
     * @param null|string|Carbon $date null to clear, otherwise stores Carbon
     *        date internally.
     * @throws InvalidArgumentException If $date is not null or Carbon
     */
    public function setDateDue($date)
    {
        if ( ! is_null($date) and ! ($date instanceof Carbon))
        {
            throw new \InvalidArgumentException('$date is not null or Carbon');
        }
        $this->due = $date;
    }

    /**
     * Set whether task is a next action
     * @param bool $nextAction
     */
    public function setIsNextAction($nextAction)
    {
        $this->nextAction = !! $nextAction;
    }

    /**
     * Set a property. (Ends up calling specific setter)
     * @param string $name isComplete|description|dateDue|isNextAction
     * @param mixed $value The value to set
     * @throws InvalidArgumentException If $name is invalid
     */
    public function set($name, $value)
    {
        switch ($name)
        {
            case 'isComplete':
                $this->setIsComplete($value);
                break;
            case 'description':
                $this->setDescription($value);
                break;
            case 'dateDue':
                $this->setDateDue($value);
                break;
            case 'isNextAction':
                $this->setIsNextAction($value);
                break;
            default:
                throw new \InvalidArgumentException("Invalid attribute $name");
        }
    }

    /**
     * Get a property.
     * @param string $name isComplete|description|dateDue|isNextAction|dateCompleted
     * @return mixed
     * @throws InvalidArgumentException If $name is invalid
     */
    public function get($name)
    {
        switch ($name)
        {
            case 'isComplete':
                return $this->isComplete();
            case 'description':
                return $this->description();
            case 'dateDue':
                return $this->dateDue();
            case 'isNextAction':
                return $this->isNextAction();
            case 'dateCompleted':
                return $this->dateCompleted();
            default:
                throw new \InvalidArgumentException("Invalid attribute $name");
        }
    }

    /**
     * Set all the tasks attributes from a string.
     * @param string $info The task info
     * @return bool True on success, false otherwise
     */
    public function setFromString($info)
    {
        $this->clear();

        // Remove dup spaces and split into words
        $info = preg_replace('/\s\s+/', ' ', $info);
        $words = explode(' ', trim($info));
        if (count($words) == 1 && $words[0] == '')
        {
            return false;
        }

        // Completed item
        if ($words[0] == 'x')
        {
            $this->complete = true;
            array_shift($words);
            try
            {
                $this->whenCompleted = new Carbon(array_shift($words));
            }
            catch (\Exception $e)
            {
                return false;
            }
        }

        // Next action
        else if ($words[0] == '*')
        {
            $this->nextAction = true;
            array_shift($words);
        }

        // Normal item
        else if ($words[0] == '-')
        {
            array_shift($words);
        }

        // Look for a due date
        for ($i = 0; $i < count($words); $i++)
        {
            if (substr($words[$i], 0, 5) == ':due:')
            {
                $this->due = new Carbon(substr($words[$i], 5));
                unset($words[$i]);
                break;
            }
        }

        $this->description = join(' ', $words);
        return true;
    }

    /**
     * Return the task as a string
     */
    public function __toString()
    {
        $build = array();
        if ($this->complete)
        {
            $build[] = 'x';
            $build[] = $this->whenCompleted->format('Y-m-d');
        }
        elseif ($this->nextAction)
        {
            $build[] = '*';
        }
        else
        {
            $build[] = '-';
        }
        $build[] = $this->description;
        if ($this->due)
        {
            $build[] = ':due:' . $this->due->format('Y-m-d');
        }
        return join(' ', $build);
    }
}
