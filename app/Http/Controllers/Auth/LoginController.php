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
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/admin';
    protected $tokenService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->tokenService = app(TokenService::class);
    }

    /**
     * 默认是admin guard
     *
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard|mixed
     */
    protected function guard()
    {
        return Auth::guard('admin');
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

}
