<?php

Route::group(['prefix' => 'admin', 'namespace' => 'Flysap\Media\Controllers', 'middleware' => 'role:admin'], function() {

    Route::get('media', ['as' => 'media', 'uses' => 'MediaController@lists']);
});