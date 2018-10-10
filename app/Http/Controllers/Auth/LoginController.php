<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\TokenService;
use App\Http\Service\WeChatService;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{

    protected $tokenService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tokenService = app(TokenService::class);
    }

    /**
     * 登录
     *
     * @author yezi
     *
     * @return mixed
     */
    public function apiLogin()
    {
        $iv = request()->input('iv');
        $code = request()->input('code');
        $encryptedData = request()->input('encrypted_data');

        try{
            DB::beginTransaction();

            $result = $this->wechatLogin($code,$iv,$encryptedData);

            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }

        return $result;
    }

    /**
     * 微信登录
     *
     * @author yezi
     *
     * @return mixed
     */
    public function weChatLogin($code,$iv,$encryptedData)
    {
        $weChatService = new WeChatService();
        $userInfo = $weChatService->getSessionInfo($code,$iv,$encryptedData);
        $token = $this->tokenService->createToken($userInfo);
        return $token;
    }

    public function loginView()
    {
        return view("auth.login");
    }

    public function login()
    {
        return 'login';
    }

}
