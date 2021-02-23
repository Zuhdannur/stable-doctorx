<?php

namespace App\Modules\Billing\Providers;

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
        $this->loadTranslationsFrom(module_path('billing', 'Resources/Lang', 'app'), 'billing');
        $this->loadViewsFrom(module_path('billing', 'Resources/Views', 'app'), 'billing');
        $this->loadMigrationsFrom(module_path('billing', 'Database/Migrations', 'app'), 'billing');
        $this->loadConfigsFrom(module_path('billing', 'Config', 'app'));
        $this->loadFactoriesFrom(module_path('billing', 'Database/Factories', 'app'));
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
