<?php namespace GSD\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Todo;

class UncreateCommand extends CommandBase {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gsd:uncreate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Destroy an empty list.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Prompt user for list-id
        if ( ! ($name = $this->askForListId(true, true)))
        {
            $this->outputErrorBox('*aborted*');
            exit;
        }

        // Validate list has no tasks
        $list = Todo::get($name);
        if ($list->taskCount() > 0)
        {
            throw new \UnexpectedValueException(
                'Cannot uncreate a list with tasks');
        }

        // Delete list
        if ( ! $this->repository->delete($name))
        {
            throw new \RuntimeException("Repository couldn't delete list '$name'");
        }
        $this->info("The list '$name' is now in the big bitbucket in the sky");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }

}