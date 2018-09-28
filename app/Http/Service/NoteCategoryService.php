<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28 0028
 * Time: 15:54
 */

namespace App\Http\Service;


use App\Models\NoteCategory as Model;

class NoteCategoryService
{
    /**
     * 保存日记簿
     *
     * @author yezi
     * @param $userId
     * @param $name
     * @param $type
     * @return mixed
     */
    public function create($userId,$name,$type)
    {
        $result = Model::create([
            Model::FIELD_ID_WEB_USER=>$userId,
            Model::FIELD_NAME=>$name,
            Model::FIELD_TYPE=>$type
        ]);

        return $result;
    }

    public function checkRepeat($userId,$name)
    {
        $result = $this->findByUserAndName($userId,$name);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function findByUserAndName($userId,$name)
    {
        $result = Model::query()->where(Model::FIELD_ID_WEB_USER,$userId)->where(Model::FIELD_NAME,$name)->first();
        return $result;
    }

}