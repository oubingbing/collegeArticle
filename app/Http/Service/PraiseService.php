<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15 0015
 * Time: 10:06
 */

namespace App\Http\Service;


use App\Models\PraiseNote as Model;

class PraiseService
{
    public function create($userId,$objId,$type)
    {
        $result = Model::create([
            Model::FIELD_ID_USER=>$userId,
            Model::FIELD_ID_OBJ=>$objId,
            Model::FIELD_TYPE=>$type
        ]);

        return $result;
    }

    public function getPraiseByUserAndObj($userId,$objId,$type)
    {
        $result = Model::query()
            ->where(Model::FIELD_ID_USER,$userId)
            ->where(Model::FIELD_ID_OBJ,$objId)
            ->where(Model::FIELD_TYPE,$type)
            ->first();

        return $result;
    }

    public function cancelPraise($userId,$objId,$type)
    {
        $result = Model::query()
            ->where(Model::FIELD_ID_USER,$userId)
            ->where(Model::FIELD_ID_OBJ,$objId)
            ->where(Model::FIELD_TYPE,$type)
            ->delete();
        return $result;
    }

    public function checkPraise($userId,$objId,$type)
    {
        $praise = $this->getPraiseByUserAndObj($userId,$objId,$type);
        if($praise){
            return true;
        }else{
            return false;
        }
    }
}