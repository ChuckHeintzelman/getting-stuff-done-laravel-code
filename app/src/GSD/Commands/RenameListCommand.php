<?php namespace GSD\Commands;

use Config;
use Symfony\Component\Console\Input\InputOption;
use Todo;

class RenameListCommand extends CommandBase {

    protected $name = 'gsd:rename';
    protected $description = 'Rename a list.';
    protected $askForListAction = 'rename';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Get archived flag and list to rename
        $archived = $this->option('archived');
        $name = $this->askForListId(true, true, $archived);
        if (is_null($name))
        {
            $this->outputErrorBox('*aborted*');
            return;
        }
        if ( ! $archived && Config::get('todo.defaultList') == $name)
        {
            $this->outputErrorBox('Cannot rename default list');
            return;
        }

        // Prompt for new list name
        $newName = $this->askForListId(false, true, $archived);
        if (is_null($name))
        {
            $this->outputErrorBox('*aborted*');
            return;
        }

        // Load existing list, save with new name
        $list = Todo::get($name, $archived);
        $newList = clone $list;
        $newList->set('id', $newName);
        $newList->save();

        // Delete existing list and we're done
        $list->delete();
        $listType = ($archived) ? 'Archived list' : 'List';
        $this->info($listType . " '$name' renamed to '$newName'");
    }

    /**
     * No arguments.
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Just the --archived option
     */
    protected function getOptions()
    {
        return array(
            array('archived', 'a', InputOption::VALUE_NONE,
                'Use archived lists?', null),
        );
    }

}