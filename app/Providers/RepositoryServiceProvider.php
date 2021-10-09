<?php

namespace App\Providers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Container\Container;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerRepository();
    }

    /*
    * Register Repository for Ioc
    */
    private function registerRepository()
    {
        $this->app->bind("App\Repository\Contracts\RepositoryInterface", "App\Repository\Eloquent\BaseRepository");
        // user
        $this->app->bind("App\Repository\Contracts\UserInterface", "App\Repository\Eloquent\UserRepository");
        // config app
        $this->app->bind("App\Repository\Contracts\AppConfigInterface", "App\Repository\Eloquent\AppConfigRepository");

    }
}

