<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15 0015
 * Time: 13:39
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\CommonService;
use App\Http\Service\OperateStaticsService;
use App\Http\Service\ViewLogService;
use App\Models\OperateStatistics;
use App\Models\ViewLog;

class ViewLogController extends Controller
{
    private $viewService;
    private $operateService;

    public function __construct(ViewLogService $viewLogService,OperateStaticsService $operateStaticsService)
    {
        $this->viewService = $viewLogService;
        $this->operateService = $operateStaticsService;
    }

    /**
     * 浏览
     *
     * @author yezi
     * @return mixed
     * @throws ApiException
     */
    public function view()
    {
        $user = request()->input('user');
        $objId = request()->input("obj_id");
        $type = request()->input("type");

        $checkResult = app(CommonService::class)->checkObj($objId,$type);
        if(!$checkResult){
            throw new ApiException("操作对象不存在");
        }

        try{
            \DB::beginTransaction();

            $view = $this->viewService->getViewLogByUserAndObj($user->id,$objId,$type);
            if(!$view){
                $saveResult = $this->viewService->create($user->id,$objId,$type);
                if(!$saveResult){
                    throw new ApiException("保存数据失败");
                }
            }

            $operateResult = $this->operateService->addCount($objId,ViewLog::ENUM_TYPE_NOTE,OperateStatistics::FIELD_VIEW,1);
            if(!$operateResult){
                throw new ApiException("保存数据失败");
            }

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new ApiException($exception->getMessage());
        }

        return $operateResult;
    }

    public function viewLogs()
    {
        $user = request()->input("user");
        $sortBy = request()->input('sort_by', 'desc');
        $filter = request()->input('filter');
        $pageSize = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];

        $builder = $this->viewService->getBuilder($user->id,ViewLog::ENUM_TYPE_NOTE);
        $list = paginate($builder,$pageParams,"*",function ($item){
            $item->view_number = $this->viewService->getViewNumber($item);
            return $item;
        });
        return $list;
    }
}