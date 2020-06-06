<?php

Route::group(['namespace' => 'Seongbae\Discuss\Http\Controllers', 'middleware' => ['web']], function () {

    // Discussion
    Route::get('discuss/new', 'ThreadsController@create');
    Route::get('discuss/{slug?}', 'ThreadsController@index')->name('discuss.index');
    Route::post('discuss', 'ThreadsController@store');
    Route::get('discuss/{channel}/{thread}', 'ThreadsController@show')->name('discuss.show');
    Route::patch('discuss/{channel}/{thread}', 'ThreadsController@update');
    Route::delete('discuss/{channel}/{thread}', 'ThreadsController@destroy');
    Route::post('discuss/{channel}/{thread}/replies', 'RepliesController@store');
    Route::patch('replies/{reply}', 'RepliesController@update');
    Route::delete('replies/{reply}', 'RepliesController@destroy');

    Route::post('discuss/subscribe/{type}/{id}', 'SubscriptionController@update')->name('subscription.update');

});

//Route::group(['namespace'=>'App\Modules\Discuss\Http\Controllers\Admin', 'middleware' => ['web', 'auth'], 'prefix' => 'admin'], function () {
//
//    Route::resource('channels', 'ChannelsController', ['as'=>'admin']);
//
//});
