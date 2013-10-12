<?php namespace GSD\Commands;

use Config;
use Todo;

class ArchiveListCommand extends CommandBase {

    protected $name = 'gsd:archive';
    protected $description = 'Archive a todo list.';
    protected $askForListAction = 'archive';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Get list name to archive
        $name = $this->askForListId();
        if (is_null($name))
        {
            $this->outputErrorBox('*Archive aborted*');
            return;
        }
        if (Config::get('todo.defaultList') == $name)
        {
            $this->outputErrorBox('Cannot archive default list');
            return;
        }

        // Warn if list exists
        if ($this->repository->exists($name, true))
        {
            $msg = "WARNING\n\n"
                 . "  An archived version of the list '$name' exists.\n"
                 . "  This action will destory the old archived list.";
            $this->outputErrorBox($msg);
        }
        $result = $this->ask(
            "Are you sure you want to archive '$name' (yes/no)?");
        if ( ! str2bool($result))
        {
            $this->outputErrorBox("*Archive aborted*");
            return;
        }

        // Archive the list
        $list = Todo::get($name);
        $list->archive();
        $this->info("List '$name' has been archived");
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
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }
}