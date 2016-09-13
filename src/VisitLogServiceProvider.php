<?php
namespace Sarfraznawaz2005\VisitLog;

use Illuminate\Support\ServiceProvider;

class VisitLogServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // routes
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/Http/routes.php';
        }

        // views
        $this->loadViewsFrom(__DIR__ . '/Views', 'visitlog');

        // publish our files over to main laravel app
        $this->publishes([
            __DIR__ . '/Config/visitlog.php' => config_path('visitlog.php'),
            __DIR__ . '/Migrations' => database_path('migrations')
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $browser = new Browser();

        // This helps Facade resolve the actual class
        $this->app->bind('VisitLog', function () use ($browser) {
            return new VisitLog($browser);
        });
    }
}
