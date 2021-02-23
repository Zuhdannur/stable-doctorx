<?php

namespace App\Modules\Room\Providers;

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
        $this->loadTranslationsFrom(module_path('room', 'Resources/Lang', 'app'), 'room');
        $this->loadViewsFrom(module_path('room', 'Resources/Views', 'app'), 'room');
        $this->loadMigrationsFrom(module_path('room', 'Database/Migrations', 'app'), 'room');
        $this->loadConfigsFrom(module_path('room', 'Config', 'app'));
        $this->loadFactoriesFrom(module_path('room', 'Database/Factories', 'app'));
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
