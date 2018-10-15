<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15 0015
 * Time: 10:20
 */

namespace App\Http\Service;


use App\Models\OperateStatistics as Model;

class OperateStaticsService
{
    public function create($objId,$type,$field,$number)
    {
        $result = Model::create([
            Model::FIELD_ID_OBJ=>$objId,
            Model::FIELD_TYPE=>$type,
            $field=>$number
        ]);

        return $result;
    }

    public function addCount($objId,$type,$field,$number)
    {
        $operate = $this->getOperateStatistics($objId,$type);
        if($operate){
            $operate->$field += $number;
            $result = $operate->save();
        }else{
            $result = $this->create($objId,$type,$field,$number);
        }

        return $result;
    }

    public function subCount($objId,$type,$field,$number)
    {
        $operate = $this->getOperateStatistics($objId,$type);
        if($operate){
            if($operate->$field <= 0){
                $result = $operate;
            }else{
                $operate->$field -= $number;
                $result = $operate->save();
            }
        }else{
            $result = $this->create($objId,$type,$field,$number);
        }

        return $result;
    }

    public function getOperateStatistics($objId,$type)
    {
        $result = Model::query()->where(Model::FIELD_ID_OBJ,$objId)->where(Model::FIELD_TYPE,$type)->first();
        return $result;
    }

}