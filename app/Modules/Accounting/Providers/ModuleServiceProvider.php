<?php

namespace App\Modules\Accounting\Providers;

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
        $this->loadTranslationsFrom(module_path('accounting', 'Resources/Lang', 'app'), 'accounting');
        $this->loadViewsFrom(module_path('accounting', 'Resources/Views', 'app'), 'accounting');
        $this->loadMigrationsFrom(module_path('accounting', 'Database/Migrations', 'app'), 'accounting');
        $this->loadConfigsFrom(module_path('accounting', 'Config', 'app'));
        $this->loadFactoriesFrom(module_path('accounting', 'Database/Factories', 'app'));
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
