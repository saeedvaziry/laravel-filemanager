<?php

namespace SaeedVaziry\LaravelFilemanager;

use Illuminate\Support\ServiceProvider;

class LaravelFilemanagerServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

        // routes
        if (!$this->app->routesAreCached()) 
            require __DIR__.'/app/Http/routes.php';

        // views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'filemanager');

        // translations
        $this->loadTranslationsFrom(__DIR__.'/resources/lang/', 'filemanager');

        // publishes
        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/filemanager'),
            __DIR__.'/resources/lang' => resource_path('lang/vendor/filemanager'),
            __DIR__.'/config/filemanager.php' => config_path('filemanager.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
    }
}