<?php namespace GSD\Commands;

use Todo;

class DoTaskCommand extends CommandBase {

    protected $name = 'gsd:do';
    protected $description = 'Mark a task as complete.';
    protected $nameArgumentDescription = 'List name with completed task.';
    protected $taskNoDescription = 'Task # to mark complete.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $name = $this->getListId('Select list with task to mark complete:');
        if (is_null($name))
        {
            $this->abort();
        }
        $list = Todo::get($name);

        $taskNo = $this->getTaskNo($list, true, true, false);
        if (is_null($taskNo))
        {
            $this->abort();
        }

        $description = $list->taskGet($taskNo, 'description');
        $list->taskSet($taskNo, 'isComplete', true)
             ->save();
        $this->info("Task '$description' marked complete.");
    }
}