<?php

namespace Youke\BaseSettings;

use Youke\BaseSettings\Console\PublishCommand;
use Youke\BaseSettings\Console\GenerateDocsCommand;
use Youke\BaseSettings\Console\PublishViewsCommand;
use Youke\BaseSettings\Console\PublishConfigCommand;
use Illuminate\Support\ServiceProvider as BaseProvider;

class SwaggerServiceProvider extends BaseProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $viewPath = __DIR__ . '/../resources/views';
        $this->loadViewsFrom($viewPath, 'swagger');

        $this->app->router->group(['namespace' => 'Swagger'], function ($route) {
            require __DIR__ . '/web.php';
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/swagger.php';
        $this->mergeConfigFrom($configPath, 'swagger');

        $this->app->singleton('command.swagger.publish', function () {
            return new PublishCommand();
        });

        $this->app->singleton('command.swagger.publish-config', function () {
            return new PublishConfigCommand();
        });

        $this->app->singleton('command.swagger.publish-views', function () {
            return new PublishViewsCommand();
        });

        $this->app->singleton('command.swagger.generate', function () {
            return new GenerateDocsCommand();
        });

        $this->commands(
            'command.swagger.publish',
            'command.swagger.publish-config',
            'command.swagger.publish-views',
            'command.swagger.generate'
        );
    }
}
