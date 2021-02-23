<?php

namespace App\Modules\Booking\Providers;

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
        $this->loadTranslationsFrom(module_path('booking', 'Resources/Lang', 'app'), 'booking');
        $this->loadViewsFrom(module_path('booking', 'Resources/Views', 'app'), 'booking');
        $this->loadMigrationsFrom(module_path('booking', 'Database/Migrations', 'app'), 'booking');
        $this->loadConfigsFrom(module_path('booking', 'Config', 'app'));
        $this->loadFactoriesFrom(module_path('booking', 'Database/Factories', 'app'));
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
