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

    public function article()
    {
        $result = CollegeArticle::orderBy(CollegeArticle::FIELD_CREATED_AT,'desc')
            ->select([
                CollegeArticle::FIELD_ID,
                CollegeArticle::FIELD_ID_POSTER,
                CollegeArticle::FIELD_COVER_IMAGE,
                CollegeArticle::FIELD_TITLE,
                CollegeArticle::FIELD_CREATED_AT
            ])
            ->get();

        return $result;
    }

    public function detail($id)
    {
        return CollegeArticle::query()->where(CollegeArticle::FIELD_ID,$id)->first();
    }

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