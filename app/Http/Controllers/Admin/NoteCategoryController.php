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
        //$userId = request()->get("user");
        $userId = 1;

        $checkRepeat = $this->noteCategoryService->checkRepeat($userId,$name);
        if($checkRepeat){
            throw new ApiException("名字不能重复！",500);
        }

        $category = $this->noteCategoryService->create($userId,$name,$type);

        return webResponse("新建成功",200,$this->noteCategoryService->formatSingle($category));
    }

    /**
     * 获取日志分类
     *
     * @author yezi
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function categories()
    {
        //$userId = request()->get("user");
        $userId = 1;

        $notes = $this->noteCategoryService->getNoteCategories($userId);

        return $notes;
    }

    public function deleteCategory($id)
    {
        //$userId = request()->get("user");
        $userId = 1;

        try{
            \DB::beginTransaction();

            $result = $this->noteCategoryService->delete($userId,$id);
            if(!$result){
                throw new WebException("删除失败！");
            }

            $deleteNoteResult = $this->noteService->deleteByCategory($id);
            if(!$deleteNoteResult){
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