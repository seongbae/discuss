<?php

namespace Seongbae\Discuss;

use Illuminate\Support\ServiceProvider;
use Seongbae\Discuss\Providers\EventServiceProvider;

class DiscussServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'discuss');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'discuss');

        \Spatie\Flash\Flash::levels([
            'success' => 'alert-success',
            'warning' => 'alert-warning',
            'error' => 'alert-error',
        ]);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('discuss.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/js' => resource_path('js'),
            ], 'js');

            $this->publishes([
                __DIR__.'/../resources/img' => public_path('vendor/discuss'),
            ], 'images');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/discuss'),
            ], 'views');

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/discuss'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/discuss'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'discuss');

        // Register the main class to use with the facade
        $this->app->singleton('discuss', function () {
            return new Discuss;
        });

        $this->app->register(EventServiceProvider::class);

        // Register helper
        $file = __DIR__.'/DiscussHelper.php';
        if (file_exists($file)) {
            require_once($file);
        }
    }
}
