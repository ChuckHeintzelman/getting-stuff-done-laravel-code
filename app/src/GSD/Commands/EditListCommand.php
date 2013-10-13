<?php namespace GSD\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Todo;

class EditListCommand extends CommandBase {

    protected $name = 'gsd:editlist';
    protected $description = "Edit a list's title or subtitle.";
    protected $nameArgumentDescription = "List name to edit.";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $name = $this->getListId('Select list to edit:');
        if (is_null($name))
        {
            $this->abort();
        }
        $list = Todo::get($name);

        $title = $this->option('title');
        $subtitle = $this->option('subtitle');

        if (all_null($title, $subtitle))
        {
            $this->info(sprintf("Editing '%s'", $name));
            $this->line('');
            $title = $this->ask("Enter list title (enter to skip)?");
            $subtitle = $this->ask("Enter list subtitle (enter to skip)?");
            $this->line('');
            if (all_null($title, $subtitle))
            {
                $this->comment('Nothing changed. List not udpated.');
                return;
            }
        }

        if ($title)
        {
            $list->set('title', $title);
        }
        if ($subtitle)
        {
            $list->set('subtitle', $subtitle);
        }
        $list->save();
        $this->info(sprintf("List '%s' updated", $name));
    }

    /**
     * Get the console command options.
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), array(
            array('title', 't', InputOption::VALUE_REQUIRED,
                'Title of list.', null),
            array('subtitle', 's', InputOption::VALUE_REQUIRED,
                'Subtitle of list.', null),
        ));
    }

}