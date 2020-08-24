<?php
/**
 * Created by PhpStorm.
 * User: szk
 * Date: 2019-11-29
 * Time: 22:54
 */

namespace App\Exceptions;

use Throwable;

/**
 * 账号解析异常类
 *
 * Class ApiException
 * @package App\Exceptions
 */
class AccountParseException extends \RuntimeException
{
    public function __construct(array $err, Throwable $previous = null)
    {
        parent::__construct($err[1], $err[0], $previous);
    }
}
