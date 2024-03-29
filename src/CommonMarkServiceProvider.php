<?php

namespace TinyPixel\CommonMark;

use Roots\Acorn\ServiceProvider;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extras\CommonMarkExtrasExtension;

/**
 * Common Mark Service Provider
 */
class CommonMarkServiceProvider extends ServiceProvider
{
    /**
     * Register
     */
    public function register()
    {
        $this->app->bind('commonmark.environment', function ($app) {
            $environment = Environment::createCommonMarkEnvironment();

            if ($app['config']->get('commonmark.environment.extras')) {
                $environment->addExtension(new CommonMarkExtrasExtension());
            }

            return $environment;
        });

        $this->app->singleton('commonmark', function ($app) {
            return new CommonMarkConverter(
                $app['config']->get('commonmark.converter'),
                $app->make('commonmark.environment')
            );
        });
    }

    /**
     * Boot
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/commonmark.php' => $this->app->configPath('commonmark.php'),
        ]);
    }
}
