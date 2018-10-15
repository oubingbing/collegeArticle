<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15 0015
 * Time: 11:23
 */

namespace App\Http\Wechat;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\CommonService;
use App\Http\Service\FollowService;
use App\Http\Service\OperateStaticsService;
use App\Models\Follow;
use App\Models\OperateStatistics;

class FollowController extends Controller
{

    private $followService;
    private $operateService;

    public function __construct(FollowService $followService,OperateStaticsService $operateStaticsService)
    {
        $this->followService = $followService;
        $this->operateService = $operateStaticsService;
    }

    /**
     * 点赞
     *
     * @author yezi
     * @return mixed
     * @throws ApiException
     */
    public function follow()
    {
        $user = request()->input('user');
        $objId = request()->input("obj_id");
        $type = request()->input("type");

        $checkResult = app(CommonService::class)->checkObj($objId,$type);
        if(!$checkResult){
            throw new ApiException("操作对象不存在");
        }

        $praise = $this->followService->getFollowByUserAndObj($user->id,$objId,$type);
        if($praise){
            throw new ApiException("已点赞");
        }

        try{
            \DB::beginTransaction();

            $saveResult = $this->followService->create($user->id,$objId,$type);
            if(!$saveResult){
                throw new ApiException("保存数据失败");
            }

            $operateResult = $this->operateService->addCount($objId,Follow::ENUM_TYPE_NOTE,OperateStatistics::FIELD_PRAISE,1);
            if(!$operateResult){
                throw new ApiException("保存数据失败");
            }

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new ApiException($exception->getMessage());
        }

        return $saveResult;
    }

    /**
     * 取消点赞
     *
     * @author yezi
     * @return mixed
     * @throws ApiException
     */
    public function cancelFollow()
    {
        $user = request()->input('user');
        $objId = request()->input("obj_id");
        $type = request()->input("type");

        $checkResult = app(CommonService::class)->checkObj($objId,$type);
        if(!$checkResult){
            throw new ApiException("操作对象不存在");
        }

        $praise = $this->followService->getFollowByUserAndObj($user->id,$objId,$type);
        if(!$praise){
            throw new ApiException("无点赞记录");
        }

        try{
            \DB::beginTransaction();

            $saveResult = $this->followService->cancelFollow($user->id,$objId,$type);
            if(!$saveResult){
                throw new ApiException("保存数据失败");
            }

            $operateResult = $this->operateService->subCount($objId,Follow::ENUM_TYPE_NOTE,OperateStatistics::FIELD_PRAISE,1);
            if(!$operateResult){
                throw new ApiException("保存数据失败");
            }

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new ApiException($exception->getMessage());
        }

        return $saveResult;
    }

}