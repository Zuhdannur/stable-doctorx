<?php

namespace App\Modules\Indonesia\Providers;

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
        $this->loadTranslationsFrom(module_path('indonesia', 'Resources/Lang', 'app'), 'indonesia');
        $this->loadViewsFrom(module_path('indonesia', 'Resources/Views', 'app'), 'indonesia');
        $this->loadMigrationsFrom(module_path('indonesia', 'Database/Migrations', 'app'), 'indonesia');
        $this->loadConfigsFrom(module_path('indonesia', 'Config', 'app'));
        $this->loadFactoriesFrom(module_path('indonesia', 'Database/Factories', 'app'));
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
