<?php

namespace App\Http\Controllers\CollegeArticle;

use App\Http\Controllers\Controller;
use App\Models\CollegeArticle;
use Illuminate\Support\Facades\Request;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/20 0020
 * Time: 10:27
 */
class ArticleController extends Controller
{
    public function create(){
        $content = request()->input("content");

        //dd($content);

        $result = CollegeArticle::create([
            CollegeArticle::FIELD_ID_POSTER=>1,
            CollegeArticle::FIELD_TITLE=>"我的大学生四年",
            CollegeArticle::FIELD_CONTENT=>$content
        ]);

        return $result;
    }

    public function getArticle()
    {
        $result = CollegeArticle::find(4);

        //$result->{CollegeArticle::FIELD_CONTENT} = MarkdownEditor::parse($result->{CollegeArticle::FIELD_CONTENT});

        return $result;
    }

}