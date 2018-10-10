<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28 0028
 * Time: 15:54
 */

namespace App\Http\Controllers\Admin;


use App\Exceptions\ApiException;
use App\Exceptions\WebException;
use App\Http\Controllers\Controller;
use App\Http\Service\NoteCategoryService;
use App\Http\Service\NoteService;
use App\Models\NoteCategory;

class NoteCategoryController extends Controller
{
    private $noteCategoryService;
    private $noteService;

    public function __construct(NoteCategoryService $service,NoteService $noteService)
    {
        $this->noteCategoryService = $service;
        $this->noteService = $noteService;
    }

    /**
     * 新建笔记类别
     *
     * @author yezi
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function create()
    {
        $name = request()->input("name");
        $type = request()->input("type",NoteCategory::ENUM_TYPE_PRIVATE);
        $user = request()->get("user");
        $userId = $user->id;

        $checkRepeat = $this->noteCategoryService->checkRepeat($userId,$name);
        if($checkRepeat){
            throw new WebException("名字已存在！",500);
        }

        try{
            \DB::beginTransaction();

            $category = $this->noteCategoryService->create($userId,$name,$type);
            $result = $this->noteCategoryService->formatSingle($category);

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new WebException($exception->getMessage());
        }

        return $result;
    }

    /**
     * 获取日志分类
     *
     * @author yezi
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function categories()
    {
        $user = request()->get("user");
        $userId = $user->id;

        $notes = $this->noteCategoryService->getNoteCategories($userId);

        return $notes;
    }

    /**
     * 删除日志类目
     *
     * @author yezi
     * @param $id
     * @return string
     * @throws WebException
     */
    public function deleteCategory($id)
    {
        $user = request()->get("user");
        $userId = $user->id;

        $category = $this->noteCategoryService->getCategoryById($userId,$id);
        if($category->{NoteCategory::FIELD_USE_TYPE} != NoteCategory::ENUM_USE_TYPE_NOTE){
            throw new WebException("笔记本无法删除");
        }

        try{
            \DB::beginTransaction();

            $result = $this->noteCategoryService->delete($userId,$id);
            if(!$result){
                throw new WebException("删除失败！");
            }

            $this->noteService->deleteByCategory($id);

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new WebException($exception->getMessage());
        }

        return (string)$result;
    }

    public function rename($id)
    {
        $name = request()->input("name");
        $user = request()->get("user");

        if(empty($name)){
            throw new WebException("名字不能为空");
        }

        $category = $this->noteCategoryService->getCategoryById($user->id,$id);
        if(!$category){
            throw new WebException("笔记本不存在");
        }

        if($category->{NoteCategory::FIELD_USE_TYPE} != NoteCategory::ENUM_USE_TYPE_NOTE){
            throw new WebException("笔记本无法重命名");
        }

        $checkRepeat = $this->noteCategoryService->checkRepeat($user->id,$name);
        if($checkRepeat){
            throw new WebException("名字不能重复",500);
        }

        try{
            \DB::beginTransaction();

            $result = $this->noteCategoryService->updateName($name,$id);
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