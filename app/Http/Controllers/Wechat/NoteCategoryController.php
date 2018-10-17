<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/10/14
 * Time: 17:03
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\FollowService;
use App\Http\Service\NoteCategoryService;
use App\Http\Service\NoteService;
use App\Models\Follow;
use App\Models\Note;
use App\Models\NoteCategory;
use App\Models\OperateStatistics;
use App\Models\User;

class NoteCategoryController extends Controller
{
    private $categoryService;
    private $noteService;

    public function __construct(NoteCategoryService $categoryService,NoteService $noteService)
    {
        $this->categoryService = $categoryService;
        $this->noteService = $noteService;
    }

    /**
     * 获取我的笔记簿
     *
     * @author yezi
     * @return \Illuminate\Database\Eloquent\Collection|void|static[]
     */
    public function myCategories()
    {
        $user = request()->input("user");
        $customer = $user->{User::REL_CUSTOMER};
        if(!$customer){
            return;
        }

        $list = $this->categoryService->getMyCategories($customer->id);

        return $list;
    }

    public function cateGoryDetail($categoryId)
    {
        $user = request()->input("user");

        $category = $this->categoryService->getCategory($categoryId);
        if(!$category){
            throw new ApiException("笔记簿不存在");
        }

        $category->follow_category = app(FollowService::class)->checkFollow($user->id,$category->id,Follow::ENUM_TYPE_CATEGORY);
        $category->follow_author = app(FollowService::class)->checkFollow($user->id,$category->{NoteCategory::FIELD_ID_POSTER},Follow::ENUM_TYPE_AUTHOR);

        $category->notes = collect($this->noteService->getNotesByCategoryId($categoryId))->map(function ($item){
            $static = $item->{Note::REL_STATICS};
            if($static){
                $item->view = $static->{OperateStatistics::FIELD_VIEW};
                $item = collect($item)->forget(Note::REL_STATICS)->all();
            }else{
                $item->view = 0;
            }
            return $item;
        });

        return $category;
    }

}