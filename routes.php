<?php

Route::group(['prefix' => 'admin', 'namespace' => 'Flysap\ImageManager\Controllers', 'middleware' => 'role:admin'], function() {

    Route::get('images', ['as' => 'images', 'uses' => 'ImageController@lists']);
});