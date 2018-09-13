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
use Illuminate\Support\Facades\Request;

class LocationController extends Controller
{
    public function saveLocation(LocationLogService $locationLogService)
    {
        $user = request()->input("user");
        $latitude = request()->input("latitude");
        $longitude = request()->input("longitude");
        $speed = request()->input("speed");
        $date = request()->input("date");

        $locationDTO = new LocationDTO();
        $locationDTO->setLatitude($latitude);
        $locationDTO->setLongitude($longitude);
        $locationDTO->setSpeed($speed);
        $locationDTO->setDate($date);

        $result = $locationLogService->save($user->id,$locationDTO);

        return $result;
    }

}