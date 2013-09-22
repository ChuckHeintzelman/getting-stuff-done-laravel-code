<?php namespace GSD\Providers;

// File: app/src/GSD/Providers/TodoServiceProvider.php

use Illuminate\Support\ServiceProvider;

class TodoServiceProvider extends ServiceProvider {

    /**
     * Register the service provider
     */
    public function register()
    {
        $this->app['todo'] = $this->app->share(function()
        {
            return new TodoManager;
        });

        $this->app->bind('GSD\Entities\ListInterface', 'GSD\Entities\TodoList');
        $this->app->bind('GSD\Entities\TaskInterface', 'GSD\Entities\Task');
        $this->app->bind('GSD\Entities\TaskCollectionInterface',
            'GSD\Entities\TaskCollection');
        $this->app->bind('GSD\Repositories\TodoRepositoryInterface',
            'GSD\Repositories\TodoRepository');
    }
}
