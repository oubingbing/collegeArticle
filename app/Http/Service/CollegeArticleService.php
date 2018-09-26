<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/20 0020
 * Time: 10:56
 */

namespace App\Http\Service;


use App\Models\CollegeArticle as Model;
use App\Models\CollegeArticle;

class CollegeArticleService
{
    private $builder;

    public function save($userId,$title,$content,$cover,$type)
    {
        $result = Model::create([
            Model::FIELD_ID_POSTER=>$userId,
            Model::FIELD_TITLE=>$title,
            Model::FIELD_CONTENT=>$content,
            Model::FIELD_COVER_IMAGE=>$cover,
            Model::FIELD_TYPE=>$type
        ]);

        return $result;
    }

    public function getBuilder()
    {
        $this->builder = Model::query();

        return $this;
    }

    public function filter($type){
        $this->builder->where(Model::FIELD_TYPE,$type);

        return $this;
    }

    public function sort($orderBy,$sort)
    {
        $this->builder->orderBy($orderBy,$sort);

        return $this;
    }

    public function done()
    {
        return $this->builder;
    }

    public function formatSingle($item)
    {
        $item->title_length = strlen($item->{CollegeArticle::FIELD_TITLE});
        $item->user_avatar = '';

        return $item;
    }

}