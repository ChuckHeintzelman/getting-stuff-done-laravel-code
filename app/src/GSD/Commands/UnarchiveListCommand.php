<?php namespace GSD\Commands;

use App;
use Todo;

class UnarchiveListCommand extends CommandBase {

    protected $name = 'gsd:unarchive';
    protected $description = 'Unarchive a todo list.';
    protected $askForListAction = 'unarchive';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Prompt user for list name
        $name = $this->askForListId(true, true, true);
        if (is_null($name))
        {
            $this->outputErrorBox('*Unarchive aborted*');
            return;
        }

        // Warn if unarchived version exists
        if ($this->repository->exists($name, false))
        {
            $msg = "WARNING!\n\n"
                 . "  An active version of the list '$name' exists.\n"
                 . "  This action will destroy the active version,\n"
                 . "  replacing it with the archived version.";
            $this->outputErrorBox($msg);
        }

        // Ask if user is sure
        $result = $this->ask(
            "Are you sure you want to unarchive '$name' (yes/no)?");
        if ( ! str2bool($result))
        {
            $this->outputErrorBox('*Unarchive aborted*');
            return;
        }

        // Load existing list and save as unarchived
        $list = Todo::get($name, true);
        $list->set('archived', false);
        $list->save();

        // Delete existing archived list
        if ( ! $this->repository->delete($name, true))
        {
            $this->outputErrorBox('ERROR deleting archived version.');
            return;
        }
        $this->info("List '$name' has been unarchived");
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