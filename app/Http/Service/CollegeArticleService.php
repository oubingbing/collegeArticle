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

    /**
     * 新建文章
     *
     * @author yezi
     * @param $userId
     * @param $title
     * @param $content
     * @param $cover
     * @param $type
     * @return mixed
     */
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

    /**
     * 构建查询构造器
     *
     * @author yezi
     * @return $this
     */
    public function getBuilder()
    {
        $this->builder = Model::query();

        return $this;
    }

    /**
     * 过滤查询
     *
     * @author yezi
     * @param $type
     * @return $this
     */
    public function filter($type){
        $this->builder->where(Model::FIELD_TYPE,$type);

        return $this;
    }

    /**
     * 排序
     *
     * @author yezi
     * @param $orderBy
     * @param $sort
     * @return $this
     */
    public function sort($orderBy,$sort)
    {
        $this->builder->orderBy($orderBy,$sort);

        return $this;
    }

    /**
     * 构造查询语句结束
     *
     * @author yezi
     * @return mixed
     */
    public function done()
    {
        return $this->builder;
    }

    /**
     * 格式化单挑数据
     *
     * @author yezi
     * @param $item
     * @return mixed
     */
    public function formatSingle($item)
    {
        $item->title_length = strlen($item->{CollegeArticle::FIELD_TITLE});
        $item->user_avatar = '';

        return $item;
    }

}