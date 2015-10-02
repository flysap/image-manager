<?php

Route::group(['prefix' => 'admin', 'namespace' => 'Flysap\Media\Controllers', 'middleware' => 'role:admin'], function() {

    Route::get('media', ['as' => 'media', 'uses' => 'MediaController@lists']);
    Route::get('media/edit/{id}', ['as' => 'media_edit', 'uses' => 'MediaController@edit']);
    Route::post('media/edit/{id}', ['as' => 'media_update', 'uses' => 'MediaController@update']);
    Route::get('media/delete/{id}', ['as' => 'media_delete', 'uses' => 'MediaController@delete']);
});