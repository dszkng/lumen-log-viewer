<?php
/**
 * Created by PhpStorm.
 * User: szk
 * Date: 2020-05-12
 * Time: 21:19
 */

namespace Youke\BaseSettings;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseProvider;

class LumenLogViewerServiceProvider extends BaseProvider
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
        $configPath = __DIR__ . '/../config/base-settings.php';

        if (function_exists('config_path')) {
            $publishPath = config_path('base-settings.php');
        } else {
            $publishPath = base_path('config/base-settings.php');
        }

        $this->publishes([$configPath => $publishPath], 'config');

        Route::group([
            'prefix' => config('base-settings.path'),
            'namespace' => 'Youke\BaseSettings\Http\Controllers',
//            'middleware' => 'web',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });

        if ($this->app->has('view')) {
            $viewPath = __DIR__ . '/../resources/views';
            $this->loadViewsFrom($viewPath, 'lumen-log-viewer');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/base-settings.php';
        $this->mergeConfigFrom($configPath, 'lumen-log-viewer');
    }
}
