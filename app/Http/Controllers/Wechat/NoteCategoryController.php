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
use App\Http\Service\CustomerService;
use App\Http\Service\FollowService;
use App\Http\Service\NoteCategoryService;
use App\Http\Service\NoteService;
use App\Models\Customer;
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

    /**
     * 获取笔记簿下的笔记列表
     *
     * @author yezi
     * @param $categoryId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     * @throws ApiException
     */
    public function cateGoryDetail($categoryId)
    {
        $user = request()->input("user");

        $category = $this->categoryService->getCategory($categoryId);
        if(!$category){
            throw new ApiException("笔记簿不存在");
        }

        $category->follow_category = app(FollowService::class)->checkFollow($user->id,$category->id,Follow::ENUM_TYPE_CATEGORY);
        $category->follow_author = app(FollowService::class)->checkFollow($user->id,$category->{NoteCategory::FIELD_ID_POSTER},Follow::ENUM_TYPE_AUTHOR);

        $category->{NoteCategory::REL_CUSTOMER};

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

    public function categoryList()
    {
        $orderBy = request()->input('order_by', 'created_at');
        $sortBy = request()->input('sort_by', 'desc');
        $pageSize = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];

        $query = $this->categoryService->getBuilder()
            ->sort($orderBy,$sortBy)
            ->done();
        $selectData = [
            NoteCategory::FIELD_ID,
            NoteCategory::FIELD_NAME,
            NoteCategory::FIELD_ID_POSTER
        ];
        $result = paginate($query,$pageParams,$selectData,function ($item){
            return $item;
        });

        return $result;
    }

    public function getUserCategory($id)
    {
        $user = request()->input("user");

        $customer = app(CustomerService::class)->getCustomerById($id);
        if(!$customer){
            throw new ApiException("用户不存在");
        }

        $resUser["id"] = $customer->id;
        $resUser["nickname"] = $customer->{Customer::FIELD_NICKNAME};
        $resUser["avatar"] = $customer->{Customer::FIELD_AVATAR};
        $resUser["follow_number"] = app(FollowService::class)->countUserFollowById($customer->id);
        $resUser['follow_author'] = app(FollowService::class)->checkFollow($user->id,$customer->id,Follow::ENUM_TYPE_AUTHOR);
        $resUser["categories"] = $this->categoryService->getMyCategories($id);

        return $resUser;
    }

}