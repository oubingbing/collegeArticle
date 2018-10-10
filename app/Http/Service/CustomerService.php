<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 18:01
 */

namespace App\Http\Service;


use App\Exceptions\WebException;
use App\Models\NoteCategory;

class CustomerService
{
    /**
     * 初始化用户信息
     *
     * @param $userId
     * @throws WebException
     */
    public function initCustomer($userId)
    {
        $noteCategoryService = app(NoteCategoryService::class);

        //初始化笔记簿
        $light = $noteCategoryService->create($userId,"灯塔",NoteCategory::ENUM_USE_TYPE_COLLEGE);
        if(!$light){
            throw new WebException("初始化失败");
        }

        $code = $noteCategoryService->create($userId,"共享代码",NoteCategory::ENUM_USE_TYPE_CODING);
        if(!$code){
            throw new WebException("初始化失败");
        }
    }
}