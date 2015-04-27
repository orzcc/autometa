<?php namespace Orzcc\AutoMeta\Providers;

use Orzcc\AutoMeta\AutoMeta;
use Illuminate\Support\ServiceProvider;

class AutoMetaServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../resources/config/autometa.php' => config_path('autometa.php')
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../../resources/config/autometa.php', 'autometa'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('autometa', function ($app) {
            return new AutoMeta($app['config']->get('autometa', []));
        });

        $this->app->bind('Orzcc\AutoMeta\Contracts\MetaTags', 'Orzcc\AutoMeta\MetaTags');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

}