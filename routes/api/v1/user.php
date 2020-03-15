<?php

Route::get('/user/profile', 'UserController@profile');
Route::get('/user/slurps', 'UserController@slurps');
Route::patch('/user/edit', 'UserController@edit');
Route::post('/user/edit/avatar', 'UserController@avatar');
Route::get('/user/yums', 'UserController@yums');
Route::post('/user/follow', 'UserController@follow');
Route::post('/user/unfollow', 'UserController@unfollow');
Route::get('/user/followee', 'UserController@followee');
Route::get('/user/follower', 'UserController@follower');