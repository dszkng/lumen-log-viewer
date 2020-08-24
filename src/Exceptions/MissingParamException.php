<?php
/**
 * Created by PhpStorm.
 * User: szk
 * Date: 2019-11-29
 * Time: 22:54
 */

namespace App\Exceptions;


use App\Common\ErrorCode;
use Throwable;

/**
 * Invalid异常类
 *
 * Class ApiException
 * @package App\Exceptions
 * @author szk
 * @time 2020-05-28 10:36:37
 */
class MissingParamException extends \RuntimeException
{
    public function __construct(string $msg, Throwable $previous = null)
    {
        parent::__construct(
            ErrorCode::VERIFY_ERROR[1] . '[' . $msg . ']',
            ErrorCode::VERIFY_ERROR[0],
            $previous
        );
    }
}
