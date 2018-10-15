<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15 0015
 * Time: 11:24
 */

namespace App\Http\Service;

use App\Models\Follow as Model;
use App\Models\Note;

class FollowService
{
    private $builder;

    public function create($userId,$objId,$type)
    {
        $result = Model::create([
            Model::FIELD_ID_USER=>$userId,
            Model::FIELD_ID_OBJ=>$objId,
            Model::FIELD_TYPE=>$type
        ]);

        return $result;
    }

    public function getFollowByUserAndObj($userId,$objId,$type)
    {
        $result = Model::query()
            ->where(Model::FIELD_ID_USER,$userId)
            ->where(Model::FIELD_ID_OBJ,$objId)
            ->where(Model::FIELD_TYPE,$type)
            ->first();

        return $result;
    }

    public function cancelFollow($userId,$objId,$type)
    {
        $result = Model::query()
            ->where(Model::FIELD_ID_USER,$userId)
            ->where(Model::FIELD_ID_OBJ,$objId)
            ->where(Model::FIELD_TYPE,$type)
            ->delete();
        return $result;
    }

    public function checkFollow($userId,$objId,$type)
    {
        $praise = $this->getFollowByUserAndObj($userId,$objId,$type);
        if($praise){
            return true;
        }else{
            return false;
        }
    }

    public function builderQuery()
    {
        $this->builder = Model::query();

        return $this;
    }

    public function filter($userId,$type)
    {
        $this->builder->where(Model::FIELD_ID_USER,$userId)->where(Model::FIELD_TYPE,$type);

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

    public function countUserFollow($item)
    {
        $item->follow_number = Model::query()
            ->where(Model::FIELD_ID_OBJ,$item->{Model::FIELD_ID_OBJ})
            ->where(Model::FIELD_TYPE,Model::ENUM_TYPE_AUTHOR)
            ->count();

        return $item;
    }
}