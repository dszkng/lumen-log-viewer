<?php
/**
 * Created by PhpStorm.
 * User: szk
 * Date: 2020-05-12
 * Time: 21:19
 */

namespace Dszkng\LumenLogViewer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LumenLogViewerServiceProvider extends ServiceProvider
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
        $configPath = __DIR__ . '/../config/lumen-log-viewer.php';

        if (function_exists('config_path')) {
            $publishPath = config_path('lumen-log-viewer.php');
        } else {
            $publishPath = base_path('config/lumen-log-viewer.php');
        }

        $this->publishes([$configPath => $publishPath], 'config');

        Route::group([
            'prefix' => config('lumen-log-viewer.path'),
            'namespace' => 'Dszkng\LumenLogViewer\Http\Controllers',
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
        $configPath = __DIR__ . '/../config/lumen-log-viewer.php';
        $this->mergeConfigFrom($configPath, 'lumen-log-viewer');
    }
}
