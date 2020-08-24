<?php

namespace App\Http\Middleware;

use App\Common\ErrorCode;
use App\Common\JsonResponse;
use App\Exceptions\AccountParseException;
use App\Exceptions\SdkException;
use App\Support\Traits\AccountCheck;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Config;
use ItFarm\PhpSdk\Client;

class Authenticate
{
    use JsonResponse, AccountCheck;

    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    private $accountApis = [
        'main.php/json/register/phone',
        'main.php/json/register/email',
        'main.php/json/login/phone',
        'main.php/json/login/email',
        'main.php/json/captcha/phone',
        'main.php/json/captcha/email',
        'main.php/json/upload/upload',
        'main.php/json/login/loginOut',
        'main.php/json/setting/update',
        'main.php/json/change_password/phone',
        'main.php/json/account/resetpwd_by_phone',
        'main.php/json/account/resetpwd_by_email',
        'main.php/json/captcha/picture',
        'main.php/json/captcha/picture/verify',
        'main.php/json/login/parseToken',
        'main.php/json/bind/info',
    ];

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        /** 检查账号是否来源OA */
        $this->checkIfOaAccount($request);

        try {
            $client = Client::getInstance();
            $client->setTimeout(10000);
            $requestStack = $request->header('requeststack', '');
            $chain        = json_decode($requestStack, true);
            $appId        = Config::get('service.IDG_APPID', '');

            if (!$chain ||
                !isset($chain[0]['appkey']) ||
                !isset($chain[0]['channel']) ||
                !$chain[0]['appkey'] ||
                !$chain[0]['channel']
            ) {
                throw new AccountParseException(ErrorCode::APPKEY_CHANNEL_IS_INVALID);
            }

            $currAppKey  = $chain[0]['appkey'];
            $currChannel = $chain[0]['channel'];

            $client->setAppInfo(
                $appId,
                $currAppKey,
                $currChannel,
                Config::get('service.IDG_VERSION', '')
            );

            $currSubOrgKey = $client->getCurrentSubOrgKey();

            if (!$currSubOrgKey) {
                throw new AccountParseException(ErrorCode::SUBORGKEY_IS_INVALID);
            }

            $client->setSubOrgKey($currSubOrgKey);

            $request->merge(compact('currAppKey', 'currChannel', 'currSubOrgKey'));

        } catch (\Exception $e) {
            throw new SdkException($e->getMessage());
        }

        return $next($request);
    }
}
