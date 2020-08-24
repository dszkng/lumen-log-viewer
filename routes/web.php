<?php
/**
 * Created by PhpStorm.
 * User: szk
 * Date: 2020-05-12
 * Time: 21:54
 */

use Illuminate\Support\Facades\Route;

Route::get('control/index', 'LogViewerController@index');
Route::get('getList', 'LogViewerController@getList');

Route::get(config('swagger.routes.docs'), [
    'as' => 'swagger.docs',
    'middleware' => config('swagger.routes.middleware.docs', []),
    'uses' => 'SwaggerController@docs',
]);

Route::get(config('swagger.routes.api'), [
    'as' => 'swagger.api',
    'middleware' => config('swagger.routes.middleware.api', []),
    'uses' => 'SwaggerController@api',
]);

Route::get(config('swagger.routes.assets') . '/{asset}', [
    'as' => 'swagger.asset',
    'middleware' => config('swagger.routes.middleware.asset', []),
    'uses' => 'SwaggerAssetController@index',
]);

Route::get(config('swagger.routes.oauth2_callback'), [
    'as' => 'swagger.oauth2_callback',
    'middleware' => config('swagger.routes.middleware.oauth2_callback', []),
    'uses' => 'SwaggerController@oauth2Callback',
]);
