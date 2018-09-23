<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\CollegeArticleService;
use App\Http\Service\QiNiuService;
use App\Models\CollegeArticle;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/20 0020
 * Time: 10:27
 */
class ArticleController extends Controller
{

    private $collegeArticleService;

    public function __construct()
    {
        $this->collegeArticleService = app(CollegeArticleService::class);
    }

    /**
     * 创建文章的视图
     *
     * @author yezi
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createView()
    {
        $token = app(QiNiuService::class)->getToken();

        return view("admin.article.create",["token"=>$token]);
    }

    /**
     * 保存文章
     *
     * @author yzei
     * @return mixed
     */
    public function create(){
        $content = request()->input("content");
        $title = request()->input("title");
        $cover = request()->input('cover');

        $result = $this->collegeArticleService->save(1,$title,$content,[$cover]);

        return webResponse("新建成功",200,$result);
    }

    /**
     * 上传文章图片
     *
     * @author yezi
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws ApiException
     */
    public function uploadImage(\Illuminate\Http\Request $request){
        $filePath =  $request->file('editormd-image-file')->path();
        $token = app(QiNiuService::class)->getToken();
        if($token == ''){
            throw new ApiException("获取token失败",500);
        }

        $result = app(QiNiuService::class)->uploadImage($token,$filePath);
        $data['success'] = 1;
        $data['message'] = '上传成功';
        $data['url'] = env('QI_NIU_DOMAIN').$result['key'];
        return $data;
    }

}