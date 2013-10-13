<?php namespace GSD\Commands;

use Config;
use Symfony\Component\Console\Input\InputOption;
use Todo;

class ListTasksCommand extends CommandBase {

    protected $name = 'gsd:list';
    protected $description = 'List tasks.';
    protected $nameArgumentDescription = 'List name to display tasks.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $name = $this->getListId('Select list to show tasks:');
        if (is_null($name))
        {
            $this->abort();
        }
        $list = Todo::get($name);

        $nextOnly = $this->option('action');
        $skipDone = $this->option('skip-done');
        if ($nextOnly and $skipDone)
        {
            $this->abort(
                "Options --action and --skip-done can't be used together."
            );
        }

        // Gather rows to display
        $completeFmt = Config::get('todo.dateCompleteFormat');
        $dueFmt = Config::get('todo.dateDueFormat');
        $rows = array();
        $rowNo = 1;
        foreach ($list->tasks() as $task)
        {
            if ($task->isComplete())
            {
                if ($skipDone or $nextOnly) continue;
                $rows[] = array(
                    '',
                    'done',
                    $task->description(),
                    'Done '.$task->dateCompleted()->format($completeFmt),
                );
            }
            elseif ($task->isNextAction() or ! $nextOnly)
            {
                $next = ($task->isNextAction()) ? 'YES' : '';
                $due = ($task->dateDue()) ?
                    'Due '.$task->dateDue()->format($dueFmt) : '';
                $rows[] = array(
                    $rowNo++,
                    $next,
                    $task->description(),
                    $due,
                );
            }
        }

        // Output a pretty table
        $title = ($nextOnly) ? "Next Actions" :
            (($skipDone) ? "Active Tasks" : "All Tasks");
        $this->info("$title in list '+$name'");
        if (count($rows) == 0)
        {
            $this->abort("No tasks in list");
        }
        $table = $this->getHelperSet()->get('table');
        $table
            ->setHeaders(array('#', 'Next', 'Description', 'Extra'))
            ->setRows($rows)
            ->render($this->getOutput());
    }

    /**
     * Get the console command options.
     */
    protected function getOptions()
    {
        return array_merge(array(
            array('action', 'a', InputOption::VALUE_NONE, 'Show only next actions.', null),
            array('skip-done', 'x', InputOption::VALUE_NONE, 'Skip completed actions.', null),
        ), parent::getOptions());
    }

}