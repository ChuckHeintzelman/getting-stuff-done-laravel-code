<?php namespace GSD\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Todo;

class UncreateCommand extends CommandBase {

    protected $name = 'gsd:uncreate';
    protected $description = 'Destroy an empty list.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Prompt user for list-id
        $selectTitle = 'Select list to uncreate:';
        $name = $this->askForListId(true, true, false, $selectTitle);
        if (is_null($name))
        {
            $this->abort();
        }

        // Validate list has no tasks
        $list = Todo::get($name);
        if ($list->taskCount() > 0)
        {
            $this->abort('Cannot uncreate a list with tasks');
        }

        // Delete list
        if ( ! $this->repository->delete($name))
        {
            $this->abort("Repository couldn't delete list '$name'");
        }
        $this->info("The list '$name' is now in the big bitbucket in the sky");
    }

    /**
     * No arguments.
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * No options.
     */
    protected function getOptions()
    {
        return array();
    }
}