<?php namespace Syntax\Core\Control;

use Illuminate\Support\ServiceProvider;

class CoreBugServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $app = $this->app;

        // Register for fatal error handling
        // TODO: For some reason, Bugsnag_Client#shutdownHandler is never called
        $app->fatal(function (\Exception $exception) use ($app) {
            $app['corebug']->notifyException($exception);
        });

        // Register for exception handling
        $app->error(function (\Exception $exception) use ($app) {
            $app['corebug']->notifyException($exception);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('corebug', function ($app) {
            return new CoreBug();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array("corebug");
    }
}