<?php

Route::group(['namespace' => 'Seongbae\Discuss\Http\Controllers', 'middleware' => ['web']], function () {

    // Discussion
    Route::get('discuss/new', 'ThreadsController@create');
    Route::get('discuss/{slug?}', 'ThreadsController@index')->name('discuss.index');
    Route::post('discuss', 'ThreadsController@store')->name('discuss.store');
    Route::get('discuss/{channel}/{thread}', 'ThreadsController@show')->name('discuss.show');
    Route::patch('discuss/{channel}/{thread}', 'ThreadsController@update')->name('discuss.update');
    Route::delete('discuss/{channel}/{thread}', 'ThreadsController@destroy')->name('discuss.destroy');

    Route::post('discuss/{channel}/{thread}/replies', 'RepliesController@store')->name('reply.store');
    Route::patch('replies/{reply}', 'RepliesController@update')->name('reply.update');
    Route::delete('replies/{reply}', 'RepliesController@destroy')->name('reply.destroy');

    Route::post('discuss/subscribe/{type}/{id}', 'SubscriptionController@update')->name('subscription.update');

});
