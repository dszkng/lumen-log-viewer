<?php
/**
 * Created by PhpStorm.
 * User: szk
 * Date: 2020-05-12
 * Time: 21:54
 */

use Illuminate\Support\Facades\Route;

Route::get('index', 'LumenLogViewerController@index');
Route::get('getList', 'LumenLogViewerController@getList');
