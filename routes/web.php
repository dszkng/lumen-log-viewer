<?php
/**
 * Created by PhpStorm.
 * User: szk
 * Date: 2020-05-12
 * Time: 21:54
 */

use Illuminate\Support\Facades\Route;

Route::prefix('log')->group(function () {
    Route::get('index', 'LogViewerController@index');
    Route::get('getList', 'LogViewerController@getList');
});
