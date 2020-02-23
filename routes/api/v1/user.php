<?php

Route::get('/user/profile', 'UserController@profile');
Route::get('/user/slurps', 'UserController@slurps');
Route::patch('/user/edit', 'UserController@edit');
Route::put('/user/edit/avatar', 'UserController@avatar');
Route::get('/user/yums', 'UserController@yums');
Route::post('/user/follow', 'UserController@postFollow');
Route::post('/user/unfollow', 'UserController@unfollow');
Route::get('/user/follow', 'UserController@getFollow');
Route::get('/user/follower', 'UserController@follower');