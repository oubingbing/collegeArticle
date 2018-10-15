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
use App\Http\Service\FollowService;
use App\Http\Service\NoteService;
use App\Http\Service\PraiseService;
use App\Models\Follow;
use App\Models\Note;
use App\Models\OperateStatistics;
use App\Models\PraiseNote;
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
        $query = $this->noteService->getBuilder()
            ->filter($type)
            ->sort($orderBy,$sortBy)
            ->done()
            ->with([Note::REL_STATICS=>function($query){
                $query->select([
                    OperateStatistics::FIELD_ID,
                    OperateStatistics::FIELD_VIEW,
                    OperateStatistics::FIELD_ID_OBJ,
                    OperateStatistics::FIELD_TYPE
                ]);
            }]);
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
        $user = request()->input("user");

        $result = $this->noteService->getNoteById($id,[
                Note::FIELD_ID,Note::FIELD_ID_POSTER,Note::FIELD_ID_CATEGORY,Note::FIELD_TITLE,Note::FIELD_CONTENT,Note::FIELD_CREATED_AT
            ]);
        $result->{Note::REL_POSTER};
        $result->{Note::REL_CATEGORY};

        //是否点赞文章
        $result->praise = app(PraiseService::class)->checkPraise($user->id,$result->id,PraiseNote::ENUM_TYPE_NOTE);

        $followService = app(FollowService::class);
        $result->follow_author = $followService->checkFollow($user->id,$result->{Note::FIELD_ID_POSTER},Follow::ENUM_TYPE_AUTHOR);
        $result->follow_note = $followService->checkFollow($user->id,$result->id,Follow::ENUM_TYPE_NOTE);

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