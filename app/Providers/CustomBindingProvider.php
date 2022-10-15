<?php

namespace App\Providers;
use App\Repository\DataManagerInterface;
use App\Repository\DataManager;
use App\Repository\OperationManager;
use App\Repository\OperationManagerInterface;
use Illuminate\Support\ServiceProvider;

class CustomBindingProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
         $this->app->singleton(
            OperationManagerInterface::class,OperationManager::class,
         );
        $this->app->singleton(
            DataManagerInterface::class,DataManager::class,
         );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
