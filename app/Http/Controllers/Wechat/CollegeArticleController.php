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
        $result = CollegeArticle::orderBy(CollegeArticle::FIELD_CREATED_AT,'desc')
            ->select([
                CollegeArticle::FIELD_ID,
                CollegeArticle::FIELD_ID_POSTER,
                CollegeArticle::FIELD_COVER_IMAGE,
                CollegeArticle::FIELD_TITLE,
                CollegeArticle::FIELD_CREATED_AT
            ])
            ->get();

        return $result;
    }

    public function detail($id)
    {
        return CollegeArticle::query()->where(CollegeArticle::FIELD_ID,$id)->first();
    }
}