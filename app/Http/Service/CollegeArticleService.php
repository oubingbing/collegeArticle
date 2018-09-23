<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/20 0020
 * Time: 10:56
 */

namespace App\Http\Service;


use App\Models\CollegeArticle as Model;

class CollegeArticleService
{
    public function save($userId,$title,$content,$cover)
    {
        $result = Model::create([
            Model::FIELD_ID_POSTER=>$userId,
            Model::FIELD_TITLE=>$title,
            Model::FIELD_CONTENT=>$content,
            Model::FIELD_COVER_IMAGE=>$cover
        ]);

        return $result;
    }

}