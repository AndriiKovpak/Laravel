<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Components\Core\Password\PasswordBrokerManager;

/**
 * Class PasswordResetServiceProvider
 * that handles custom logic for password manipulation
 *
 * @package App\Providers
 */
class PasswordResetServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('auth.password', function ($app) {

            return new PasswordBrokerManager($app);
        });

        $this->app->bind('auth.password.broker', function ($app) {

            return $app->make('auth.password')->broker();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['auth.password', 'auth.password.broker'];
    }
}