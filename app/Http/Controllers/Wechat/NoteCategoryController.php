<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/10/14
 * Time: 17:03
 */

namespace App\Http\Wechat;


use App\Http\Controllers\Controller;
use App\Http\Service\NoteCategoryService;
use App\Models\User;

class NoteCategoryController extends Controller
{
    private $categoryService;

    public function __construct(NoteCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

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
     * 笔记列表
     *
     * @author yezi
     * @return array
     */
    public function noteCategoryList()
    {
        $orderBy = request()->input('order_by', 'created_at');
        $sortBy = request()->input('sort_by', 'desc');
        $filter = request()->input('filter');
        $pageSize = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];

        /*$selectData = [
            CollegeArticle::FIELD_ID,
            CollegeArticle::FIELD_ID_POSTER,
            CollegeArticle::FIELD_COVER_IMAGE,
            CollegeArticle::FIELD_TITLE,
            CollegeArticle::FIELD_CREATED_AT,
            CollegeArticle::FIELD_CONTENT
        ];
        $query = $this->collegeService->getBuilder()->filter($type)->sort($orderBy,$sortBy)->done();
        $result = paginate($query,$pageParams,$selectData,function ($item){
            $item = $this->collegeService->formatSingle($item);
            return $item;
        });

        return $result;*/
    }

}