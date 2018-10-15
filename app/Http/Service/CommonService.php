<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15 0015
 * Time: 10:12
 */

namespace App\Http\Service;


class CommonService
{

    public function checkObj($objId,$type)
    {
        switch ($type){
            case 1:
                $result = app(CustomerService::class)->getCustomerById($objId);
                break;
            case 2:
                $result = app(NoteCategoryService::class)->getCategory($objId);
                break;
            case 3:
                $result = app(NoteService::class)->getNoteById($objId);
                break;
            default:
                $result = "";
        }

        return $result;
    }

}