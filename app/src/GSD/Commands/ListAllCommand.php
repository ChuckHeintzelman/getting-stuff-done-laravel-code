<?php namespace GSD\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ListAllCommand extends CommandBase {

    protected $name = 'gsd:listall';
    protected $description = 'Lists all todo lists (and possibly tasks).';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $archived = $this->option('archived');
        $title = 'Listing all ';
        if ($archived) $title .= 'archived ';
        $title .= 'lists';
        $this->info($title);

        $lists = \Todo::allLists($archived);
        $lists = $this->sortListIds($lists);

        $headers = array('list', 'next', 'todos', 'completed');
        $rows = array();
        foreach ($lists as $listId)
        {
            $list = \Todo::get($listId, $archived);
            $rows[] = array(
                $listId,
                $list->taskCount('next'),
                $list->taskCount('todo'),
                $list->taskCount('done'),
            );
        }

        // Output a pretty table
        $table = $this->getHelperSet()->get('table');
        $table
          ->setHeaders($headers)
          ->setRows($rows)
          ->render($this->getOutput());

    }

    /**
     * Sort the list ids
     */
    protected function sortListIds(array $listIds)
    {
        // Pull the names
        $special = array();
        foreach (\Config::get('todo.listOrder') as $name)
        {
            $special[$name] = false;
        }

        // Peel off the specials
        $tosort = array();
        foreach ($listIds as $listId)
        {
            if (array_key_exists($listId, $special))
            {
                $special[$listId] = true;
            }
            else
            {
                $tosort[] = $listId;
            }
        }

        // Put the specials first then sort the remaining and add them in
        $return = array();
        foreach ($special as $listId => $flag)
        {
            if ($flag)
            {
                $return[] = $listId;
            }
        }
        natcasesort($tosort);
        return array_merge($return, $tosort);
    }

    /**
     * No arguments.
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Just the `--archived` option
     */
    protected function getOptions()
    {
        return array(
            array('archived', 'a', InputOption::VALUE_NONE,
                'Use archived lists?'),
        );
    }
}
