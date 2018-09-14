<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13 0013
 * Time: 15:11
 */

namespace App\Http\Service;

use App\Models\TravelPlan;
use App\Models\TravelPlanPoint;
use Carbon\Carbon;

class TravelService
{
    protected $builder;

    /**
     * 新建旅途
     *
     * @param $userId
     * @param $title
     * @param $distance
     * @return mixed
     */
    public function saveTravelPlan($userId,$title,$distance)
    {
        $plan = TravelPlan::create([
            TravelPlan::FIELD_ID_USER=>$userId,
            TravelPlan::FIELD_TITLE=>empty($title)?'无':$title,
            TravelPlan::FIELD_DISTANCE=>$distance,
            TravelPlan::FIELD_STATUS=>TravelPlan::ENUM_STATUS_TRAVELING
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

    /**
     * 获取用户的旅途计划
     *
     * @author yezi
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getTravelingPlan($userId)
    {
        $travelPlan = TravelPlan::query()
            ->select([TravelPlan::FIELD_ID,TravelPlan::FIELD_DISTANCE,TravelPlan::FIELD_TITLE,TravelPlan::FIELD_STATUS,TravelPlan::FIELD_CREATED_AT])
            ->where(TravelPlan::FIELD_ID_USER,$userId)
            ->where(TravelPlan::FIELD_STATUS,'!=',TravelPlan::ENUM_STATUS_END)
            ->first();

        return $travelPlan;
    }

    /**
     * 获取旅行计划的站点
     *
     * @author yezi
     * @param $planId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function planPoints($planId)
    {
        $points = TravelPlanPoint::query()
            ->select([
                TravelPlanPoint::FIELD_ID,
                TravelPlanPoint::FIELD_ID_TRAVEL_PLAN,
                TravelPlanPoint::FIELD_LONGITUDE,
                TravelPlanPoint::FIELD_LATITUDE,
                TravelPlanPoint::FIELD_ADDRESS,
                TravelPlanPoint::FIELD_ADDRESS_DETAIL,
                TravelPlanPoint::FIELD_SORT,
                TravelPlanPoint::FIELD_CREATED_AT
            ])
            ->where(TravelPlanPoint::FIELD_ID_TRAVEL_PLAN,$planId)
            ->get();

        return $points;
    }

}