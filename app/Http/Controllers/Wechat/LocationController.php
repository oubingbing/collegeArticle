<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/28 0028
 * Time: 10:01
 */

namespace App\Http\Wechat;


use App\Http\Controllers\Controller;
use App\Http\DTO\LocationDTO;
use App\Http\Service\LocationLogService;
use App\Http\Service\TravelService;
use App\Models\LocationLog;
use Illuminate\Support\Facades\Request;

class LocationController extends Controller
{
    protected $travelService;

    public function __construct(TravelService $travelService)
    {
        $this->travelService = $travelService;
    }

    public function saveLocation(LocationLogService $locationLogService)
    {
        $user = request()->input("user");
        $latitude = request()->input("latitude");
        $longitude = request()->input("longitude");
        $speed = request()->input("speed");
        $date = request()->input("date");
        $planId = request()->input("plan_id");

        $locationDTO = new LocationDTO();
        $locationDTO->setLatitude($latitude);
        $locationDTO->setLongitude($longitude);
        $locationDTO->setSpeed($speed);
        $locationDTO->setDate($date);
        $locationDTO->setPlanId($planId);

        $result = $locationLogService->save($user->id,$locationDTO);

        return $result;
    }

    public function planLocation(){
        $user = request()->input("user");
        $pageSize = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);

        $plan = $this->travelService->getTravelingPlan($user->id);
        if(!$plan){
            return null;
        }

        $locationLogs = LocationLog::query()
            ->select([
                LocationLog::FIELD_ID,
                LocationLog::FIELD_ADDRESS,
                LocationLog::FIELD_ADDRESS_FORMAT,
                LocationLog::FIELD_LATITUDE,
                LocationLog::FIELD_LONGITUDE,
                LocationLog::FIELD_SPEED,
                LocationLog::FIELD_LOCATE_AT
            ])
            ->where(LocationLog::FIELD_ID_PLAN,$plan->id)
            ->get();

        return $locationLogs;
    }

}