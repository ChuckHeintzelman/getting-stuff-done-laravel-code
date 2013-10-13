<?php namespace GSD\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Todo;

class EditTaskCommand extends CommandBase {

    protected $name = 'gsd:edit';
    protected $description = 'Edit a task.';
    protected $nameArgumentDescription = 'List name with task to edit.';
    protected $taskNoDescription = 'Task # to edit.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Should we prompt for everything?
        $promptAll = all_null(
            $this->argument('+name'),
            $this->argument('task-number'),
            $this->option('descript'),
            $this->option('action')
        );

        // Get list
        $name = $this->getListId('Select list with task to edit:');
        if (is_null($name))
        {
            $this->abort();
        }
        $list = Todo::get($name);

        // Get task-number
        $taskNo = $this->getTaskNo($list, true, true, false);
        if (is_null($taskNo))
        {
            $this->abort();
        }

        $currDescript = $list->taskGet($taskNo, 'description');
        $currAction = $list->taskGet($taskNo, 'isNextAction');

        // Prompt for description and next action
        if ($promptAll)
        {
            $currActionState = ($currAction) ? 'is' : 'is not';
            $this->line("Current description: $currDescript");
            $descript = $this->ask("New description (enter to skip)?");
            $this->line("Task $currActionState currently a Next Aciton.");
            $next = $this->ask("Is Next Action (enter skip, yes or no)?");
        }

        // Pull description and next action from command
        else
        {
            $descript = $this->option('descript');
            $next = $this->option('action');
        }
        $action = is_null($next) ? null : str2bool($next);

        if ((is_null($descript) || $descript == $currDescript) &&
            (is_null($action)   || $action == $currAction))
        {
            $this->abort("Nothing changed");
        }

        // Make changes and save the list
        $task = $list->task($taskNo);
        if ( ! is_null($action))
        {
            $task->setIsNextAction($action);
        }
        if ( ! is_null($descript))
        {
            $task->setDescription($descript);
        }
        $list->save(true);

        $this->info("Task in $name updated to: ".(string)$task);
    }

    /**
     * Get the console command options.
     */
    protected function getOptions()
    {
        return array_merge(array(
            array('descript', 'd', InputOption::VALUE_REQUIRED,
                'New description for task.'),
            array('action', 'a', InputOption::VALUE_REQUIRED,
                'Is task a next action (yes|no).'),
        ), parent::getOptions());
    }

}