<?php

namespace App\Modules\Humanresource\Providers;

use Caffeinated\Modules\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(module_path('humanresource', 'Resources/Lang', 'app'), 'humanresource');
        $this->loadViewsFrom(module_path('humanresource', 'Resources/Views', 'app'), 'humanresource');
        $this->loadMigrationsFrom(module_path('humanresource', 'Database/Migrations', 'app'), 'humanresource');
        $this->loadConfigsFrom(module_path('humanresource', 'Config', 'app'));
        $this->loadFactoriesFrom(module_path('humanresource', 'Database/Factories', 'app'));
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
