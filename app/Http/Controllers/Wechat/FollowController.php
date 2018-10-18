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
use App\Http\Service\ViewLogService;
use App\Models\Customer;
use App\Models\Follow;
use App\Models\Note;
use App\Models\NoteCategory;
use App\Models\OperateStatistics;
use Illuminate\Database\Eloquent\Model;

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
     * 收藏
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

            $operateResult = $this->operateService->subCount($objId,Follow::ENUM_TYPE_NOTE,OperateStatistics::FIELD_FOLLOW,1);
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
     * 我关注的日志列表
     *
     * @author yezi
     * @return array
     */
    public function followNotes()
    {
        $user = request()->input("user");
        $sortBy = request()->input('sort_by', 'desc');
        $filter = request()->input('filter');
        $pageSize = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $builder = $this->followService->builderQuery()
            ->filter($user->id,Follow::ENUM_TYPE_NOTE)
            ->sort(Follow::FIELD_CREATED_AT,$sortBy)
            ->done()
            ->with([
                Follow::REL_NOTE=>function($query){
                    $query->select([
                        Note::FIELD_ID,
                        Note::FIELD_TITLE,
                        Note::FIELD_ID_POSTER,
                        Note::FIELD_ID_CATEGORY,
                        Note::FIELD_ATTACHMENTS,
                        Note::FIELD_CREATED_AT
                    ]);
                }
            ]);
        $list = paginate($builder,$pageParams,"*",function ($item){
            $item->view_number = app(ViewLogService::class)->getViewNumber($item->{Follow::FIELD_ID_OBJ});
            return $item;
        });
        return $list;
    }

    /**
     * 我关注的日志列表
     *
     * @author yezi
     * @return array
     */
    public function followUser()
    {
        $user = request()->input("user");
        $sortBy = request()->input('sort_by', 'desc');
        $filter = request()->input('filter');
        $pageSize = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $builder = $this->followService->builderQuery()
            ->filter($user->id,Follow::ENUM_TYPE_AUTHOR)
            ->sort(Follow::FIELD_CREATED_AT,$sortBy)
            ->done()
            ->with([
                Follow::REL_CUSTOMER=>function($query){
                    $query->select([
                        Customer::FIELD_ID,
                        Customer::FIELD_NICKNAME,
                        Customer::FIELD_AVATAR
                    ]);
                }
            ]);
        $list = paginate($builder,$pageParams,"*",function ($item){
            $item = $this->followService->countUserFollow($item);
            return $item;
        });
        return $list;
    }

    public function followCategory()
    {
        $user = request()->input("user");
        $sortBy = request()->input('sort_by', 'desc');
        $filter = request()->input('filter');
        $pageSize = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $builder = $this->followService->builderQuery()
            ->filter($user->id,Follow::ENUM_TYPE_CATEGORY)
            ->sort(Follow::FIELD_CREATED_AT,$sortBy)
            ->done()
            ->with([
                Follow::REL_CATEGORIES=>function($query){
                    $query->select([
                        NoteCategory::FIELD_ID,
                        NoteCategory::FIELD_ID_POSTER,
                        NoteCategory::FIELD_NAME
                    ]);
                }
            ]);
        $list = paginate($builder,$pageParams,[
            Follow::FIELD_ID,
            Follow::FIELD_ID_USER,
            Follow::FIELD_ID_OBJ
        ],function ($item){
            $item = $this->followService->countUserFollow($item);
            return $item;
        });
        return $list;
    }


}