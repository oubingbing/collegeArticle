<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13 0013
 * Time: 16:47
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\TravelService;

class TravelController extends Controller
{
    protected $travelService;

    public function __construct(TravelService $travelService)
    {
        $this->travelService = $travelService;
    }

    /**
     * 新建旅行计划
     *
     * @author yezi
     *
     * @return mixed
     * @throws ApiException
     */
    public function createTravelPlan()
    {
        $user = request()->input('user');
        $plans = request()->input('plans');
        $distance = request()->input('distance');
        $title = request()->input('title');

        if(collect($plans)->count() <= 1){
            throw new ApiException('站点要两个以上',500);
        }

        $plans = collect(collect($plans)->sortBy('id'))->toArray();

        try {
            \DB::beginTransaction();

            //新建旅行计划
            $travel = $this->travelService->saveTravelPlan($user->id,$title,$distance);
            if(!$travel){
                throw new ApiException('新建失败！',500);
            }
            $result = $this->travelService->saveTravelPlanPoint($travel->id,$plans);
            if(!$result){
                throw new ApiException("新建失败！",501);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new ApiException($e, 60001);
        }

        return $travel;
    }

}