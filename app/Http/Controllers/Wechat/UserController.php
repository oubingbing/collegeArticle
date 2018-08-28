<?php

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\SendMessageService;
use App\Http\Service\UserService;
use App\Jobs\UserLogs;
use App\Models\Colleges;
use App\Models\User;
use App\Models\WechatApp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{
    /**
     * 获取用户信息
     *
     * @author yezi
     *
     * @return array|string
     */
    public function user($id)
    {
        $user = User::find($id);

        return $user;
    }

    /**
     * 获取个人信息
     *
     * @author yezi
     *
     * @return array|string
     */
    public function personal()
    {
        $user = request()->input('user');

        return $user;
    }

}