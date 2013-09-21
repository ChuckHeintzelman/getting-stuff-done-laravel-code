<?php namespace GSD\Providers;

use App;

class TodoManager {

    /**
     * A stupid method for testing
     */
    public function imATeapot()
    {
        return "I'm a teapot.";
    }

    /**
     * Create a new TODO List
     * @param string $id The basename of the list
     * @param string $title The title of the list
     * @return ListInterface The newly created list
     * @throws InvalidArgumentException If the list already exists
     */
    public function makeList($id, $title)
    {
        $repository = App::make('TodoRepositoryInterface');
        if ($repository->exists($id))
        {
            throw new \InvalidArgumentException("A list with id=$id already exists");
        }
        $list = App::make('ListInterface');
        $list->set('id', $id)
             ->set('title', $title)
             ->save();
        return $list;
    }
}
