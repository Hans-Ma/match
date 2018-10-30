<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::group('admin', function(){
    //主页
    Route::get('index', 'admin/index/index');

    //运动员
    Route::any('ath/index', 'admin/athlete/index');
    Route::get('ath/create', 'admin/athlete/create');
    Route::post('ath/add', 'admin/athlete/save');
    Route::get('ath/delete/[:id]', 'admin/athlete/delete');
    Route::get('ath/edit/[:id]', 'admin/athlete/edit');
    Route::post('ath/edit/[:id]', 'admin/athlete/update');

    //比赛
    Route::rule('match/index', 'admin/match/index', 'get|post');
    Route::get('match/create', 'admin/match/create');
    Route::post('match/add', 'admin/match/save');
    Route::get('match/delete/[:id]', 'admin/match/delete');
    Route::get('match/edit/[:id]', 'admin/match/edit');
    Route::post('match/edit/[:id]', 'admin/match/update');

    //比赛数据
    Route::rule('data/index', 'admin/data/index', 'get|post');
    Route::post('data/read', 'admin/data/read');
    Route::get('data/create', 'admin/data/create');
    Route::post('data/add', 'admin/data/save');
    Route::get('data/delete/[:id]', 'admin/data/delete');
    Route::get('data/edit/[:id]', 'admin/data/edit');
    Route::post('data/edit/[:id]', 'admin/data/update');
    Route::get('data/upload', 'admin/data/upload');
    Route::post('data/upload', 'admin/data/addExcel');
    Route::get('data/download', 'admin/data/download');

    //比赛成绩
    Route::rule('score/index', 'admin/score/index', 'get|post');
});