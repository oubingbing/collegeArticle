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
use App\Http\Service\QiNiuService;
use App\Models\Note;
use App\Models\NoteCategory;

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
        $user = request()->get("user");
        $userId = $user->id;

        if(count($attachments) == 2){
            $attachments = $attachments[0];
        }elseif(count($attachments) > 3){
            $attachments = collect($attachments)->filter(function ($item,$index){
               if($index <= 2){
                   return $item;
               }
            });
        }

        $attachments = collect($attachments)->toArray();

        $category = $this->noteCategoryService->getCategoryById($userId,$categoryId);
        if(!$category){
            throw new WebException("日志本不存在");
        }

        $checkoutRepeat = $this->noteService->checkRepeat($title,$category->id);
        if($checkoutRepeat){
            throw new WebException("名字已存在");
        }

        $useType = $category->{NoteCategory::FIELD_USE_TYPE};

        try{
            \DB::beginTransaction();

            $result = $this->noteService->create($categoryId,$user->id,$title,$content,$attachments,$useType,$type);
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
        $user = request()->get("user");
        $userId = $user->id;

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
        $user = request()->get("user");
        $userId = $user->id;
        $content = request()->input("content");
        $attachments = request()->input("attachments");
        if(is_null($content)){
            throw new WebException("内容不能为空",500);
        }

        if(count($attachments) == 2){
            $attachments = $attachments[0];
        }elseif(count($attachments) > 3){
            $attachments = collect($attachments)->filter(function ($item,$index){
                if($index <= 2){
                    return $item;
                }
            });
        }

        $note = $this->noteService->getNoteById($id);
        if(!$note){
            throw new WebException("日志不存在",500);
        }

        $category = $this->noteCategoryService->getCategoryById($userId,$note->{Note::FIELD_ID_CATEGORY});
        if(!$category){
            throw new WebException("日志不存在",500);
        }

        if($category->{NoteCategory::FIELD_USE_TYPE} != NoteCategory::ENUM_USE_TYPE_NOTE){
            if(empty($attachments)){
                throw new WebException("灯塔文章需要至少上传一张图片");
            }
        }

        try{
            \DB::beginTransaction();

            $result = $this->noteService->updateContent($content,collect($attachments)->toArray(),$note);
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
        $user = request()->get("user");
        $userId = $user->id;

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

    /**
     * 上传文章图片
     *
     * @author yezi
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws ApiException
     */
    public function uploadImage(\Illuminate\Http\Request $request){
        $filePath =  $request->file('editormd-image-file')->path();
        $token = app(QiNiuService::class)->getToken();
        if($token == ''){
            throw new WebException("获取token失败");
        }

        $result = app(QiNiuService::class)->uploadImage($token,$filePath);
        $data['success'] = 1;
        $data['message'] = '上传成功';
        $data['url'] = env('QI_NIU_DOMAIN').$result['key'];
        return $data;
    }

    public function editTitle($id)
    {
        $name = request()->input("title");
        $user = request()->get("user");
        $userId = $user->id;

        if(empty($name)){
            throw new WebException("名字不能为空");
        }

        $note = $this->noteService->getNoteById($id);
        if(!$note){
            throw new WebException("笔记不存在");
        }

        if($name == $note->{Note::FIELD_TITLE}){
            return;
        }

        $category = $this->noteCategoryService->getCategoryById($userId,$note->{Note::FIELD_ID_CATEGORY});
        if(!$category){
            throw new WebException("笔记不存在");
        }

        $checkoutRepeat = $this->noteService->checkRepeat($name,$category->id);
        if($checkoutRepeat){
            throw new WebException("名字不能重复");
        }

        try{
            \DB::beginTransaction();

            $result = $this->noteService->updateTitle($name,$note);
            if(!$result){
                throw new WebException("重命名失败");
            }

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new WebException($exception->getMessage());
        }

        return (string)$result;
    }
}