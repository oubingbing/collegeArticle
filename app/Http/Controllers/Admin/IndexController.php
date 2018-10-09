<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Service\QiNiuService;
use App\Models\CollegeArticle;

class IndexController extends Controller
{
    public function dashboard()
    {
       // $user = request()->get('user');

        return view('admin.dashboard');
    }

    /**
     * 进入管理后台首页
     *
     * @author yezi
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //$user = request()->get('user');
        //$app = $user->app();

        $token = app(QiNiuService::class)->getToken();

        return view('admin.index',["token"=>$token]);
    }

    /**
     * 获取七牛上传凭证
     *
     * @author yezi
     *
     * @return mixed
     */
    public function getUploadToken()
    {
        $token = app(QiNiuService::class)->getToken();

        return webResponse('ok',200,$token);
    }

    public function test(){
        $result = CollegeArticle::find(13);

        //$result->{CollegeArticle::FIELD_CONTENT} = MarkdownEditor::parse($result->{CollegeArticle::FIELD_CONTENT});
        return view("test.edit",["name"=>$result->{CollegeArticle::FIELD_CONTENT}]);
    }

}