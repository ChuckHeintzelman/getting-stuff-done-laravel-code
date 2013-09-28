<?php namespace GSD\Commands;

use App;
use Illuminate\Console\Command;

class CommandBase extends Command {

    protected $repository;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->repository = App::make('GSD\Repositories\TodoRepositoryInterface');
    }

    /**
     * Prompt the user ofr a list id
     * @param bool $existing Prompt for existing list or new list?
     * @param bool $allowCancel Allow user to cancel?
     * @param bool $archived Use archived lists?
     * @return mixed String list id or null if user cancels
     */
    public function askForListId($existing = true, $allowCancel = true,
        $archived = false)
    {
        if ($existing)
        {
            throw new \Exception('existing not done');
        }

        $prompt = 'Enter name of new list';
        if ($allowCancel) $prompt .= ' (enter to cancel)';
        $prompt .= '?';
        while(true)
        {
            if ( ! ($result = $this->ask($prompt)))
            {
                if ($allowCancel)
                {
                    return null;
                }
                $this->outputErrorBox('You must enter something');
            }
            else if ($this->repository->exists($result, $archived))
            {
                $this->outputErrorBox("You already have a list named '$result'");
            }
            else
            {
                return $result;
            }
        }
    }

    /**
     * Output an error box
     * @param string $message The message
     */
    protected function outputErrorBox($message)
    {
        $formatter = $this->getHelperSet()->get('formatter');
        $block = $formatter->formatBlock($message, 'error', true);
        $this->line('');
        $this->line($block);
        $this->line('');
    }

}