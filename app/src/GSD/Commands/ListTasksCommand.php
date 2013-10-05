<?php namespace GSD\Commands;

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
        $name = $this->getListId();
        if (is_null($name))
        {
            $this->outputErrorBox('ListTasks aborted');
            return;
        }
        $list = Todo::get($name);

        $nextOnly = $this->option('action');
        $skipDone = $this->option('skip-done');
        if ($nextOnly and $skipDone)
        {
            $this->outputErrorBox(
                "Options --action and --skip-done can't be used together."
            );
            return;
        }

        // Gather rows to display
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
                    'Done '.$task->dateCompleted()->format('n/j/y'),
                );
            }
            elseif ($task->isNextAction() or ! $nextOnly)
            {
                $next = ($task->isNextAction()) ? 'YES' : '';
                $due = ($task->dateDue()) ?
                    'Due '.$task->dateDue()->format('M-j') : '';
                $rows[] = array(
                    $rowNo++,
                    $next,
                    $task->description(),
                    $due,
                );
            }
        }

        // Output a pretty table
        $title = ($nextOnly) ? 'Next Actions' :
            (($skipDone) ? "Active Tasks" : "All Tasks");
        $this->info("$title in list '+$name'\n");
        if (count($rows) == 0)
        {
            $this->error("Nothing found");
            return;
        }
        $table = $this->getHelperSet()->get('table');
        $table
            ->setHeaders(array('#', 'Next', 'Description', 'Extra'))
            ->setRows($rows)
            ->render($this->getOutput());
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(array(
            array('action', 'a', InputOption::VALUE_NONE,
                'Show only next actions.', null),
            array('skip-done', 'x', InputOption::VALUE_NONE,
                'Skip completed actions.', null),
        ), parent::getOptions());
    }
}