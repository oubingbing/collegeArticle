<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 18:01
 */

namespace App\Http\Service;


use App\Exceptions\WebException;
use App\Models\Customer as Model;
use App\Models\Customer;
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
    }

    public function getCustomerByPhone($phone)
    {
        return Model::query()->where(Model::FIELD_PHONE,$phone)->first();
    }

    public function updateAvatar($phone,$avatar)
    {
        $customer = $this->getCustomerByPhone($phone);
        if(!$customer){
            return false;
        }

        $customer->{Model::FIELD_AVATAR} = $avatar;
        $result = $customer->save();
        return $result;
    }

    public function getCustomerById($id)
    {
        return Model::find($id);
    }
}