<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15 0015
 * Time: 13:40
 */

namespace App\Http\Service;

use App\Models\Note;
use App\Models\OperateStatistics;
use App\Models\ViewLog as Model;

class ViewLogService
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

    public function getViewLogByUserAndObj($userId,$objId,$type)
    {
        $result = Model::query()
            ->where(Model::FIELD_ID_USER,$userId)
            ->where(Model::FIELD_ID_OBJ,$objId)
            ->where(Model::FIELD_TYPE,$type)
            ->first();

        return $result;
    }

    public function checkView($userId,$objId,$type)
    {
        $praise = $this->getViewLogByUserAndObj($userId,$objId,$type);
        if($praise){
            return true;
        }else{
            return false;
        }
    }

    public function getBuilder($userId,$type)
    {
        $result = Model::query()
            ->with([Model::REL_NOTE=>function($query){
                $query->select([
                    Note::FIELD_ID,
                    Note::FIELD_TITLE,
                    Note::FIELD_ID_POSTER,
                    Note::FIELD_ID_CATEGORY,
                    Note::FIELD_ATTACHMENTS,
                    Note::FIELD_CREATED_AT
                ]);
            }])
            ->where(Model::FIELD_ID_USER,$userId)
            ->where(Model::FIELD_TYPE,$type)
            ->orderBy(Model::FIELD_CREATED_AT,"desc");
        return $result;
    }

    public function getViewNumber($noteId)
    {
        $result = OperateStatistics::query()
            ->where(OperateStatistics::FIELD_ID_OBJ,$noteId)
            ->where(OperateStatistics::FIELD_TYPE,OperateStatistics::ENUM_TYPE_NOTE)
            ->first();
        if($result){
            return $result->{OperateStatistics::FIELD_VIEW};
        }else{
            return 0;
        }
    }
}