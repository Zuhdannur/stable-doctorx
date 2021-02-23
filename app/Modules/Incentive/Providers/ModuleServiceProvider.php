<?php

namespace App\Modules\Incentive\Providers;

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
        $this->loadTranslationsFrom(module_path('incentive', 'Resources/Lang', 'app'), 'incentive');
        $this->loadViewsFrom(module_path('incentive', 'Resources/Views', 'app'), 'incentive');
        $this->loadMigrationsFrom(module_path('incentive', 'Database/Migrations', 'app'), 'incentive');
        $this->loadConfigsFrom(module_path('incentive', 'Config', 'app'));
        $this->loadFactoriesFrom(module_path('incentive', 'Database/Factories', 'app'));
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
