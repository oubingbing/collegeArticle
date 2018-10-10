<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Service\QiNiuService;
use App\Models\CollegeArticle;
use App\Models\Customer;

class IndexController extends Controller
{
    /**
     * 进入管理后台首页
     *
     * @author yezi
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $customer = request()->get('user');

        $token = app(QiNiuService::class)->getToken();

        return view('admin.index',["token"=>$token,"nickname"=>$customer->{Customer::FIELD_NICKNAME}]);
    }

}