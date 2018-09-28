<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28 0028
 * Time: 15:54
 */

namespace App\Http\Controllers\Admin;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\NoteCategoryService;
use App\Models\NoteCategory;

class NoteCategoryController extends Controller
{
    private $noteCategoryService;

    public function __construct(NoteCategoryService $service)
    {
        $this->noteCategoryService = $service;
    }

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

        return webResponse("新建成功",200,$category);
    }
}