<?php

use App\Events\Chat;
use Illuminate\Support\Facades\Redis;

Route::get('/test_edit','Admin\IndexController@test');

/** 测试 */
//App\Http\Controllers\App\Http\IM\IndexController
Route::get('test_socket','IM\IndexController@chatRoom');
Route::get('socket','IM\IndexController@socket');
Route::get('bind','IM\IndexController@bindSocket');
Route::post('send','IM\IndexController@sendSocket');
