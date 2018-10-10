<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin','namespace' => 'Admin','middleware'=>['web']], function () {

    //Route::group(['middleware'=>['authUser']], function () {

        /** 用户统计 */
        Route::get('user_statistics','UserController@userStatistics');

        /** 用户列表 */
        Route::get('user/index','UserController@index');

        /** 创建笔记类别 **/
        Route::post("note_category/create","NoteCategoryController@create");

        /** 笔记簿列表 **/
        Route::get("note_category/list","NoteCategoryController@categories");

        /** 创建笔记 **/
        Route::post("note/create","NoteController@createNote");

        /** 获取日志详情 **/
        Route::get("note/{categoryId}/{noteId}","NoteController@detail");

        /** 编辑日志 **/
        Route::post("note/update/{id}","NoteController@edit");

        /** 删除日志类目 **/
        Route::post("note_category/{id}/delete","NoteCategoryController@deleteCategory");

        /** 删除日志 **/
        Route::post("note/{id}/delete","NoteController@deleteNote");

        /** 文章上传图片 **/
        Route::post('note/image_upload',"NoteController@uploadImage");

        /** 重命名日志本 **/
        Route::post("note_category/{id}/rename","NoteCategoryController@rename");

        /** 重命名日志 **/
        Route::post("note/{id}/rename","NoteController@editTitle");
});

/** 退出登录 */
Route::get('/logout','Auth\LoginController@logout')->middleware(['guest','web']);

