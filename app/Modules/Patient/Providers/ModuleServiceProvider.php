<?php

namespace App\Modules\Patient\Providers;

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
        $this->loadTranslationsFrom(module_path('patient', 'Resources/Lang', 'app'), 'patient');
        $this->loadViewsFrom(module_path('patient', 'Resources/Views', 'app'), 'patient');
        $this->loadMigrationsFrom(module_path('patient', 'Database/Migrations', 'app'), 'patient');
        $this->loadConfigsFrom(module_path('patient', 'Config', 'app'));
        $this->loadFactoriesFrom(module_path('patient', 'Database/Factories', 'app'));
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
