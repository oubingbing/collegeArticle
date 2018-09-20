<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/20 0020
 * Time: 11:12
 */

namespace App\Http\Wechat;


use App\Http\Controllers\Controller;
use App\Models\CollegeArticle;

class CollegeArticleController extends Controller
{
    public function article()
    {
        $result = CollegeArticle::orderBy(CollegeArticle::FIELD_CREATED_AT,'desc')->first();



        //$result->{CollegeArticle::FIELD_CONTENT} = $Parsedown->parse($result->{CollegeArticle::FIELD_CONTENT});

        return $result;
    }
}