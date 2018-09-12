<?php

namespace App\Http\Wechat;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\YunPianService;
use App\Models\LocationLogModel;
use Carbon\Carbon;

class IndexController extends Controller
{
    /**
     * 获取用户信息
     *
     * @author yezi
     *
     * @return mixed
     */
    public function index()
    {
        $user = request()->get('user');

        return $user;
    }

    public function recordLocation(){
        $latitude = request()->input("latitude");
        $longitude = request()->input("longitude");

        LocationLogModel::create([
            LocationLogModel::FIELD_ID_USER=>1,
            LocationLogModel::FIELD_LATITUDE=>$latitude,
            LocationLogModel::FIELD_LONGITUDE=>$longitude,
            LocationLogModel::FIELD_LOCATE_AT=>Carbon::now()
        ]);
    }

    /**
     * 发送验证码
     *
     * @author yezi
     *
     * @return mixed
     * @throws ApiException
     */
    public function getMessageCode()
    {
        $user = request()->input('user');
        $phone = request()->input('phone');

        if(!$phone){
            throw new ApiException('手机号码不能为空！',500);
        }

        $validPhone = validMobile($phone);
        if($validPhone != 1){
            throw new ApiException('手机号码格式错误',500);
        }

        $result = app(YunPianService::class)->sendMessageCode($phone);
        if($result['code'] != 0){
            throw new ApiException('发送失败！',500);
        }

        return $result;
    }
}