<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/20 0020
 * Time: 11:12
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\NoteService;
use App\Models\Note;
use App\Models\User;

class NoteController extends Controller
{
    private $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    /**
     * 灯塔文章列表
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
        $type = request()->input("note_type");

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];

        $selectData = [
            Note::FIELD_ID,
            Note::FIELD_TITLE,
            Note::FIELD_ATTACHMENTS,
            Note::FIELD_USE_TYPE,
            Note::FIELD_ID_CATEGORY,
            Note::FIELD_ID_POSTER,
            Note::FIELD_CREATED_AT
        ];
        $query = $this->noteService->getBuilder()->filter($type)->sort($orderBy,$sortBy)->done();
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
        $result = $this->noteService->getNoteById($id);
        $result->{Note::REL_POSTER};
        $result->{Note::REL_CATEGORY};

        return $this->noteService->formatSingle($result);
    }

    public function getNoteListByCategory($categoryId)
    {
        $user = request()->input("user");

        if(empty($categoryId)){
            throw new ApiException("id不能为空");
        }

        $list = $this->noteService->getNotesByCategoryId($categoryId);

        return $list;
    }
}