<?php

Route::post('/slurp', 'SlurpController@slurp');
Route::post('/slurp/text', 'SlurpController@text');
Route::post('/slurp/yum', 'SlurpController@yum');
Route::post('/slurp/unyum', 'SlurpController@unyum');
Route::get('/slurp/detail', 'SlurpController@detail');
Route::get('/slurp/yums', 'SlurpController@yums');
Route::delete('/slurp/delete', 'SlurpController@delete');