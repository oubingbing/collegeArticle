<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13 0013
 * Time: 11:27
 */

namespace App\Http\Service;


use App\Http\DTO\LocationDTO;
use App\Models\LocationLog;

class LocationLogService
{
    /**
     * 保存坐标
     *
     * @param $userId
     * @param LocationDTO $locationDTO
     * @return mixed
     */
    public function save($userId,LocationDTO $locationDTO)
    {
        if($locationDTO->getSpeed() == -1){
            $locationDTO->setSpeed(0);
        }

        $result = LocationLog::create([
            LocationLog::FIELD_ID_USER=>$userId,
            LocationLog::FIELD_LATITUDE=>$locationDTO->getLatitude(),
            LocationLog::FIELD_LONGITUDE=>$locationDTO->getLongitude(),
            LocationLog::FIELD_SPEED=>$locationDTO->getSpeed(),
            LocationLog::FIELD_LOCATE_AT=>$locationDTO->getDate()
        ]);

        return $result;
    }

}