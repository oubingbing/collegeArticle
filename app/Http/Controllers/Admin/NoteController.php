<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/29 0029
 * Time: 11:38
 */

namespace App\Http\Controllers\Admin;


use App\Exceptions\WebException;
use App\Http\Controllers\Controller;
use App\Http\Service\NoteCategoryService;
use App\Http\Service\NoteService;
use App\Models\Note;

class NoteController extends Controller
{
    private $noteService;
    private $noteCategoryService;

    public function __construct(NoteService $noteService,NoteCategoryService $noteCategoryService)
    {
        $this->noteService = $noteService;
        $this->noteCategoryService = $noteCategoryService;
    }

    /**
     * 新建日志
     *
     * @author yezi
     * @return mixed
     */
    public function createNote()
    {
        $title = request()->input("title");
        $categoryId = request()->input("category_id");
        $content = request()->input("content","");
        $attachments = request()->input("attachments",[]);
        $type = request()->input("type",Note::ENUM_TYPE_PRIVATE);
        //$userId = request()->get("user");
        $userId = 1;

        try{
            \DB::beginTransaction();

            $result = $this->noteService->create($categoryId,$title,$content,$attachments,$type);
            $result = $this->noteService->formatSingle($result);

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new WebException($exception->getMessage());
        }

        return $result;
    }

    /**
     * 获取日志详情
     *
     * @author yezi
     * @param $categoryId
     * @param $noteId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     * @throws WebException
     */
    public function detail($categoryId,$noteId)
    {
        //$userId = request()->get("user");
        $userId = 1;

        $category = $this->noteCategoryService->getCategoryById($userId,$categoryId);
        if(!$category){
            throw new WebException("日志不存在",500);
        }

        $note = $this->noteService->getNoteById($noteId);

        return $note;
    }

    /**
     * 编辑日志
     *
     * @author yezi
     * @param $id
     * @return mixed
     * @throws WebException
     */
    public function edit($id)
    {
        //$userId = request()->get("user");
        $userId = 1;
        $content = request()->input("content");
        if(is_null($content)){
            throw new WebException("内容不能为空",500);
        }

        $note = $this->noteService->getNoteById($id);
        if(!$note){
            throw new WebException("日志不存在",500);
        }

        $category = $this->noteCategoryService->getCategoryById($userId,$note->{Note::FIELD_ID_CATEGORY});
        if(!$category){
            throw new WebException("日志不存在",500);
        }

        try{
            \DB::beginTransaction();

            $result = $this->noteService->updateContent($content,$note);
            if(!$result){
                throw new WebException("保存失败",500);
            }

            $result = $this->noteService->formatSingle($this->noteService->getNoteById($note->id));

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new WebException($exception->getMessage());
        }

        return $result;
    }

    /**
     * 删除日志
     *
     * @author yezi
     * @param $id
     * @return bool|mixed|null
     * @throws WebException
     */
    public function deleteNote($id)
    {
        //$userId = request()->get("user");
        $userId = 1;

        try{
            \DB::beginTransaction();

            $note = $this->noteService->getNoteById($id);
            if(!$note){
                throw new WebException("日志不存在");
            }

            $category = $this->noteCategoryService->getCategoryById($userId,$note->{Note::FIELD_ID_CATEGORY});
            if(!$category){
                throw new WebException("日志不存在");
            }

            $result = $this->noteService->deleteById($id);
            if(!$result){
                throw new WebException("删除失败");
            }

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new WebException($exception->getMessage());
        }

        return (string)$result;
    }
}