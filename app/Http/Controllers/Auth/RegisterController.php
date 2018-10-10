<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\WebException;
use App\Http\Controllers\Controller;
use App\Http\Service\AuthService;
use App\Http\Service\CustomerService;
use App\Models\Customer;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * 注册视图
     *
     * @author yezi
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerView()
    {
        return view('auth.register');
    }

    /**
     * 注册
     *
     * @author yezi
     * @param Request $request
     * @return mixed
     * @throws WebException
     */
    public function register(Request $request)
    {
        $nickname = request()->input("nickname");
        $phone = request()->input("phone");
        $password = request()->input("password");

        $validPhone = validMobile($phone);
        if(!$validPhone){
            throw new WebException("手机号码格式不正确");
        }

        $valid = $this->authService->validRegister($request);
        if(!$valid['valid']){
            throw new WebException($valid['message']);
        }

        try{
            \DB::beginTransaction();

            $result = $this->authService->createCustomer($nickname,$phone,$password);

            //初始化用户信息
            app(CustomerService::class)->initCustomer($result->{Customer::FIELD_ID});

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new WebException($exception);
        }

        return $result;
    }
}
