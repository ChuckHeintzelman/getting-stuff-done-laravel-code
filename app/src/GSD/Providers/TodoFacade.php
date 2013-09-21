<?php namespace GSD\Providers;

use Illuminate\Support\Facades\Facade;

class TodoFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'todo';
    }
}
