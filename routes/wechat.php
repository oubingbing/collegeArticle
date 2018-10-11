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
use App\Http\Wechat\CollegeArticleController;
use App\Http\Wechat\LocationController;
use App\Http\Wechat\NoteController;
use App\Http\Wechat\TravelController;
use App\Http\Wechat\UserController;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['prefix' => 'wechat','middleware' => 'api.throttle', 'limit' => 100, 'expires' => 1], function ($api) {

        $api->group(['prefix' => 'auth'], function ($api) {
            /** 登录 */
            $api->post('/login', LoginController::class . '@apiLogin');
        });

        $api->group(['middleware' => 'wechat'], function ($api) {

            /** 获取用户信息 **/
            $api->get('/user',UserController::class . '@personal');

            /** 获取文章列表 **/
            $api->get("/notes",NoteController::class . "@noteList");

            /** 获取日志列表 **/
            $api->get("/notes",NoteController::class . "@noteList");

            /** 获取文章详情 **/
            $api->get("/note/{id}",NoteController::class . "@detail");
        });

    });

});


