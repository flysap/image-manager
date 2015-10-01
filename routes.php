<?php

Route::group(['prefix' => 'admin', 'namespace' => 'Flysap\ImageManager\Controllers', 'middleware' => 'role:admin'], function() {

    /**
     * That controller will show all settings from file config files which are registered to global
     *  config repository and merge with database config as database priority .. All the changes will be stored in database .
     *
     */
    Route::get('images', ['as' => 'images', 'uses' => 'ImageController@lists']);
});