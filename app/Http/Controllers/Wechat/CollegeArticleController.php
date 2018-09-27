<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/20 0020
 * Time: 11:12
 */

namespace App\Http\Wechat;


use App\Http\Controllers\Controller;
use App\Http\Service\CollegeArticleService;
use App\Models\CollegeArticle;

class CollegeArticleController extends Controller
{
    private $collegeService;

    public function __construct(CollegeArticleService $collegeArticleService)
    {
        $this->collegeService = $collegeArticleService;
    }

    /**
     * 灯塔文章列表
     *
     * @author yezi
     * @return array
     */
    public function article()
    {
        $orderBy = request()->input('order_by', 'created_at');
        $sortBy = request()->input('sort_by', 'desc');
        $filter = request()->input('filter');
        $pageSize = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $type = CollegeArticle::ENUM_COLLEGE_NOTE;

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];

        $selectData = [
            CollegeArticle::FIELD_ID,
            CollegeArticle::FIELD_ID_POSTER,
            CollegeArticle::FIELD_COVER_IMAGE,
            CollegeArticle::FIELD_TITLE,
            CollegeArticle::FIELD_CREATED_AT
        ];
        $query = $this->collegeService->getBuilder()->filter($type)->sort($orderBy,$sortBy)->done();
        $result = paginate($query,$pageParams,$selectData,function ($item){
            return $item;
        });

        return $result;
    }

    /**
     * 文章详情
     *
     * @author yezi
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function detail($id)
    {
        $result = CollegeArticle::query()->where(CollegeArticle::FIELD_ID,$id)->first();

        return $result;
    }

    /**
     * 笔记列表
     *
     * @author yezi
     * @return array
     */
    public function noteList()
    {
        $orderBy = request()->input('order_by', 'created_at');
        $sortBy = request()->input('sort_by', 'desc');
        $filter = request()->input('filter');
        $pageSize = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $type = CollegeArticle::ENUM_NOTE;

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];

        $selectData = [
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

        return $result;
    }
}