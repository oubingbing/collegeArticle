<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin','namespace' => 'Admin','middleware'=>['web']], function () {

    //Route::group(['middleware'=>['authUser']], function () {
        /** 后台首页 */
        Route::get('/','IndexController@index');
        /** 后台主页 */
        Route::get('/dashboard','IndexController@dashboard');
        /** 获取小程序信息 */
        Route::get('/app','AppController@appInfo');
        /** 用户列表 */
        Route::get('wechat_users','UserController@userList');
        /** 用户统计 */
        Route::get('user_statistics','UserController@userStatistics');
        /** 用户列表 */
        Route::get('user/index','UserController@index');

        /** 创建文章视图页面 */
        Route::get('article/create',"ArticleController@createView");

        /** 创建文章视图页面 */
        Route::post('article/create',"ArticleController@create");

        /** 文章上传图片 **/
        Route::post('article/image_upload',"ArticleController@uploadImage");

        /** 创建笔记类别 **/
        Route::post("note_category/create","NoteCategoryController@create");

        /** 笔记簿列表 **/
        Route::get("note_category/list","NoteCategoryController@categories");

        /** 创建笔记 **/
        Route::post("note/create","NoteController@createNote");

        Route::get("note/{categoryId}/{noteId}","NoteController@detail");

        Route::post("note/update/{id}","NoteController@edit");

});

/** 退出登录 */
Route::get('/logout','Auth\LoginController@logout')->middleware(['guest','web']);

