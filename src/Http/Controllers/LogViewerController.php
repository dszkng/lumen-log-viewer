<?php
/**
 * Created by PhpStorm.
 * User: szk
 * Date: 2020-05-12
 * Time: 21:36
 */

namespace Youke\BaseSettings\Http\Controllers;

use Youke\BaseSettings\LogViewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LogViewerController extends \Laravel\Lumen\Routing\Controller
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var LogViewer
     */
    private $log_viewer;

    /**
     * @var string
     */
//    protected $view_log = 'lumen-log-viewer::log';
    protected $view_log = 'log';

    /**
     * LogViewerController constructor.
     */
    public function __construct()
    {
        $this->log_viewer = new LogViewer();
        $this->request = app('request');
    }

    public function index()
    {
        $files = $this->log_viewer->getFiles(true);

        return view('log-viewer::index', compact('files'));
    }

    public function getList(Request $request)
    {
        return [
            'code' => 0,
            'data' => $this->log_viewer->all2(intval($request->get('index', 0))),
//            'data' => [
//                [
//                    'context' => $current[3],
//                    'level' => $level,
//                    'folder' => $this->folder,
//                    'level_class' => $this->level->cssClass($level),
//                    'level_img' => $this->level->img($level),
//                    'date' => $current[1],
//                    'text' => $current[4],
//                    'in_file' => isset($current[5]) ? $current[5] : null,
//                    'stack' => preg_replace("/^\n*/", '', $log_data[$i])
//                ]
//            ],
            'msg' => '',
        ];
    }
}
