<?php namespace GSD\Commands;

use App;
use Config;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Todo;

class CommandBase extends Command {

    protected $repository;
    protected $nameArgumentDescription = 'List name.';
    protected $taskNoDescription = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->repository = App::make('GSD\Repositories\TodoRepositoryInterface');
        $aliases = Config::get('todo.aliases');
        if (array_key_exists($this->name, $aliases))
        {
            $this->setAliases($aliases[$this->name]);
        }
    }

    /**
     * Prompt the user for a list id
     * @param bool $existing Prompt for existing list or new list?
     * @param bool $allowCancel Allow user to cancel
     * @param bool $archived Prompt for archived list?
     * @param string $selectTitle Title to use if list selection occurs.
     * @return mixed string list id or null if user cancels
     */
    public function askForListId($existing = true, $allowCancel = true,
        $archived = false, $selectTitle = 'Select a list:')
    {
        if ($existing)
        {
            $abort = "Cancel";
            $choices = Todo::allLists($archived);
            if (count($choices) == 0)
            {
                throw new \RuntimeException('No lists to choose from');
            }
            $result = pick_from_list($this, $selectTitle, $choices, 0, $abort);
            if ($result == -1)
            {
                return null;
            }
            return $choices[$result-1];
        }

        $prompt = 'Enter name of new';
        if ($archived) $prompt .= ' archived';
        $prompt .= ' list';
        if ($allowCancel) $prompt .= ' (enter to cancel)';
        $prompt .= '?';
        while(true)
        {
            if ( ! ($result = $this->ask($prompt)))
            {
                if ($allowCancel)
                {
                    return null;
                }
                $this->outputErrorBox('You must enter something');
            }
            else if ($this->repository->exists($result, $archived))
            {
                $this->outputErrorBox("You already have a list named '$result'");
            }
            else
            {
                return $result;
            }
        }
    }

    /**
     * Output an error box
     * @param string $message The message
     */
    protected function outputErrorBox($message)
    {
        $formatter = $this->getHelperSet()->get('formatter');
        $block = $formatter->formatBlock($message, 'error', true);
        $this->line('');
        $this->line($block);
        $this->line('');
    }

    /**
     * The console command arguments. Derived classes could replace this
     * method entirely, or merge its own arguments with thext
     *
     * @return array of argument definitions
     */
    protected function getArguments()
    {
        $args = array();
        if ( ! is_null($this->taskNoDescription))
        {
            $args[] = array(
                'task-number',
                InputArgument::OPTIONAL,
                $this->taskNoDescription
            );

        }
        $args[] = array(
            '+name',
            InputArgument::OPTIONAL,
            $this->nameArgumentDescription
        );
        return $args;
    }

    /**
     * The console command options. Derived classes could replace this
     * method entirely, or merge their own options with these.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('listname', 'l', InputOption::VALUE_REQUIRED,
                "Source of list name, 'prompt' or 'default'."),
        );
    }

    /**
     * Get the list id (of existing lists).
     *
     * This can happen in a variety of ways. If specified as an argument, then
     * it's returned (without the + of course). Otherwise, look to see if the
     * `--listname` argument is used and determine the list accordingly.
     * Finally, we fallback to the method specified by Config's
     * 'app.gsd.noListPrompt' setting
     *
     * @param string $selectTitle Title to use if list selection occurs
     * @return $string Existing list id (or null if user aborts)
     * @throws InvalidArgumentException If something's not right
     */
    protected function getListId($selectTitle = 'Select a list:')
    {
        $archived = $this->input->hasOption('archived') and
                    $this->option('archived');
        $name = $this->argument('+name');
        $listnameOption = $this->option('listname');
        if ($name)
        {
            $name = substr($name, 1);
            if ( ! is_null($listnameOption))
            {
                throw new \InvalidArgumentException(
                    'Cannot specify $name and --listname together');
            }
        }
        else
        {
            if (is_null($listnameOption))
            {
                $listnameOption = Config::get('todo.noListPrompt') ? 'prompt' : 'config';
            }
            if ($listnameOption == 'prompt')
            {
                $name = $this->askForListId(true, true, $archived, $selectTitle);
                if (is_null($name))
                {
                    return null;
                }
            }
            else
            {
                $name = Config::get('todo.defaultList');
            }
        }

        // Throw error if list doesn't exist
        if ( ! $this->repository->exists($name, $archived))
        {
            $archived = ($archived) ? '(archived) ' : '';
            throw new \InvalidArgumentException(
                "List $archived'$name' not found");
        }
        return $name;
    }


    /**
     * Get the task # of a list, either from the argument or prompt the user.
     * Keep in mind the # present to the user always begins with 1, but the
     * number we return is always one less (starting with 0)
     *
     * @param ListInterface $list The Todo List
     * @param bool $showNext Show next actions in prompt list
     * @param bool $showNormal Show normal tasks in prompt list
     * @param bool $showComplete Show completed tasks in prompt list
     * @return mixed NULL if user aborts, otherwise integer of task number
     */
    protected function getTaskNo(\GSD\Entities\ListInterface $list, $showNext,
        $showNormal, $showComplete)
    {
        // Return the # if provided on command line
        $taskNo = $this->argument('task-number');
        if ( ! is_null($taskNo))
        {
            return (int)$taskNo - 1;
        }

        // Build list of tasks
        $tasks = array();
        foreach ($list->tasks() as $task)
        {
            if ($task->isComplete())
            {
                if ($showComplete)
                    $tasks[] = (string)$task;
            }
            elseif ($task->isNextAction())
            {
                if ($showNext)
                    $tasks[] = (string)$task;
            }
            elseif ($showNormal)
            {
                $tasks[] = (string)$task;
            }
        }

        // Let user pick from list, return result
        $selectTitle = rtrim($this->taskNoDescription, '.') . ':';
        $result = pick_from_list($this, $selectTitle, $tasks, 0, "Cancel");
        if ($result == -1)
        {
            return null;
        }
        return $result - 1;
    }

    /**
     * Output an error message and die
     * @param string $message Optional message to output
     */
    protected function abort($message = '*aborted*')
    {
        $this->outputErrorBox($message);
        exit;
    }
}