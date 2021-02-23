<?php

namespace App\Modules\Attribute\Providers;

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
        $this->loadTranslationsFrom(module_path('attribute', 'Resources/Lang', 'app'), 'attribute');
        $this->loadViewsFrom(module_path('attribute', 'Resources/Views', 'app'), 'attribute');
        $this->loadMigrationsFrom(module_path('attribute', 'Database/Migrations', 'app'), 'attribute');
        $this->loadConfigsFrom(module_path('attribute', 'Config', 'app'));
        $this->loadFactoriesFrom(module_path('attribute', 'Database/Factories', 'app'));
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
