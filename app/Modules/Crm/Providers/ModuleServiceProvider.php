<?php

namespace App\Modules\Crm\Providers;

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
        $this->loadTranslationsFrom(module_path('crm', 'Resources/Lang', 'app'), 'crm');
        $this->loadViewsFrom(module_path('crm', 'Resources/Views', 'app'), 'crm');
        $this->loadMigrationsFrom(module_path('crm', 'Database/Migrations', 'app'), 'crm');
        $this->loadConfigsFrom(module_path('crm', 'Config', 'app'));
        $this->loadFactoriesFrom(module_path('crm', 'Database/Factories', 'app'));
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
