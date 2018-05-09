<?php

namespace App\Providers;

use App\Repositories\GroupsAndPermissionRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
          'App\Repositories\GroupsAndPermissionRepository'
        );

        $this->app->singleton('GroupsAndModulesRepo', function()
        {
            return new GroupsAndPermissionRepository();

        });

    }
}
