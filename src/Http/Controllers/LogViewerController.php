<?php
/**
 * Created by PhpStorm.
 * User: szk
 * Date: 2020-05-12
 * Time: 21:36
 */

namespace Dszkng\LumenLogViewer\Http\Controllers;

class LogViewerController extends \Laravel\Lumen\Routing\Controller
{
    public function index()
    {
        return view('log');
    }
}
