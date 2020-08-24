<?php
/**
 * Created by PhpStorm.
 * User: szk
 * Date: 2019-11-29
 * Time: 22:54
 */

namespace App\Exceptions;


use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * 自定义接口异常类
 *
 * Class ApiException
 * @package App\Exceptions
 * @author szk
 * @time 2020-05-28 10:36:37
 */
class ApiException extends \RuntimeException
{
    public function __construct(array $error, Throwable $previous = null)
    {
        $state   = $error[0];
        $message = $error[1];
        parent::__construct($message, $state, $previous);
    }

    /**
     * 报告异常
     *
     * @return void
     */
    public function report()
    {
        Log::info('ApiException', $this->getTrace());
    }

    /**
     * 转换异常为 HTTP 响应
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'state' => $this->getCode(),
            'data' => '',
            'msg' => $this->getMessage(),
        ]);
    }
}
