<?php namespace GSD\Commands;

use Symfony\Component\Console\Input\InputOption;
use Todo;

class MoveTaskCommand extends CommandBase {

    protected $name = 'gsd:move';
    protected $description = 'Move a task between lists.';
    protected $nameArgumentDescription = 'Source list name.';
    protected $taskNoDescription = 'Task # to move from source list.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Get source list
        $sourceName = $this->getListId('Select list with task to move:');
        if (is_null($sourceName))
        {
            $this->abort();
        }
        $sourceList = Todo::get($sourceName);

        // Get task to move
        $taskNo = $this->getTaskNo($sourceList, true, true, false);
        if (is_null($taskNo))
        {
            $this->abort();
        }

        // Get dest list
        $destName = $this->option('dest');
        if (is_null($destName))
        {
            $destName = $this->askForListId(
                true,
                true,
                false,
                'Select destination list:'
            );
            if (is_null($destName))
            {
                $this->abort();
            }
        }
        if ($destName == $sourceName)
        {
            $this->abort('Source and destination cannot be the same');
        }
        $destList = Todo::get($destName);

        // Verify
        $task = $sourceList->task($taskNo);
        $description = $task->description();
        $fromTo = sprintf("from '+%s' to '+%s'", $sourceName, $destName);
        if ( ! $this->option('force'))
        {
            $this->outputErrorBox("Moving '$description' $fromTo");
            $result = $this->ask("Are you sure (yes/no)?");
            if ( ! str2bool($result))
            {
                $this->abort();
            }
        }

        // Remove from source, add to dest, save both
        $sourceList->taskRemove($taskNo);
        $destList->taskAdd($task)
                 ->save();
        $sourceList->save();
        $this->info("'$description' moved $fromTo");
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(array(
            array('dest', 'd', InputOption::VALUE_REQUIRED,
                'Destination list name.', null),
            array('force', 'f', InputOption::VALUE_NONE,
                'Force the move, no prompting.'),
        ), parent::getOptions());
    }

}