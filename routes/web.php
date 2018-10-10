<?php


Route::group(['namespace' => 'Frontend'], function () {
    Route::get('/','IndexController@index');
});

Route::group(['namespace' => 'Auth'], function () {

    Route::post("register","RegisterController@register");

    Route::get("register","RegisterController@registerView");

    /** 登录视图 **/
    Route::get('/login','LoginController@loginView');

    /** 登录 **/
    Route::post("/login","LoginController@login");
});