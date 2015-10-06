<?php

Route::group(['prefix' => 'admin', 'namespace' => 'Flysap\Media\Controllers', 'middleware' => 'role:admin'], function() {

    Route::get('media', ['as' => 'media', 'uses' => 'MediaController@lists']);
    Route::any('media/create', ['as' => 'media_create', 'uses' => 'MediaController@create']);
    Route::any('media/edit/{id}', ['as' => 'media_edit', 'uses' => 'MediaController@edit']);
    Route::get('media/delete/{id}', ['as' => 'media_delete', 'uses' => 'MediaController@delete']);
});