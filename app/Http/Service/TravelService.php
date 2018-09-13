<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13 0013
 * Time: 15:11
 */

namespace App\Http\Service;


use App\Models\RunStep;
use App\Models\TravelLog;
use App\Models\TravelLogPoi;
use App\Models\TravelPlan;
use App\Models\TravelPlanPoint;
use Carbon\Carbon;

class TravelService
{
    protected $builder;

    public function saveTravelPlan($userId,$title,$distance)
    {
        $plan = TravelPlan::create([
            TravelPlan::FIELD_ID_USER=>$userId,
            TravelPlan::FIELD_TITLE=>empty($title)?'无':$title,
            TravelPlan::FIELD_DISTANCE=>$distance,
            TravelPlan::FIELD_STATUS=>TravelPlan::ENUM_STATUS_TRAVeLING
        ]);

        return $plan;
    }

    /**
     * 保存旅游站点
     *
     * @author yezi
     *
     * @param $planId
     * @param $points
     * @return array
     */
    public function saveTravelPlanPoint($planId,$points)
    {
        $planArray = [];
        $length = count($points);
        foreach ($points as $key => $point){
            if($key == 0){
                $type = TravelPlanPoint::ENUM_TYPE_END_POINT;
            }elseif($key == ($length - 1)){
                $type = TravelPlanPoint::ENUM_TYPE_START_POINT;
            }else{
                $type = TravelPlanPoint::ENUM_TYPE_ROUTE_POINT;
            }
            array_push($planArray,[
                TravelPlanPoint::FIELD_ID_TRAVEL_PLAN=>$planId,
                TravelPlanPoint::FIELD_ADDRESS_DETAIL=>$point['name'],
                TravelPlanPoint::FIELD_ADDRESS=>$point['address'],
                TravelPlanPoint::FIELD_LATITUDE=>$point['latitude'],
                TravelPlanPoint::FIELD_LONGITUDE=>$point['longitude'],
                TravelPlanPoint::FIELD_SORT=>$point['id'],
                TravelPlanPoint::FIELD_TYPE=>$type,
                TravelPlanPoint::FIELD_CREATED_AT=>Carbon::now(),
                TravelPlanPoint::FIELD_UPDATED_AT=>Carbon::now()
            ]);
        }

        if($planArray){
            TravelPlanPoint::insert($planArray);
        }

        return $planArray;
    }

}