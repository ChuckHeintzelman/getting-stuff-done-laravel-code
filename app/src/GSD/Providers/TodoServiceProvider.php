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
    }
}
