<?php namespace GSD\Commands;

use App;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Todo;

class AddTaskCommand extends CommandBase {

    protected $name = 'gsd:addtask';
    protected $description = 'Add a new task to a list.';
    protected $nameArgumentDescription = 'List name to add the task to.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $name = $this->getListId();
        if (is_null($name))
        {
            $this->outputErrorBox("AddTask aborted");
            return;
        }
        $list = Todo::get($name);

        $task = App::make('GSD\Entities\TaskInterface');
        if ( ! $task->setFromString($this->argument('task')))
        {
            throw new \InvalidArgumentException('Cannot parse task string');
        }
        $type = 'Todo';
        if ($this->option('action'))
        {
            $task->setIsNextAction(true);
            $type = 'Next Action';
        }
        $list->taskAdd($task);
        $list->save();
        $this->info("$type successfully added to $name");
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments()
    {
        return array_merge(array(
            array('task', InputArgument::REQUIRED,
                "The task's description."),
        ), parent::getArguments());
    }

    /**
     * Get the console command options.
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), array(
            array('action', 'a', InputOption::VALUE_NONE,
                'Make task a Next Action.'),
        ));
    }
}