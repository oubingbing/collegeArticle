<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\QiNiuController;
use App\Http\Wechat\ChatController;
use App\Http\Wechat\CommentController;
use App\Http\Wechat\CompareFaceController;
use App\Http\Wechat\FollowController;
use App\Http\Wechat\FormIdController;
use App\Http\Wechat\InboxController;
use App\Http\Wechat\IndexController;
use App\Http\Wechat\MatchLoveController;
use App\Http\Wechat\PartTimeJobController;
use App\Http\Wechat\PostController;
use App\Http\Wechat\PraiseController;
use App\Http\Wechat\SaleFriendController;
use App\Http\Wechat\StepTravelController;
use App\Http\Wechat\TopicController;
use App\Http\Wechat\TravelController;
use App\Http\Wechat\UserController;
use Illuminate\Support\Facades\Log;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['prefix' => 'wechat','middleware' => 'api.throttle', 'limit' => 100, 'expires' => 1], function ($api) {

        $api->get('/config',IndexController::class . '@config');

        $api->post('/location',IndexController::class . '@recordLocation');

        $api->group(['prefix' => 'auth', 'middleware' => 'before'], function ($api) {

            /** 登录 */
            $api->post('/login', LoginController::class . '@apiLogin');

        });

    });

});


