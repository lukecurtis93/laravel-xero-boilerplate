<?php

namespace Lukecurtis\LaravelXeroBoilerplate;

use Illuminate\Support\ServiceProvider;
use Lukecurtis\LaravelXeroBoilerplate\Console\Commands\SyncXero;

class LaravelXeroBoilerplateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-xero-boilerplate');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-xero-boilerplate');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/api/webhooks.php');
        $this->app['router']->aliasMiddleware('verify_xero', Lukecurtis\LaravelXeroBoilerplate\Http\Middleware\VerifyXeroToken::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-xero-boilerplate.php'),
            ], 'config');

            if (! class_exists('CreateXeroAccountsTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_xero_accounts_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_xero_accounts_table.php'),
                ], 'migrations');
            }

            if (! class_exists('CreateXeroContactsTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_xero_contacts_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_xero_contacts_table.php'),
                ], 'migrations');
            }
            if (! class_exists('CreateXeroItemsTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_xero_items_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_xero_items_table.php'),
                ], 'migrations');
            }
            if (! class_exists('CreateXeroInvoicesTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_xero_invoices_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_xero_invoices_table.php'),
                ], 'migrations');
            }
            if (! class_exists('CreateXeroLineItemsTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_xero_line_items_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_xero_line_items_table.php'),
                ], 'migrations');
            }
            if (! class_exists('CreateXeroPaymentsTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_xero_payments_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_xero_payments_table.php'),
                ], 'migrations');
            }

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-xero-boilerplate'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-xero-boilerplate'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-xero-boilerplate'),
            ], 'lang');*/
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-xero-boilerplate');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-xero-boilerplate', function () {
            return new LaravelXeroBoilerplate;
        });

        // Registering package commands.
        $this->app->bind('command.laravelxeroboilerplate:sync', SyncXero::class);

        $this->commands([
            'command.laravelxeroboilerplate:sync',
        ]);
    }
}
