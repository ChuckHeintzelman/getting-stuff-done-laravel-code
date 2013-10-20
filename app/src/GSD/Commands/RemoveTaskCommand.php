<?php namespace GSD\Commands;

use Symfony\Component\Console\Input\InputOption;
use Todo;

class RemoveTaskCommand extends CommandBase {

    protected $name = 'gsd:remove';
    protected $description = 'Remove a task from a list.';
    protected $taskNoDescription = 'Task # to remove.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Load list, prompting if needed
        $name = $this->getListId('Select list with task to remove:');
        if (is_null($name))
        {
            $this->abort();
        }
        $list = Todo::get($name);

        // Prompt for task # if needed
        $taskNo = $this->getTaskNo($list, true, true, false);
        if (is_null($taskNo))
        {
            $this->abort();
        }

        // Show warning, get prompt if needed
        $description = $list->taskGet($taskNo, 'description');
        if ( ! $this->option('force'))
        {
            $this->outputErrorBox(
                "WARNING: This will remove the task '$description'."
            );
            $result = $this->ask("Are you sure (yes/no)?");
            if ( ! str2bool($result))
            {
                $this->abort();
            }
        }

        // Delete task from list and save
        $list->taskRemove($taskNo)
             ->save();
        $this->info("Task '$description' removed from '+$name'");
    }

    /**
     * Return the options.
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), array(
            array('force', 'f', InputOption::VALUE_NONE,
                'Force the removal, no prompting.'),
        ));
    }
}