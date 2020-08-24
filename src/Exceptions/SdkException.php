<?php
/**
 * Created by PhpStorm.
 * User: szk
 * Date: 2019-11-29
 * Time: 22:54
 */

namespace Youke\BaseSeetings\Exceptions;


use Youke\BaseSeetings\Support\ErrorCode;
use Throwable;

/**
 * sdk异常类
 *
 * Class ApiException
 * @package App\Exceptions
 * @author szk
 * @time 2020-05-28 10:36:37
 */
class SdkException extends \RuntimeException implements YoukeException
{
    public function __construct($msg, Throwable $previous = null)
    {
        if (is_array($msg)) {
            $message = $msg[1] ?? ErrorCode::SDK_ERROR[1];
            $code = $msg[0] ?? ErrorCode::SDK_ERROR[0];
        } elseif (is_string($msg)) {
            $message = $msg;
            $code = ErrorCode::SDK_ERROR[0];
        } else {
            $message = ErrorCode::SDK_ERROR[1];
            $code = ErrorCode::SDK_ERROR[0];
        }

        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
